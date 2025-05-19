<?php
namespace PHPMaker2024\eNotary;

// Security check
if (!$Security->isLoggedIn()) {
    return;
}

// Get status filter parameter
$statusFilter = Get("status_filter", "pending"); // Default to pending requests

// Get date range parameters
$dateRange = Get("date_range", "this_month"); // Default to this month
$startDate = "";
$endDate = "";
$customStartDate = Get("custom_start_date", "");
$customEndDate = Get("custom_end_date", "");


$historyRequestId = Get("history_request_id", 0);
if ($historyRequestId > 0) {
    // This is a request for history data only
    $history = ExecuteRows("
        SELECT 
            srh.history_id,
            srh.request_id,
            srh.status,
            srh.comment,
            srh.created_by,
            srh.created_at,
            u.first_name || ' ' || u.last_name as staff_name
        FROM support_request_history srh
        LEFT JOIN users u ON srh.created_by = u.user_id
        WHERE srh.request_id = " . QuotedValue($historyRequestId, DataType::NUMBER) . "
        ORDER BY srh.created_at DESC", 
    "DB");
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'history' => $history]);
    exit(); // Important: stop execution here
}




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
    $dateCondition = " AND sr.created_at BETWEEN " . QuotedValue($startDate, DataType::DATE) . 
                     " AND " . QuotedValue($endDate, DataType::DATE);
}

// Build status filter condition
$statusCondition = "";
if ($statusFilter != "all") {
    $statusCondition = " AND sr.status = " . QuotedValue($statusFilter, DataType::STRING);
}

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

// Process support request actions (using GET)
$action = Get("action");
$requestId = Get("request_id");

if ($action && $requestId) {
    try {
        // Begin transaction
        Execute("BEGIN", "DB");

        
        if ($action == "assign") {
            $staffId = Get("staff_id");
            
            if ($staffId) {
                // Update request assignment
                Execute("
                    UPDATE support_requests
                    SET 
                        assigned_to = " . QuotedValue($staffId, DataType::NUMBER) . ",
                        updated_at = CURRENT_TIMESTAMP
                    WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER), 
                "DB");
                
                // Add history record
                Execute("
                    INSERT INTO support_request_history (
                        request_id, 
                        status, 
                        comment, 
                        created_by, 
                        created_at
                    ) VALUES (
                        " . QuotedValue($requestId, DataType::NUMBER) . ",
                        'assigned',
                        " . QuotedValue('Assigned to staff ID ' . $staffId, DataType::STRING) . ",
                        " . QuotedValue(CurrentUserID(), DataType::NUMBER) . ",
                        CURRENT_TIMESTAMP
                    )", 
                "DB");
                
                // Add success message
                $msg = "success";
                $text = "Request #$requestId assigned successfully.";
                AddMessage($msg, $text);
            }
        } else if ($action == "status") {
            $newStatus = Get("status");
            $comment = Get("comment", "Status updated by admin");
            
            if ($newStatus) {
                // Update request status
                Execute("
                    UPDATE support_requests
                    SET 
                        status = " . QuotedValue($newStatus, DataType::STRING) . ",
                        updated_at = CURRENT_TIMESTAMP" . 
                        ($newStatus == "resolved" ? ", resolved_at = CURRENT_TIMESTAMP" : "") . "
                    WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER), 
                "DB");
                
                // Add history record
                Execute("
                    INSERT INTO support_request_history (
                        request_id, 
                        status, 
                        comment, 
                        created_by, 
                        created_at
                    ) VALUES (
                        " . QuotedValue($requestId, DataType::NUMBER) . ",
                        " . QuotedValue($newStatus, DataType::STRING) . ",
                        " . QuotedValue($comment, DataType::STRING) . ",
                        " . QuotedValue(CurrentUserID(), DataType::NUMBER) . ",
                        CURRENT_TIMESTAMP
                    )", 
                "DB");
                
                // Add success message
                $msg = "success";
                $text = "Request #$requestId status updated to '$newStatus'.";
                AddMessage($msg, $text);
            }
        } else if ($action == "respond") {
            $response = Get("response");
            
            if ($response) {
                // Update request with response
                Execute("
                    UPDATE support_requests
                    SET 
                        response = " . QuotedValue($response, DataType::STRING) . ",
                        updated_at = CURRENT_TIMESTAMP
                    WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER), 
                "DB");
                
                // Add history record
                Execute("
                    INSERT INTO support_request_history (
                        request_id, 
                        status, 
                        comment, 
                        created_by, 
                        created_at
                    ) VALUES (
                        " . QuotedValue($requestId, DataType::NUMBER) . ",
                        'responded',
                        " . QuotedValue('Response added by staff', DataType::STRING) . ",
                        " . QuotedValue(CurrentUserID(), DataType::NUMBER) . ",
                        CURRENT_TIMESTAMP
                    )", 
                "DB");
                
                // Add success message
                $msg = "success";
                $text = "Response added to request #$requestId.";
                AddMessage($msg, $text);
            }
        }
        
        // Commit transaction
        Execute("COMMIT", "DB");
        
        // Redirect to refresh the page
        header("Location: " . GetUrl("SupportPanel"));
        exit();
    } catch (Exception $e) {
        // Rollback on error
        Execute("ROLLBACK", "DB");
        
        // Add error message
        $msg = "danger";
        $text = "Error processing request: " . $e->getMessage();
        AddMessage($msg, $text);
    }
}

