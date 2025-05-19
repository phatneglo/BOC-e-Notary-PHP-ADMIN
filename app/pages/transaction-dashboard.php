<?php
namespace PHPMaker2024\eNotary;

// Security check
if (!$Security->isLoggedIn()) {
    return;
}

// Get status filter parameter
$statusFilter = Get("status_filter", "pending"); // Default to pending transactions

// Get date range parameters
$dateRange = Get("date_range", "this_month"); // Default to this month
$startDate = "";
$endDate = "";
$customStartDate = Get("custom_start_date", "");
$customEndDate = Get("custom_end_date", "");

// Process date range
switch ($dateRange) {
    case "today":
        $startDate = date("Y-m-d 00:00:00");
        $endDate = date("Y-m-d 23:59:59");
        break;
    case "this_week":
        $startDate = date("Y-m-d 00:00:00", strtotime("monday this week"));
        $endDate = date("Y-m-d 23:59:59", strtotime("sunday this week"));
        break;
    case "this_month":
        $startDate = date("Y-m-01 00:00:00");
        $endDate = date("Y-m-t 23:59:59");
        break;
    case "last_month":
        $startDate = date("Y-m-01 00:00:00", strtotime("first day of last month"));
        $endDate = date("Y-m-t 23:59:59", strtotime("last day of last month"));
        break;
    case "this_year":
        $startDate = date("Y-01-01 00:00:00");
        $endDate = date("Y-12-31 23:59:59");
        break;
    case "custom":
        if (!empty($customStartDate)) {
            $startDate = date("Y-m-d 00:00:00", strtotime($customStartDate));
        } else {
            $startDate = date("Y-m-01 00:00:00"); // Default to first day of current month
        }
        if (!empty($customEndDate)) {
            $endDate = date("Y-m-d 23:59:59", strtotime($customEndDate));
        } else {
            $endDate = date("Y-m-t 23:59:59"); // Default to last day of current month
        }
        break;
    default:
        $startDate = date("Y-m-01 00:00:00"); // Default to first day of current month
        $endDate = date("Y-m-t 23:59:59"); // Default to last day of current month
        break;
}

// Format date condition
$dateCondition = "";
if (!empty($startDate) && !empty($endDate)) {
    $dateCondition = " AND pt.created_at BETWEEN " . QuotedValue($startDate, DataType::DATE) . 
                     " AND " . QuotedValue($endDate, DataType::DATE);
}

// Build status filter condition
$statusCondition = "";
if ($statusFilter != "all") {
    $statusCondition = " AND pt.status = " . QuotedValue($statusFilter, DataType::STRING);
}

// Get payment methods for dropdown
$paymentMethods = ExecuteRows("
    SELECT method_id, method_name, method_code
    FROM payment_methods
    WHERE is_active = TRUE
    ORDER BY method_name", 
"DB");

// Format date range for display
function formatDateRangeText($dateRange, $startDate, $endDate) {
    switch ($dateRange) {
        case "today":
            return "Today (" . date("M d, Y") . ")";
        case "this_week":
            return "This Week (" . date("M d", strtotime("monday this week")) . " - " . 
                  date("M d, Y", strtotime("sunday this week")) . ")";
        case "this_month":
            return "This Month (" . date("M Y") . ")";
        case "last_month":
            return "Last Month (" . date("M Y", strtotime("first day of last month")) . ")";
        case "this_year":
            return "This Year (" . date("Y") . ")";
        case "custom":
            return "Custom (" . date("M d, Y", strtotime($startDate)) . " - " . 
                  date("M d, Y", strtotime($endDate)) . ")";
        default:
            return "This Month (" . date("M Y") . ")";
    }
}

$dateRangeText = formatDateRangeText($dateRange, $startDate, $endDate);

// Get transactions with pagination
$page = Get("page", 1);
$perPage = 20;
$offset = ($page - 1) * $perPage;

