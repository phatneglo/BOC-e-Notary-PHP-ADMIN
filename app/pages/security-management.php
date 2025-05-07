<?php
namespace PHPMaker2024\eNotary;


// Permission Constants
define("ALLOW_ADD", 1);
define("ALLOW_DELETE", 2);
define("ALLOW_EDIT", 4);
define("ALLOW_LIST", 8);
define("ALLOW_ADMIN", 16);
define("ALLOW_VIEW", 32);
define("ALLOW_SEARCH", 64);
define("ALLOW_IMPORT", 128);
define("ALLOW_LOOKUP", 256);
define("ALLOW_PUSH", 512);
define("ALLOW_EXPORT", 1024);

// Get current system and user level
$currentSystem = Get("system", "UAC");
if (EmptyValue($currentSystem)) {
    $sql = "SELECT system_code FROM systems ORDER BY system_code LIMIT 1";
    $currentSystem = ExecuteScalar($sql, "DB");
}
$sql = "SELECT user_level_id, name FROM user_levels ORDER BY user_level_id";
$userLevels = ExecuteRows($sql, "DB");
?>

<!-- Main content -->
<div class="container-fluid">
    <!-- System Selection and Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Selection</h3>
                </div>
                <div class="card-body">
                    <select id="systemSelect" class="form-select" onchange="loadSystemData(this.value)">
                        <!-- Will be populated via API -->
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary me-2" onclick="checkAllPermissions()">
                        <i class="fas fa-check-square me-2"></i>Check All
                    </button>
                    <button type="button" class="btn btn-danger me-2" onclick="uncheckAllPermissions()">
                        <i class="fas fa-square me-2"></i>Uncheck All
                    </button>
                    <button type="button" class="btn btn-success" onclick="savePermissions()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and User Levels -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="btn-group" role="group" id="userLevelButtons">
                        <!-- Will be populated via API -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Grid -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Permissions Management</h3>
        </div>
        <div class="card-body table-responsive">
            <table id="permissionsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th  class="align-middle">Table/Page</th>
                        <th colspan="8" class="text-center">Permissions</th>
                    </tr>
                    <tr>
                        <th><input type="text" class="form-control w-100" id="searchInput" placeholder="Search tables..." onkeyup="filterTables()">
                        </th>
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'list')">
                                <label class="form-check-label">List</label>
                            </div>
                        </th>                        
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'view')">
                                <label class="form-check-label">View</label>
                            </div>
                        </th>
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'add')">
                                <label class="form-check-label">Add</label>
                            </div>
                        </th>
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'edit')">
                                <label class="form-check-label">Edit</label>
                            </div>
                        </th>
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'delete')">
                                <label class="form-check-label">Delete</label>
                            </div>
                        </th>
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'search')">
                                <label class="form-check-label">Search</label>
                            </div>
                        </th>
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'export')">
                                <label class="form-check-label">Export</label>
                            </div>
                        </th>
                        <th class="text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" onclick="toggleColumnPermissions(this, 'lookup')">
                                <label class="form-check-label">Lookup</label>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Will be populated via API -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Permission flags for bitwise operations
const PERMISSIONS = {
    ADD: <?= ALLOW_ADD ?>,
    DELETE: <?= ALLOW_DELETE ?>,
    EDIT: <?= ALLOW_EDIT ?>,
    LIST: <?= ALLOW_LIST ?>,
    ADMIN: <?= ALLOW_ADMIN ?>,
    VIEW: <?= ALLOW_VIEW ?>,
    SEARCH: <?= ALLOW_SEARCH ?>,
    IMPORT: <?= ALLOW_IMPORT ?>,
    LOOKUP: <?= ALLOW_LOOKUP ?>,
    PUSH: <?= ALLOW_PUSH ?>,
    EXPORT: <?= ALLOW_EXPORT ?>
};

let currentSystem = '<?= $currentSystem ?>';
let currentUserLevel = '';
let permissionsData = {};

// Load initial data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadSystems();
});

// Load systems into dropdown
async function loadSystems() {
    try {
        const response = await fetch('/api/permission/systems', {             
            headers: { 
                'Content-Type': 'application/json',
                'X-Authorization': 'Bearer ' + ew.API_JWT_TOKEN 
            },
        });
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Error loading systems');
        
        const select = document.getElementById('systemSelect');
        select.innerHTML = result.data.map(sys => 
            `<option value="${sys.system_code}" ${sys.system_code === currentSystem ? 'selected' : ''}>
                ${sys.system_name} (${sys.system_code})
            </option>`
        ).join('');

        // Load user levels for initial system
        await loadUserLevels();
    } catch (error) {
        console.error('Error loading systems:', error);
        ew.alert('Error loading systems');
    }
}
// Load user levels and permissions for current system
async function loadSystemData(systemCode) {
    currentSystem = systemCode;
    await loadUserLevels();    
    await loadPermissions();
}