// Get support requests with pagination
$page = Get("page", 1);
$perPage = 20;
$offset = ($page - 1) * $perPage;

$supportRequests = ExecuteRows("
    SELECT 
        sr.request_id,
        sr.user_id,
        sr.name,
        sr.email,
        sr.subject,
        sr.message,
        sr.request_type,
        sr.reference_number,
        sr.status,
        sr.created_at,
        sr.updated_at,
        sr.assigned_to,
        sr.resolved_at,
        sr.response,
        a.first_name || ' ' || a.last_name as assigned_to_name
    FROM support_requests sr
    LEFT JOIN users a ON sr.assigned_to = a.user_id
    WHERE 1=1
    $statusCondition
    $dateCondition
    ORDER BY sr.created_at DESC
    LIMIT $perPage OFFSET $offset", 
"DB");

// Get total count for pagination
$totalRequests = ExecuteScalar("
    SELECT COUNT(*) 
    FROM support_requests sr
    WHERE 1=1
    $statusCondition
    $dateCondition", 
"DB");

$totalPages = ceil($totalRequests / $perPage);

// Get summary stats
$pendingCount = ExecuteScalar("
    SELECT COUNT(*) 
    FROM support_requests sr
    WHERE sr.status = 'pending'
    $dateCondition", 
"DB");

$resolvedCount = ExecuteScalar("
    SELECT COUNT(*) 
    FROM support_requests sr
    WHERE sr.status = 'resolved'
    $dateCondition", 
"DB");

$unassignedCount = ExecuteScalar("
    SELECT COUNT(*) 
    FROM support_requests sr
    WHERE sr.assigned_to IS NULL
    $dateCondition", 
"DB");

// Get request types distribution
$requestTypeStats = ExecuteRows("
    SELECT 
        request_type, 
        COUNT(*) as count
    FROM support_requests sr
    WHERE 1=1
    $dateCondition
    GROUP BY request_type
    ORDER BY count DESC", 
"DB");

// Get staff list for assignment
$staffList = ExecuteRows("
    SELECT 
        u.user_id,
        u.first_name || ' ' || u.last_name as name
    FROM users u
    JOIN user_level_permissions ulp ON CAST(u.user_level_id AS INTEGER) = ulp.user_level_id
    WHERE ulp.table_name = 'support_requests' 
    AND ulp.permission & " . (1 << 0) . " > 0  -- Using bitwise for Edit permission
    ORDER BY name", 
"DB");