$transactions = ExecuteRows("
    SELECT 
        pt.transaction_id,
        pt.request_id,
        pt.user_id,
        pt.transaction_reference,
        pt.amount,
        pt.currency,
        pt.status,
        pt.payment_date,
        pt.gateway_reference,
        pt.gateway_response,
        pt.fee_amount,
        pt.total_amount,
        pt.payment_receipt_url,
        pt.created_at,
        pt.ip_address,
        pt.notes,
        pm.method_name,
        pm.method_code,
        CONCAT(u.first_name, ' ', u.last_name) as user_name,
        u.email as user_email,
        nr.document_id
    FROM payment_transactions pt
    LEFT JOIN payment_methods pm ON pt.payment_method_id = pm.method_id
    LEFT JOIN users u ON pt.user_id = u.user_id
    LEFT JOIN notarization_requests nr ON pt.request_id = nr.request_id
    WHERE 1=1
    $statusCondition
    $dateCondition
    ORDER BY pt.created_at DESC
    LIMIT $perPage OFFSET $offset", 
"DB");

// Get total count for pagination
$totalTransactions = ExecuteScalar("
    SELECT COUNT(*) 
    FROM payment_transactions pt
    WHERE 1=1
    $statusCondition
    $dateCondition", 
"DB");

$totalPages = ceil($totalTransactions / $perPage);

// Get summary stats
$pendingCount = ExecuteScalar("
    SELECT COUNT(*) 
    FROM payment_transactions pt
    WHERE pt.status = 'pending'
    $dateCondition", 
"DB");

$completedCount = ExecuteScalar("
    SELECT COUNT(*) 
    FROM payment_transactions pt
    WHERE pt.status = 'completed'
    $dateCondition", 
"DB");

$totalAmount = ExecuteScalar("
    SELECT SUM(amount) 
    FROM payment_transactions pt
    WHERE 1=1
    $statusCondition
    $dateCondition", 
"DB");




// Process approval/rejection if form submitted
// Process approval/rejection via GET parameters
$action = Get("action");
$transactionId = Get("transaction_id");

if ($action && $transactionId) {
    try {
        // Begin transaction
        Execute("BEGIN", "DB");
        
        if ($action == "approve") {
            // Update transaction status to completed
            Execute("
                UPDATE payment_transactions
                SET 
                    status = 'completed',
                    payment_date = CURRENT_TIMESTAMP,
                    updated_at = CURRENT_TIMESTAMP,
                    notes = " . QuotedValue('Approved by admin: ' . CurrentUserName(), DataType::STRING) . "
                WHERE transaction_id = " . QuotedValue($transactionId, DataType::NUMBER), 
            "DB");
            
            // Also update the notarization request payment status
            Execute("
                UPDATE notarization_requests
                SET 
                    payment_status = 'paid',
                    modified_at = CURRENT_TIMESTAMP
                WHERE request_id = (
                    SELECT request_id 
                    FROM payment_transactions 
                    WHERE transaction_id = " . QuotedValue($transactionId, DataType::NUMBER) . "
                )", 
            "DB");
            
            // Add success message - fixed with variable
            $msg = "success";
            $text = "Transaction #$transactionId approved successfully.";
            AddMessage($msg, $text);
        } else if ($action == "reject") {
            $rejectReason = Get("reject_reason", "Rejected by admin");
            
            // Update transaction status to rejected
            Execute("
                UPDATE payment_transactions
                SET 
                    status = 'rejected',
                    updated_at = CURRENT_TIMESTAMP,
                    notes = " . QuotedValue('Rejected by admin: ' . CurrentUserName() . ' - ' . $rejectReason, DataType::STRING) . "
                WHERE transaction_id = " . QuotedValue($transactionId, DataType::NUMBER), 
            "DB");
            
            // Add success message - fixed with variable
            $msg = "success";
            $text = "Transaction #$transactionId rejected successfully.";
            AddMessage($msg, $text);
        }
        
        // Commit transaction
        Execute("COMMIT", "DB");
        
        // Redirect to refresh the page
        header("Location: " . GetUrl("PaymentDashboard"));
        exit();
    } catch (Exception $e) {
        // Rollback on error
        Execute("ROLLBACK", "DB");
        
        // Add error message - fixed with variable
        $msg = "danger";
        $text = "Error processing transaction: " . $e->getMessage();
        AddMessage($msg, $text);
    }
}
// Helper function to format JSON for display
function formatJSONForDisplay($jsonString) {
    if (empty($jsonString)) return "-";
    
    $jsonArray = json_decode($jsonString, true);
    if (!$jsonArray) return $jsonString;
    
    $output = "<ul class='list-unstyled mb-0'>";
    foreach ($jsonArray as $key => $value) {
        $key = ucwords(str_replace('_', ' ', $key));
        $output .= "<li><strong>$key:</strong> $value</li>";
    }
    $output .= "</ul>";
    
    return $output;
}

