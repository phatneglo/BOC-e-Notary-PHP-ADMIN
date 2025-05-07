<?php   // {SYSTEM}/app/pages/user-access.php ?>
<!-- Main content -->
<div class="container-fluid">
    <div class="row">
        <!-- User Selection - Left Column -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">User Selection</h3>
                    <button type="button" class="btn btn-primary btn-sm" data-ew-action="modal" data-url="UsersAdd" data-caption="Add New User">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="input-group mb-3">
                        <input type="text" id="userSearch" class="form-control" placeholder="Search users...">
                        <button class="btn btn-outline-secondary" type="button" onclick="refreshUsers()">
                            <i class="fas fa-sync"></i>
                        </button>
                        <button id="editUserBtn" class="btn btn-outline-primary" type="button" disabled 
                                data-ew-action="modal" data-url="UsersEdit" data-caption="Edit User">
                            <i class="fas fa-user-edit"></i>
                        </button>
                    </div>
                    <select id="userSelect" class="form-select flex-grow-1" size="15" onchange="loadUserAccess(this.value)">
                        <!-- Will be populated via API -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Access Matrix - Right Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">System Access Matrix</h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger me-2" onclick="removeAllAccess()">
                            <i class="fas fa-ban me-2"></i>Remove All Access
                        </button>
                        <button type="button" class="btn btn-success" onclick="saveUserAccess()">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="accessMatrix" class="row g-3">
                        <!-- Will be populated via API -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Make cards full height on mobile */
@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
    #userSelect {
        height: 200px !important;
    }
}

/* System cards styling */
.system-card {
    margin-bottom: 1rem;
}

.system-card .btn-group {
    flex-wrap: wrap;
}

.system-card .btn-group label {
    flex: 1;
    min-width: 120px;
    margin: 2px;
}

/* Selected user highlight */
.selected-user {
    background-color: #e8f4ff;
}
</style>

<script>
let currentUserId = '';
let systemsData = {};

// Load initial data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    // Set initial height to match viewport
    adjustHeight();
    // Listen for window resize
    window.addEventListener('resize', adjustHeight);
    // Handle modal events
    ew.on("load.ew.modal", function(e) {
        if (e.detail?.data?.btn === "AddUserLevel") {
            // Set the system_id in the form
            const systemId = e.detail.data.systemId;
            const form = e.detail.modal.querySelector("form");
            if (form) {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "x_system_id";
                input.value = systemId;
                form.appendChild(input);
            }
        }
    });

    // Handle successful form submissions
    ew.on("success", function(e) {
        const data = e.detail?.data;
        if (!data) return;

        // Check if this is a modal form submission
        if (data.action === "modal") {
            const formName = data.form;
            
            // Handle different form types
            if (formName?.includes("usersadd") || formName?.includes("usersedit")) {
                loadUsers(); // Reload users list
            } else if (formName?.includes("userlevelsadd")) {
                loadUserAccess(currentUserId); // Reload current user's access matrix
            }

            // Show success message
            ew.showToast(ew.language.phrase("RecordUpdated")); // Use built-in phrase

            // Close the modal - use proper modal reference
            if (data.modal) {
                ew.modalDialog.close(data.modal);
            }
        }
    });
});

// Parse URL to get user ID
function getUserIdFromUrl() {
    const pathParts = window.location.pathname.split('/');
    const lastPart = pathParts[pathParts.length - 1];
    return !isNaN(lastPart) ? lastPart : null;
}

// Select user in dropdown and load their access
function selectUser(userId) {
    const userSelect = document.getElementById('userSelect');
    if (userSelect) {
        userSelect.value = userId;
        if (userSelect.value === userId) { // Verify selection was successful
            loadUserAccess(userId);
        }
    }
}


// Adjust heights for full-height layout
function adjustHeight() {
    const windowHeight = window.innerHeight;
    const topOffset = document.querySelector('.container-fluid').getBoundingClientRect().top;
    const availableHeight = windowHeight - topOffset - 20; // 20px padding
    
    // Set card heights
    document.querySelectorAll('.card.h-100').forEach(card => {
        card.style.height = `${availableHeight}px`;
    });
}

// Load users into select
async function loadUsers() {
    try {
        const response = await fetch('/api/access/users', {
            headers: { 
                'Content-Type': 'application/json',
                'X-Authorization': 'Bearer ' + ew.API_JWT_TOKEN 
            }
        });
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Error loading users');
        
        const select = document.getElementById('userSelect');
        select.innerHTML = result.data.map(user => 
            `<option value="${user.user_id}">
                ${user.username} - ${user.first_name} ${user.last_name}
            </option>`
        ).join('');

        // Check if we need to auto-select a user
        const urlUserId = getUserIdFromUrl();
        if (urlUserId) {
            selectUser(urlUserId);
        }        
    } catch (error) {
        console.error('Error loading users:', error);
        ew.alert('Error loading users');
    }
}