async function loadUserLevels() {
    try {
        const response = await fetch(`/api/permission/userlevels/${currentSystem}`, {
            headers: { 
                'Content-Type': 'application/json',
                'X-Authorization': 'Bearer ' + ew.API_JWT_TOKEN 
            }
        });
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Error loading user levels');

        const buttonContainer = document.getElementById('userLevelButtons');
        buttonContainer.innerHTML = result.data.map(level => `
            <input type="radio" class="btn-check" name="userLevel" id="level_${level.user_level_id}" 
                   value="${level.user_level_id}" autocomplete="off">
            <label class="btn btn-outline-primary" for="level_${level.user_level_id}" title="${level.description || ''}">
                ${level.name}
            </label>
        `).join('');

        // Set first level as default and load its permissions
        const firstLevel = document.querySelector('input[name="userLevel"]');
        if (firstLevel) {
            firstLevel.checked = true;
            currentUserLevel = firstLevel.value;
            await loadPermissions();
        }

        // Add user level change listener
        document.querySelectorAll('input[name="userLevel"]').forEach(radio => {
            radio.addEventListener('change', function() {
                currentUserLevel = this.value;
                loadPermissions();
            });
        });
    } catch (error) {
        console.error('Error loading user levels:', error);
        ew.alert('Error loading user levels');
    }
}


// Filter tables based on search input
function filterTables() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#permissionsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.querySelector('td:first-child').textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

// Toggle all permissions for a column
function toggleColumnPermissions(checkbox, permissionType) {
    const permValue = PERMISSIONS[permissionType.toUpperCase()];
    const checkboxes = document.querySelectorAll(`input[data-permission="${permValue}"]`);
    checkboxes.forEach(cb => {
        if (cb.closest('tr').style.display !== 'none') { // Only toggle visible rows
            cb.checked = checkbox.checked;
        }
    });
}


// Load permissions for current system
async function loadPermissions() {
    try {
        const response = await fetch(`/api/permission/${currentSystem}/${currentUserLevel}`, {
            headers: { 
                'Content-Type': 'application/json',
                'X-Authorization': 'Bearer ' + ew.API_JWT_TOKEN 
            }
        });
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Error loading permissions');
        
        permissionsData = result.data;
        updatePermissionsTable();
    } catch (error) {
        console.error('Error loading permissions:', error);
        ew.alert('Error loading permissions');
    }
}


// Update the permissions table with current data
function updatePermissionsTable() {
    const tbody = document.querySelector('#permissionsTable tbody');
    tbody.innerHTML = permissionsData.tables.map(table => `
        <tr data-table="${table.table_name}">
            <td>${table.caption}</td>
            ${[PERMISSIONS.LIST, PERMISSIONS.VIEW, PERMISSIONS.ADD, PERMISSIONS.EDIT, PERMISSIONS.DELETE, 
               PERMISSIONS.SEARCH, PERMISSIONS.EXPORT, PERMISSIONS.LOOKUP].map(value => `
                <td class="text-center">
                    <div class="form-check d-flex justify-content-center">
                        <input type="checkbox" class="form-check-input permission-check" 
                               data-permission="${value}"
                               ${hasPermission(table.permission, value) ? 'checked' : ''}>
                    </div>
                </td>
            `).join('')}
        </tr>
    `).join('');
}

// Check if permission exists using bitwise operation
function hasPermission(current, check) {
    return (current & check) === check;
}

// Calculate total permission value from checkboxes
function calculatePermission(row) {
    let permission = 0;
    row.querySelectorAll('.permission-check:checked').forEach(checkbox => {
        permission |= parseInt(checkbox.dataset.permission);
    });
    return permission;
}

// Check/uncheck all permissions
function checkAllPermissions(value = true) {
    document.querySelectorAll('.permission-check').forEach(checkbox => {
        checkbox.checked = value;
    });
}

function uncheckAllPermissions() {
    checkAllPermissions(false);
}

// Save all permissions
async function savePermissions() {
    const permissions = [];
    document.querySelectorAll('#permissionsTable tbody tr').forEach(row => {
        if (row.style.display !== 'none') { // Only save visible rows
            permissions.push({
                table_name: row.dataset.table,
                permission: calculatePermission(row),
                user_level_id: currentUserLevel
            });
        }
    });

    try {
        const response = await fetch('/api/permission', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Authorization': 'Bearer ' + ew.API_JWT_TOKEN 
            },
            body: JSON.stringify({
                system_code: currentSystem,
                permissions: permissions
            })
        });

        const result = await response.json();
        if (result.success) {
            ew.showToast('Permissions saved successfully', 'success');
            loadPermissions();
        } else {
            ew.showToast('Error saving permissions: ' + result.message);
        }
    } catch (error) {
        console.error('Error saving permissions:', error);
        ew.showToast('Error saving permissions');
    }
}
</script>