// Get transaction stats for charts
$statusStats = ExecuteRows("
    SELECT 
        status, 
        COUNT(*) as count,
        SUM(amount) as total_amount
    FROM payment_transactions pt
    WHERE 1=1
    $dateCondition
    GROUP BY status
    ORDER BY count DESC", 
"DB");

// Get transaction timeline data
$timelineData = ExecuteRows("
    WITH RECURSIVE date_range AS (
        SELECT DATE_TRUNC('month', CURRENT_DATE - INTERVAL '5 months') AS month
        UNION ALL
        SELECT month + INTERVAL '1 month'
        FROM date_range
        WHERE month < DATE_TRUNC('month', CURRENT_DATE)
    )
    SELECT 
        TO_CHAR(dr.month, 'Mon YYYY') as month_label,
        dr.month as month_date,
        COALESCE(COUNT(pt.transaction_id), 0) as transaction_count,
        COALESCE(SUM(CASE WHEN pt.status = 'completed' THEN 1 ELSE 0 END), 0) as completed_count,
        COALESCE(SUM(CASE WHEN pt.status = 'pending' THEN 1 ELSE 0 END), 0) as pending_count,
        COALESCE(SUM(CASE WHEN pt.status = 'rejected' THEN 1 ELSE 0 END), 0) as rejected_count,
        COALESCE(SUM(pt.amount), 0) as total_amount
    FROM date_range dr
    LEFT JOIN payment_transactions pt ON 
        DATE_TRUNC('month', pt.created_at) = dr.month
    WHERE 1=1
    " . ($dateCondition ? str_replace("pt.created_at", "pt.created_at", $dateCondition) : "") . "
    GROUP BY dr.month, month_label
    ORDER BY dr.month",
"DB");


// Page rendering starts here
?>