// Update the access matrix with current data
function updateAccessMatrix() {
    const matrix = document.getElementById('accessMatrix');
    let html = '';
    
    systemsData.systems.forEach(system => {
        const levels = systemsData.user_levels[system.system_code].levels;
        const currentAssignment = systemsData.assignments.find(a => a.system_id === system.system_id);
        
        html += `
            <div class="col-12 system-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">${system.system_name} (${system.system_code})</h5>
                    </div>
                    <div class="card-body">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" 
                                   name="level_${system.system_id}" 
                                   id="no_access_${system.system_id}" 
                                   value="" 
                                   data-system-id="${system.system_id}"
                                   ${!currentAssignment ? 'checked' : ''}>
                            <label class="btn btn-outline-secondary" for="no_access_${system.system_id}">
                                No Access
                            </label>
                            ${levels.map(level => `
                                <input type="radio" class="btn-check" 
                                       name="level_${system.system_id}" 
                                       id="level_${system.system_id}_${level.user_level_id}" 
                                       value="${level.user_level_id}" 
                                       data-system-id="${system.system_id}"
                                       ${currentAssignment && currentAssignment.user_level_id === level.user_level_id ? 'checked' : ''}>
                                <label class="btn btn-outline-primary" for="level_${system.system_id}_${level.user_level_id}"
                                       title="${level.description || ''}">
                                    ${level.name}
                                </label>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    matrix.innerHTML = html;
}

// Remove all access for current user
function removeAllAccess() {
    if (!currentUserId) {
        ew.alert('Please select a user first');
        return;
    }

    // Set all radio buttons to "No Access"
    systemsData.systems.forEach(system => {
        const noAccessRadio = document.getElementById(`no_access_${system.system_id}`);
        if (noAccessRadio) {
            noAccessRadio.checked = true;
        }
    });
}
document.getElementById('userSearch').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const options = document.getElementById('userSelect').options;
    
    for (let option of options) {
        const text = option.text.toLowerCase();
        option.style.display = text.includes(searchTerm) ? '' : 'none';
    }
});

// Load user access matrix
async function loadUserAccess(userId) {
    if (!userId) return;
    currentUserId = userId;
    
    // Enable edit button and set the correct URL
    const editBtn = document.getElementById('editUserBtn');
    editBtn.disabled = false;
    editBtn.dataset.url = `UsersEdit/${userId}`;

    try {
        const response = await fetch(`/api/access/matrix/${userId}`, {
            headers: { 
                'Content-Type': 'application/json',
                'X-Authorization': 'Bearer ' + ew.API_JWT_TOKEN 
            }
        });
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Error loading access matrix');

        systemsData = result.data;
        updateAccessMatrix();
    } catch (error) {
        console.error('Error loading access matrix:', error);
        ew.alert('Error loading access matrix');
    }
}

// Update the access matrix with current data
function updateAccessMatrix() {
    const matrix = document.getElementById('accessMatrix');
    let html = '';
    
    systemsData.systems.forEach(system => {
        const levels = systemsData.user_levels[system.system_code].levels;
        const currentAssignment = systemsData.assignments.find(a => a.system_id === system.system_id);
        
        html += `
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">[${system.system_code}] ${system.description}</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" 
                               name="level_${system.system_id}" 
                               id="no_access_${system.system_id}" 
                               value="" 
                               data-system-id="${system.system_id}"
                               ${!currentAssignment ? 'checked' : ''}>
                        <label class="btn btn-outline-secondary" for="no_access_${system.system_id}">
                            No Access
                        </label>
                        ${levels.map(level => `
                            <input type="radio" class="btn-check" 
                                   name="level_${system.system_id}" 
                                   id="level_${system.system_id}_${level.user_level_id}" 
                                   value="${level.user_level_id}" 
                                   data-system-id="${system.system_id}"
                                   ${currentAssignment && currentAssignment.user_level_id === level.user_level_id ? 'checked' : ''}>
                            <label class="btn btn-outline-primary" for="level_${system.system_id}_${level.user_level_id}"
                                   title="${level.description || ''}">
                                ${level.name}
                            </label>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
    });
    
    matrix.innerHTML = html;
}

// Save user access
async function saveUserAccess() {
    if (!currentUserId) {
        ew.alert('Please select a user first');
        return;
    }
    
    const assignments = [];
    systemsData.systems.forEach(system => {
        const selectedLevel = document.querySelector(`input[name="level_${system.system_id}"]:checked`);
        if (selectedLevel && selectedLevel.value) {
            assignments.push({
                system_id: selectedLevel.dataset.systemId,
                user_level_id: selectedLevel.value
            });
        }
    });
    
    try {
        const response = await fetch('/api/access', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Authorization': 'Bearer ' + ew.API_JWT_TOKEN 
            },
            body: JSON.stringify({
                user_id: currentUserId,
                assignments: assignments
            })
        });

        const result = await response.json();
        if (result.success) {
            ew.alert('Access levels saved successfully');
            loadUserAccess(currentUserId);
        } else {
            ew.alert('Error saving access levels: ' + result.message);
        }
    } catch (error) {
        console.error('Error saving access levels:', error);
        ew.alert('Error saving access levels');
    }
}

function refreshUsers() {
    loadUsers();
}

</script>