// Get request history data (for AJAX loading later)
$requestHistory = [];
$historyRequestId = Get("history_request_id", 0);
if ($historyRequestId > 0) {
    $requestHistory = ExecuteRows("
        SELECT 
            srh.history_id,
            srh.request_id,
            srh.status,
            srh.comment,
            srh.created_by,
            srh.created_at,
            u.first_name || ' ' || u.last_name as staff_name
        FROM support_request_history srh
        LEFT JOIN users u ON srh.created_by = u.user_id
        WHERE srh.request_id = " . QuotedValue($historyRequestId, DataType::NUMBER) . "
        ORDER BY srh.created_at DESC", 
    "DB");
}

?>

<style>
.status-badge {
    min-width: 85px;
    text-align: center;
}
.request-type-icon {
    font-size: 1.5em;
    margin-right: 8px;
}
.detail-row {
    display: none;
}
.action-buttons {
    min-width: 180px;
}
.history-timeline {
    position: relative;
    padding-left: 30px;
}
.history-timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}
.history-item {
    position: relative;
    margin-bottom: 15px;
}
.history-item::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #4e73df;
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- Title Section -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="mb-1">Support Management Dashboard</h4>
            <p class="text-muted mb-0">View, assign, and manage customer support requests</p>
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
                                <label class="form-label small">Request Status</label>
                                <select class="form-select form-select-sm" name="status_filter" id="statusSelect">
                                    <option value="all" <?php echo ($statusFilter == "all") ? "selected" : ""; ?>>All Statuses</option>
                                    <option value="pending" <?php echo ($statusFilter == "pending") ? "selected" : ""; ?>>Pending</option>
                                    <option value="in-progress" <?php echo ($statusFilter == "in-progress") ? "selected" : ""; ?>>In Progress</option>
                                    <option value="resolved" <?php echo ($statusFilter == "resolved") ? "selected" : ""; ?>>Resolved</option>
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
                        <div class="p-3 me-3 bg-warning-subtle rounded">
                            <i class="fas fa-ticket-alt text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Pending Requests</h6>
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
                        <div class="p-3 me-3 bg-danger-subtle rounded">
                            <i class="fas fa-user-slash text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0">Unassigned Requests</h6>
                            <h2 class="mb-0"><?php echo number_format($unassignedCount); ?></h2>
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
                            <h6 class="text-muted mb-0">Resolved Requests</h6>
                            <h2 class="mb-0"><?php echo number_format($resolvedCount); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Request Types Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Request Type Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="requestTypeChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Support Requests Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Support Requests</h5>
                    <span class="badge bg-primary"><?php echo number_format($totalRequests); ?> requests</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%"></th>
                                    <th width="8%">ID</th>
                                    <th width="12%">Date</th>
                                    <th width="15%">User</th>
                                    <th width="15%">Type</th>
                                    <th width="20%">Subject</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($supportRequests)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">No support requests found matching the current filters.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($supportRequests as $idx => $req): ?>
                                <tr class="main-row">
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="toggle-details" data-idx="<?php echo $idx; ?>">
                                            <i class="fas fa-chevron-down"></i>
                                        </a>
                                    </td>
                                    <td><?php echo $req['request_id']; ?></td>
                                    <td><?php echo FormatDateTime($req['created_at'], 0); ?></td>
                                    <td>
                                        <div><?php echo htmlspecialchars($req['name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($req['email']); ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $typeIcon = 'fa-question-circle'; // Default icon
                                        switch (strtolower($req['request_type'] ?? '')) {
                                            case 'technical': $typeIcon = 'fa-laptop-code'; break;
                                            case 'billing': $typeIcon = 'fa-file-invoice-dollar'; break;
                                            case 'account': $typeIcon = 'fa-user-cog'; break;
                                            case 'document': $typeIcon = 'fa-file-alt'; break;
                                        }
                                        ?>
                                        <i class="fas <?php echo $typeIcon; ?> request-type-icon"></i>
                                        <?php echo htmlspecialchars(ucfirst($req['request_type'])); ?>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($req['subject']); ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'secondary';
                                        if ($req['status'] == 'resolved') $statusClass = 'success';
                                        if ($req['status'] == 'pending') $statusClass = 'warning';
                                        if ($req['status'] == 'in-progress') $statusClass = 'info';
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?> status-badge">
                                            <?php echo ucfirst($req['status']); ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="javascript:void(0)" onclick="viewHistory(<?php echo $req['request_id']; ?>)" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-history me-1"></i> History
                                        </a>
                                       <a href="SupportRequestsView/<?php echo $req['request_id']; ?>" class="btn btn-sm btn-outline-secondary mt-1">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>

                                    </td>
                                </tr>
                                <!-- Detail Row -->
                                <tr class="detail-row bg-light" id="detail-<?php echo $idx; ?>">
                                    <td colspan="8">
                                        <div class="p-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Request Details</h6>
                                                    <table class="table table-sm table-bordered">
                                                        <tr>
                                                            <th width="30%">Request ID</th>
                                                            <td><?php echo $req['request_id']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reference Number</th>
                                                            <td><?php echo htmlspecialchars($req['reference_number']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>User</th>
                                                            <td>
                                                                <?php echo htmlspecialchars($req['name']); ?>
                                                                <?php if ($req['user_id']): ?>
                                                                <a href="UserView?user_id=<?php echo $req['user_id']; ?>" class="ms-2">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Email</th>
                                                            <td><?php echo htmlspecialchars($req['email']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Subject</th>
                                                            <td><?php echo htmlspecialchars($req['subject']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Type</th>
                                                            <td><?php echo htmlspecialchars(ucfirst($req['request_type'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td>
                                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                                    <?php echo ucfirst($req['status']); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Assigned To</th>
                                                            <td><?php echo !empty($req['assigned_to_name']) ? htmlspecialchars($req['assigned_to_name']) : 'Unassigned'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Created At</th>
                                                            <td><?php echo FormatDateTime($req['created_at'], 0); ?></td>
                                                        </tr>
                                                        <?php if (!empty($req['resolved_at'])): ?>
                                                        <tr>
                                                            <th>Resolved At</th>
                                                            <td><?php echo FormatDateTime($req['resolved_at'], 0); ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Message</h6>
                                                    <div class="border rounded p-3 bg-white mb-3" style="max-height: 200px; overflow-y: auto;">
                                                        <?php echo nl2br(htmlspecialchars($req['message'])); ?>
                                                    </div>
                                                    
                                                    <?php if (!empty($req['response'])): ?>
                                                    <h6>Response</h6>
                                                    <div class="border rounded p-3 bg-white mb-3" style="max-height: 150px; overflow-y: auto;">
                                                        <?php echo nl2br(htmlspecialchars($req['response'])); ?>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="mt-3">
                                                        <h6>Actions</h6>
                                                        <div class="d-flex gap-2 mb-2">
                                                            <div class="dropdown">
                                                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="assignDropdown<?php echo $req['request_id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-user-tag me-1"></i> Assign
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="assignDropdown<?php echo $req['request_id']; ?>">
                                                                    <?php foreach ($staffList as $staff): ?>
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo GetUrl('SupportPanel') . '?action=assign&request_id=' . $req['request_id'] . '&staff_id=' . $staff['user_id']; ?>">
                                                                            <?php echo htmlspecialchars($staff['name']); ?>
                                                                        </a>
                                                                    </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            </div>
                                                            
                                                            <div class="dropdown">
                                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown<?php echo $req['request_id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-sync-alt me-1"></i> Update Status
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="statusDropdown<?php echo $req['request_id']; ?>">
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus(<?php echo $req['request_id']; ?>, 'pending')">
                                                                            Pending
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus(<?php echo $req['request_id']; ?>, 'in-progress')">
                                                                            In Progress
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus(<?php echo $req['request_id']; ?>, 'resolved')">
                                                                            Resolved
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        
                                                        <button type="button" class="btn btn-success" onclick="addResponse(<?php echo $req['request_id']; ?>)">
                                                            <i class="fas fa-reply me-1"></i> Add Response
                                                        </button>
                                                    </div>
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
                            <?php echo min($page * $perPage, $totalRequests); ?> of 
                            <?php echo $totalRequests; ?> entries
                        </div>
                        <nav aria-label="Support requests pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo GetUrl('SupportPanel', [
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
                                    echo '<li class="page-item"><a class="page-link" href="' . GetUrl('SupportPanel', [
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
                                        <a class="page-link" href="' . GetUrl('SupportPanel', [
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
                                    
                                    echo '<li class="page-item"><a class="page-link" href="' . GetUrl('SupportPanel', [
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
                                    <a class="page-link" href="<?php echo GetUrl('SupportPanel', [
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

<!-- Request History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Request History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="historyContent" class="history-timeline">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading history...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
loadjs.ready(["wrapper", "head"], function () {
    // Toggle request details
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
    
    // Initialize Request Type Chart
    const ctxRequestType = document.getElementById('requestTypeChart').getContext('2d');
    const requestTypeChart = new Chart(ctxRequestType, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($requestTypeStats, 'request_type')); ?>,
            datasets: [{
                label: 'Number of Requests',
                data: <?php echo json_encode(array_column($requestTypeStats, 'count')); ?>,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',  // blue
                    'rgba(16, 185, 129, 0.8)',  // green
                    'rgba(245, 158, 11, 0.8)',  // yellow
                    'rgba(239, 68, 68, 0.8)',   // red
                    'rgba(139, 92, 246, 0.8)'   // purple
                ],
                borderWidth: 0,
                maxBarThickness: 50
            }]
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
            }
        }
    });
});

// Update Status Function
function updateStatus(requestId, status) {
    let comment = "";
    if (status === "resolved") {
        comment = prompt("Please enter resolution details:", "Issue resolved");
        if (comment === null) return; // User cancelled
    } else {
        comment = prompt("Please enter a comment for this status update:", "Status updated to " + status);
        if (comment === null) return; // User cancelled
    }
    
    window.location.href = '<?php echo GetUrl("SupportPanel"); ?>' +
        '?action=status' +
        '&request_id=' + requestId +
        '&status=' + encodeURIComponent(status) +
        '&comment=' + encodeURIComponent(comment);
}

// Add Response Function
function addResponse(requestId) {
    const response = prompt("Please enter your response to this request:", "");
    if (response === null || response === "") return; // User cancelled or empty
    
    window.location.href = '<?php echo GetUrl("SupportPanel"); ?>' +
        '?action=respond' +
        '&request_id=' + requestId +
        '&response=' + encodeURIComponent(response);
}

// View History Function
function viewHistory(requestId) {
    const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
    const historyContent = document.getElementById('historyContent');
    
    // Show loading indicator
    historyContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading history...</p></div>';
    
    // Ajax request to load history data - using the same page with history_request_id parameter
    $.ajax({
        url: '<?php echo GetUrl("SupportPanel"); ?>',
        type: 'GET',
        data: { history_request_id: requestId },
        dataType: 'json',
        success: function(response) {
            if (response && response.success) {
                const history = response.history;
                if (history.length === 0) {
                    historyContent.innerHTML = '<div class="alert alert-info">No history records found for this request.</div>';
                } else {
                    let html = '';
                    history.forEach(function(item) {
                        const date = new Date(item.created_at);
                        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                        
                        let statusClass = 'secondary';
                        if (item.status === 'resolved') statusClass = 'success';
                        if (item.status === 'pending') statusClass = 'warning';
                        if (item.status === 'in-progress') statusClass = 'info';
                        if (item.status === 'assigned') statusClass = 'primary';
                        if (item.status === 'responded') statusClass = 'primary';
                        
                        html += `
                        <div class="history-item">
                            <div class="mb-1">
                                <span class="badge bg-${statusClass}">${item.status}</span>
                                <small class="text-muted ms-2">${formattedDate}</small>
                            </div>
                            <p class="mb-1">${item.comment}</p>
                            <small class="text-muted">By: ${item.staff_name || 'System'}</small>
                        </div>`;
                    });
                    
                    historyContent.innerHTML = html;
                }
            } else {
                historyContent.innerHTML = '<div class="alert alert-danger">Error loading history data</div>';
            }
        },
        error: function(xhr) {
            historyContent.innerHTML = '<div class="alert alert-danger">Error loading history data. Please try again.</div>';
        }
    });
    
    historyModal.show();
}


</script>