<style>
.status-badge {
    min-width: 85px;
    text-align: center;
}
.payment-method-icon {
    font-size: 1.5em;
    margin-right: 8px;
}
.detail-row {
    display: none;
}
.action-buttons {
    min-width: 180px;
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- Title Section -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="mb-1">Transaction Management Dashboard</h4>
            <p class="text-muted mb-0">View, approve, and manage payment transactions</p>
            <p class="mb-0"><strong>Date Range:</strong> <?php echo $dateRangeText; ?></p>
        </div>
    </div>
    
    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-12">
            <form id="filterForm" method="get" action="">
                <div class="card shadow-sm">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label small">Transaction Status</label>
                                <select class="form-select form-select-sm" name="status_filter" id="statusSelect">
                                    <option value="all" <?php echo ($statusFilter == "all") ? "selected" : ""; ?>>All Statuses</option>
                                    <option value="pending" <?php echo ($statusFilter == "pending") ? "selected" : ""; ?>>Pending</option>
                                    <option value="completed" <?php echo ($statusFilter == "completed") ? "selected" : ""; ?>>Completed</option>
                                    <option value="rejected" <?php echo ($statusFilter == "rejected") ? "selected" : ""; ?>>Rejected</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label small">Date Range</label>
                                <select class="form-select form-select-sm" name="date_range" id="dateRangeSelect">
                                    <option value="today" <?php echo ($dateRange == "today") ? "selected" : ""; ?>>Today</option>
                                    <option value="this_week" <?php echo ($dateRange == "this_week") ? "selected" : ""; ?>>This Week</option>
                                    <option value="this_month" <?php echo ($dateRange == "this_month") ? "selected" : ""; ?>>This Month</option>
                                    <option value="last_month" <?php echo ($dateRange == "last_month") ? "selected" : ""; ?>>Last Month</option>
                                    <option value="this_year" <?php echo ($dateRange == "this_year") ? "selected" : ""; ?>>This Year</option>
                                    <option value="custom" <?php echo ($dateRange == "custom") ? "selected" : ""; ?>>Custom Range</option>
                                </select>
                            </div>
                            
                            <div id="customDateFields" class="<?php echo ($dateRange != "custom") ? "d-none" : ""; ?> col-md-4">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small">Start Date</label>
                                        <input type="date" class="form-control form-control-sm" name="custom_start_date" 
                                               value="<?php echo !empty($customStartDate) ? $customStartDate : date("Y-m-d", strtotime($startDate)); ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">End Date</label>
                                        <input type="date" class="form-control form-control-sm" name="custom_end_date" 
                                               value="<?php echo !empty($customEndDate) ? $customEndDate : date("Y-m-d", strtotime($endDate)); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="<?php echo ($dateRange != "custom") ? "col-md-4" : "d-none"; ?> d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Apply Filters</button>
                            </div>
                            
                            <div class="<?php echo ($dateRange == "custom") ? "col-12 mt-2" : "d-none"; ?>">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 me-3 bg-primary-subtle rounded">
                            <i class="fas fa-money-bill-wave text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Total Amount</h6>
                            <h2 class="mb-0">₱<?php echo number_format($totalAmount ?? 0); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 me-3 bg-warning-subtle rounded">
                            <i class="fas fa-clock text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Pending Transactions</h6>
                            <h2 class="mb-0"><?php echo number_format($pendingCount); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 me-3 bg-success-subtle rounded">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Completed Transactions</h6>
                            <h2 class="mb-0"><?php echo number_format($completedCount); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Transaction Charts -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Transaction Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Transaction Amount by Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="amountChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Transaction Timeline</h5>
                </div>
                <div class="card-body">
                    <canvas id="timelineChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Transactions</h5>
                    <span class="badge bg-primary"><?php echo number_format($totalTransactions); ?> transactions</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%"></th>
                                    <th width="10%">ID</th>
                                    <th width="10%">Date</th>
                                    <th width="15%">User</th>
                                    <th width="15%">Payment Method</th>
                                    <th width="15%">Reference</th>
                                    <th width="10%">Amount</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">No transactions found matching the current filters.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($transactions as $idx => $trx): ?>
                                <tr class="main-row">
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="toggle-details" data-idx="<?php echo $idx; ?>">
                                            <i class="fas fa-chevron-down"></i>
                                        </a>
                                    </td>
                                    <td><?php echo $trx['transaction_id']; ?></td>
                                    <td><?php echo FormatDateTime($trx['created_at'], 0); ?></td>
                                    <td>
                                        <div><?php echo htmlspecialchars($trx['user_name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($trx['user_email']); ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $methodIcon = 'fa-credit-card'; // Default icon
                                        switch (strtolower($trx['method_code'] ?? '')) {
                                            case 'gcash': $methodIcon = 'fa-wallet'; break;
                                            case 'maya': $methodIcon = 'fa-money-bill-transfer'; break;
                                            case 'cash': $methodIcon = 'fa-money-bill-wave'; break;
                                            case 'bank': $methodIcon = 'fa-university'; break;
                                        }
                                        ?>
                                        <i class="fas <?php echo $methodIcon; ?> payment-method-icon"></i>
                                        <?php echo htmlspecialchars($trx['method_name']); ?>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($trx['transaction_reference']); ?></div>
                                        <?php if (!empty($trx['gateway_reference'])): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-link me-1"></i><?php echo htmlspecialchars(substr($trx['gateway_reference'], 0, 12) . '...'); ?>
                                        </small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold">₱<?php echo number_format($trx['amount']); ?></td>
                                    <td>
                                        <?php
                                        $statusClass = 'secondary';
                                        if ($trx['status'] == 'completed') $statusClass = 'success';
                                        if ($trx['status'] == 'pending') $statusClass = 'warning';
                                        if ($trx['status'] == 'rejected') $statusClass = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?> status-badge">
                                            <?php echo ucfirst($trx['status']); ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <?php if ($trx['status'] == 'pending'): ?>
                                        <a href="<?php echo GetUrl('TransactionDashboard') . '?action=approve&transaction_id=' . $trx['transaction_id']; ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-check-circle me-1"></i> Approve
                                        </a>
                                        <a href="javascript:void(0)" onclick="rejectWithReason(<?php echo $trx['transaction_id']; ?>)" class="btn btn-sm btn-outline-danger mt-1">
                                            <i class="fas fa-times-circle me-1"></i> Reject
                                        </a>
                                        <?php else: ?>
                                        <a href="PaymentView?transaction_id=<?php echo $trx['transaction_id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Detail Row -->
                                <tr class="detail-row bg-light" id="detail-<?php echo $idx; ?>">
                                    <td colspan="9">
                                        <div class="p-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Payment Details</h6>
                                                    <table class="table table-sm table-bordered">
                                                        <tr>
                                                            <th width="30%">Transaction ID</th>
                                                            <td><?php echo $trx['transaction_id']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Request ID</th>
                                                            <td>
                                                                <a href="NotarizationRequestView?request_id=<?php echo $trx['request_id']; ?>">
                                                                    <?php echo $trx['request_id']; ?>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reference</th>
                                                            <td><?php echo htmlspecialchars($trx['transaction_reference']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Gateway Reference</th>
                                                            <td><?php echo !empty($trx['gateway_reference']) ? htmlspecialchars($trx['gateway_reference']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payment Method</th>
                                                            <td><?php echo htmlspecialchars($trx['method_name']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Amount</th>
                                                            <td>₱<?php echo number_format($trx['amount']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td>
                                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                                    <?php echo ucfirst($trx['status']); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <?php if (!empty($trx['payment_date'])): ?>
                                                        <tr>
                                                            <th>Payment Date</th>
                                                            <td><?php echo FormatDateTime($trx['payment_date'], 0); ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <tr>
                                                            <th>Created At</th>
                                                            <td><?php echo FormatDateTime($trx['created_at'], 0); ?></td>
                                                        </tr>
                                                        <?php if (!empty($trx['notes'])): ?>
                                                        <tr>
                                                            <th>Notes</th>
                                                            <td><?php echo htmlspecialchars($trx['notes']); ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Gateway Response</h6>
                                                    <div class="border rounded p-3 bg-white">
                                                        <?php echo formatJSONForDisplay($trx['gateway_response']); ?>
                                                    </div>
                                                    
                                                    <?php if ($trx['status'] == 'pending'): ?>
                                                    <div class="mt-3">
                                                        <h6>Actions</h6>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-success approve-btn w-50" data-id="<?php echo $trx['transaction_id']; ?>">
                                                                <i class="fas fa-check-circle me-1"></i> Approve Payment
                                                            </button>
                                                            <button type="button" class="btn btn-danger reject-btn w-50" data-id="<?php echo $trx['transaction_id']; ?>">
                                                                <i class="fas fa-times-circle me-1"></i> Reject Payment
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing <?php echo ($page - 1) * $perPage + 1; ?> to 
                            <?php echo min($page * $perPage, $totalTransactions); ?> of 
                            <?php echo $totalTransactions; ?> entries
                        </div>
                        <nav aria-label="Transactions pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo GetUrl('TransactionDashboard', [
                                        'page' => $page - 1,
                                        'status_filter' => $statusFilter,
                                        'date_range' => $dateRange,
                                        'custom_start_date' => $customStartDate,
                                        'custom_end_date' => $customEndDate
                                    ]); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                
                                if ($startPage > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="' . GetUrl('TransactionDashboard', [
                                        'page' => 1,
                                        'status_filter' => $statusFilter,
                                        'date_range' => $dateRange,
                                        'custom_start_date' => $customStartDate,
                                        'custom_end_date' => $customEndDate
                                    ]) . '">1</a></li>';
                                    
                                    if ($startPage > 2) {
                                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                    }
                                }
                                
                                for ($i = $startPage; $i <= $endPage; $i++) {
                                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '">
                                        <a class="page-link" href="' . GetUrl('TransactionDashboard', [
                                            'page' => $i,
                                            'status_filter' => $statusFilter,
                                            'date_range' => $dateRange,
                                            'custom_start_date' => $customStartDate,
                                            'custom_end_date' => $customEndDate
                                        ]) . '">' . $i . '</a>
                                    </li>';
                                }
                                
                                if ($endPage < $totalPages) {
                                    if ($endPage < $totalPages - 1) {
                                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                    }
                                    
                                    echo '<li class="page-item"><a class="page-link" href="' . GetUrl('TransactionDashboard', [
                                        'page' => $totalPages,
                                        'status_filter' => $statusFilter,
                                        'date_range' => $dateRange,
                                        'custom_start_date' => $customStartDate,
                                        'custom_end_date' => $customEndDate
                                    ]) . '">' . $totalPages . '</a></li>';
                                }
                                ?>
                                
                                <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo GetUrl('TransactionDashboard', [
                                        'page' => $page + 1,
                                        'status_filter' => $statusFilter,
                                        'date_range' => $dateRange,
                                        'custom_start_date' => $customStartDate,
                                        'custom_end_date' => $customEndDate
                                    ]); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Approve Transaction Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <input type="hidden" name="action" value="approve">
                <input type="hidden" name="transaction_id" id="approveTransactionId">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this payment transaction?</p>
                    <p class="mb-0">This will mark the payment as completed and update the notarization request payment status.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Transaction Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <input type="hidden" name="action" value="reject">
                <input type="hidden" name="transaction_id" id="rejectTransactionId">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this payment transaction?</p>
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="rejectReason" name="reject_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

<script>
loadjs.ready(["wrapper", "head"], function () {
    // Toggle transaction details
    document.querySelectorAll('.toggle-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const idx = this.getAttribute('data-idx');
            const detailRow = document.getElementById('detail-' + idx);
            
            if (detailRow.style.display === 'table-row') {
                detailRow.style.display = 'none';
                this.querySelector('i').classList.replace('fa-chevron-up', 'fa-chevron-down');
            } else {
                document.querySelectorAll('.detail-row').forEach(function(row) {
                    row.style.display = 'none';
                });
                document.querySelectorAll('.toggle-details i').forEach(function(icon) {
                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                });
                
                detailRow.style.display = 'table-row';
                this.querySelector('i').classList.replace('fa-chevron-down', 'fa-chevron-up');
            }
        });
    });
    
    // Date range selector
    document.getElementById('dateRangeSelect').addEventListener('change', function() {
        const customFields = document.getElementById('customDateFields');
        if (this.value === 'custom') {
            customFields.classList.remove('d-none');
        } else {
            customFields.classList.add('d-none');
            // Auto-submit form when non-custom option is selected
            document.getElementById('filterForm').submit();
        }
    });
    
    // Auto-submit on status change
    document.getElementById('statusSelect').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Setup approve modal
    const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
    document.querySelectorAll('.approve-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('approveTransactionId').value = this.getAttribute('data-id');
            approveModal.show();
        });
    });
    
    // Setup reject modal
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    document.querySelectorAll('.reject-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('rejectTransactionId').value = this.getAttribute('data-id');
            rejectModal.show();
        });
    });

    function rejectWithReason(transactionId) {
        const reason = prompt("Please enter a reason for rejection:", "");
        if (reason !== null) {
            window.location.href = '<?php echo GetUrl("TransactionDashboard"); ?>' + 
                '?action=reject&transaction_id=' + transactionId + 
                '&reject_reason=' + encodeURIComponent(reason);
        }
    } 

    // Chart initialization
    if (typeof Chart !== 'undefined') {
        // Set global Chart.js options
        Chart.defaults.global.defaultFontFamily = "'Inter', 'Segoe UI', 'Helvetica Neue', sans-serif";
        Chart.defaults.global.defaultFontSize = 12;
        Chart.defaults.global.tooltips.backgroundColor = 'rgba(0, 0, 0, 0.7)';
        Chart.defaults.global.tooltips.cornerRadius = 6;
        
        // Transaction Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php 
                    $labels = [];
                    $data = [];
                    $colors = [
                        'pending' => 'rgba(245, 158, 11, 0.8)',    // yellow/warning for pending
                        'completed' => 'rgba(16, 185, 129, 0.8)',  // green/success for completed
                        'rejected' => 'rgba(239, 68, 68, 0.8)'     // red/danger for rejected
                    ];
                    $bgColors = [];
                    
                    foreach ($statusStats as $stat) {
                        $labels[] = ucfirst($stat['status']);
                        $data[] = $stat['count'];
                        $bgColors[] = $colors[$stat['status']] ?? 'rgba(156, 163, 175, 0.8)';  // default gray
                    }
                    
                    echo json_encode($labels);
                ?>,
                datasets: [{
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: <?php echo json_encode($bgColors); ?>,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 65,
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const value = dataset.data[tooltipItem.index];
                            const label = data.labels[tooltipItem.index];
                            const total = dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        });
        
        // Transaction Amount Chart
        const amountCtx = document.getElementById('amountChart').getContext('2d');
        const amountChart = new Chart(amountCtx, {
            type: 'bar',
            data: {
                labels: <?php 
                    $labels = [];
                    $amounts = [];
                    
                    foreach ($statusStats as $stat) {
                        $labels[] = ucfirst($stat['status']);
                        $amounts[] = $stat['total_amount'];
                    }
                    
                    echo json_encode($labels);
                ?>,
                datasets: [{
                    label: 'Total Amount',
                    data: <?php echo json_encode($amounts); ?>,
                    backgroundColor: <?php echo json_encode($bgColors); ?>,
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        gridLines: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let value = tooltipItem.yLabel;
                            return `Amount: ₱${value.toLocaleString()}`;
                        }
                    }
                }
            }
        });
        
        // Transaction Timeline Chart
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        const timelineChart = new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($timelineData, 'month_label')); ?>,
                datasets: [
                    {
                        label: 'Total Transactions',
                        data: <?php echo json_encode(array_column($timelineData, 'transaction_count')); ?>,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        pointRadius: 4,
                        lineTension: 0.4
                    },
                    {
                        label: 'Completed',
                        data: <?php echo json_encode(array_column($timelineData, 'completed_count')); ?>,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        fill: false,
                        lineTension: 0.4
                    },
                    {
                        label: 'Pending',
                        data: <?php echo json_encode(array_column($timelineData, 'pending_count')); ?>,
                        borderColor: 'rgb(245, 158, 11)',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgb(245, 158, 11)',
                        fill: false,
                        lineTension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        },
                        gridLines: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                hover: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    }

});
</script>