<?php
$page_title = "Học Sinh - Hệ Thống Điểm Danh";
require_once 'includes/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-3">
                <i class="bi bi-people"></i> Quản Lý Học Sinh
            </h1>
            <p class="text-muted">Quản lý hồ sơ và thông tin học sinh</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-person-plus"></i> Thêm Học Sinh Mới
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm học sinh theo tên, email hoặc số điện thoại...">
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="text-muted">
                Tổng Số Học Sinh: <span id="totalStudentsCount" class="fw-bold">0</span>
            </span>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-list"></i> Danh Sách Học Sinh
            </h5>
        </div>
        <div class="card-body">
            <div id="studentsLoading" class="text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="mt-2 text-muted">Đang tải danh sách học sinh...</p>
            </div>
            
            <div id="studentsTable" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số Điện Thoại</th>
                                <th>Ngày Thêm</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="noStudentsFound" style="display: none;" class="text-center py-4">
                <i class="bi bi-people fs-1 text-muted"></i>
                <p class="text-muted mt-2">Không tìm thấy học sinh nào. <a href="#" data-bs-toggle="modal" data-bs-target="#addStudentModal">Thêm học sinh đầu tiên</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus"></i> Thêm Học Sinh Mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStudentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="studentName" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="studentEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="studentEmail" name="email">
                        <div class="invalid-feedback"></div>
                        <div class="form-text" id="emailHelp"></div>
                    </div>
                    <div class="mb-3">
                        <label for="studentPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="studentPhone" name="phone">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveStudentBtn">
                        <i class="bi bi-check-lg"></i> Save Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil"></i> Edit Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStudentForm">
                <input type="hidden" id="editStudentId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editStudentName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editStudentName" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editStudentEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editStudentEmail" name="email">
                        <div class="invalid-feedback"></div>
                        <div class="form-text" id="editEmailHelp"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editStudentPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="editStudentPhone" name="phone">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateStudentBtn">
                        <i class="bi bi-check-lg"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let studentsData = [];
let emailCheckTimeout;

$(document).ready(function() {
    loadStudents();
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterStudents(searchTerm);
    });
    
    // Real-time email validation for add form
    $('#studentEmail').on('input', function() {
        const email = $(this).val().trim();
        if (email && isValidEmailFormat(email)) {
            checkEmailAvailability(email, null, '#studentEmail', '#emailHelp');
        } else {
            clearEmailValidation('#studentEmail', '#emailHelp');
        }
    });
    
    // Real-time email validation for edit form
    $('#editStudentEmail').on('input', function() {
        const email = $(this).val().trim();
        const studentId = $('#editStudentId').val();
        if (email && isValidEmailFormat(email)) {
            checkEmailAvailability(email, studentId, '#editStudentEmail', '#editEmailHelp');
        } else {
            clearEmailValidation('#editStudentEmail', '#editEmailHelp');
        }
    });
    
    // Add student form submission
    $('#addStudentForm').on('submit', function(e) {
        e.preventDefault();
        addStudent();
    });
    
    // Edit student form submission
    $('#editStudentForm').on('submit', function(e) {
        e.preventDefault();
        updateStudent();
    });
    
    // Reset validation when modals are closed
    $('#addStudentModal').on('hidden.bs.modal', function() {
        $('#addStudentForm')[0].reset();
        clearEmailValidation('#studentEmail', '#emailHelp');
    });
    
    $('#editStudentModal').on('hidden.bs.modal', function() {
        $('#editStudentForm')[0].reset();
        clearEmailValidation('#editStudentEmail', '#editEmailHelp');
    });
});

function loadStudents() {
    $('#studentsLoading').show();
    $('#studentsTable').hide();
    $('#noStudentsFound').hide();
    
    $.ajax({
        url: 'api/get_students.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#studentsLoading').hide();
            
            if (response.status === 'success') {
                studentsData = response.data.students;
                displayStudents(studentsData);
                $('#totalStudentsCount').text(response.data.total_count);
            } else {
                showAlert('Error loading students: ' + response.message, 'danger');
                $('#noStudentsFound').show();
            }
        },
        error: function() {
            $('#studentsLoading').hide();
            $('#noStudentsFound').show();
            showAlert('Error connecting to server', 'danger');
        }
    });
}

function displayStudents(students) {
    if (students.length === 0) {
        $('#studentsTable').hide();
        $('#noStudentsFound').show();
        return;
    }
    
    let tableRows = '';
    students.forEach(function(student) {
        const addedDate = new Date(student.created_at).toLocaleDateString();
        tableRows += `
            <tr>
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.email || '-'}</td>
                <td>${student.phone || '-'}</td>
                <td>${addedDate}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="editStudent(${student.id})" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteStudent(${student.id}, '${student.name}')" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                    <a href="reports.php?student_id=${student.id}" class="btn btn-sm btn-outline-info" title="View Reports">
                        <i class="bi bi-graph-up"></i>
                    </a>
                </td>
            </tr>
        `;
    });
    
    $('#studentsTableBody').html(tableRows);
    $('#studentsTable').show();
    $('#noStudentsFound').hide();
}

function isValidEmailFormat(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function checkEmailAvailability(email, excludeId, inputSelector, helpSelector) {
    // Clear previous timeout
    if (emailCheckTimeout) {
        clearTimeout(emailCheckTimeout);
    }
    
    // Show checking status
    $(helpSelector).removeClass('text-danger text-success').addClass('text-muted').text('Checking email availability...');
    $(inputSelector).removeClass('is-invalid is-valid');
    
    // Debounce the API call
    emailCheckTimeout = setTimeout(function() {
        $.ajax({
            url: 'api/check_email.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ 
                email: email,
                exclude_id: excludeId 
            }),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $(inputSelector).removeClass('is-invalid').addClass('is-valid');
                    $(helpSelector).removeClass('text-danger text-muted').addClass('text-success').text('✅ Email is available');
                } else {
                    $(inputSelector).removeClass('is-valid').addClass('is-invalid');
                    $(inputSelector).siblings('.invalid-feedback').text('This email is already registered');
                    $(helpSelector).removeClass('text-success text-muted').addClass('text-danger').text('❌ Email already exists');
                }
            },
            error: function() {
                $(inputSelector).removeClass('is-valid is-invalid');
                $(helpSelector).removeClass('text-success text-danger').addClass('text-muted').text('Unable to check email availability');
            }
        });
    }, 500); // 500ms delay
}

function clearEmailValidation(inputSelector, helpSelector) {
    if (emailCheckTimeout) {
        clearTimeout(emailCheckTimeout);
    }
    $(inputSelector).removeClass('is-valid is-invalid');
    $(inputSelector).siblings('.invalid-feedback').text('');
    $(helpSelector).text('');
}

function filterStudents(searchTerm) {
    if (searchTerm === '') {
        displayStudents(studentsData);
        return;
    }
    
    const filteredStudents = studentsData.filter(student => 
        student.name.toLowerCase().includes(searchTerm) ||
        (student.email && student.email.toLowerCase().includes(searchTerm)) ||
        (student.phone && student.phone.includes(searchTerm))
    );
    
    displayStudents(filteredStudents);
}

function addStudent() {
    const formData = new FormData(document.getElementById('addStudentForm'));
    const studentData = Object.fromEntries(formData);
    
    // Check if email field has validation errors
    const emailInput = $('#studentEmail');
    if (emailInput.hasClass('is-invalid')) {
        showAlert('Please fix the email validation errors before submitting', 'warning');
        emailInput.focus();
        return;
    }
    
    showLoading($('#saveStudentBtn'));
    
    $.ajax({
        url: 'api/add_student.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(studentData),
        dataType: 'json',
        success: function(response) {
            hideLoading($('#saveStudentBtn'));
            
            if (response.status === 'success') {
                $('#addStudentModal').modal('hide');
                $('#addStudentForm')[0].reset();
                clearEmailValidation('#studentEmail', '#emailHelp');
                showAlert('Student added successfully!', 'success');
                loadStudents();
            } else {
                // Handle specific duplicate email error
                if (response.message && response.message.toLowerCase().includes('email')) {
                    $('#studentEmail').removeClass('is-valid').addClass('is-invalid');
                    $('#studentEmail').siblings('.invalid-feedback').text(response.message);
                    $('#emailHelp').removeClass('text-success text-muted').addClass('text-danger').text('✗ ' + response.message);
                    $('#studentEmail').focus();
                } else {
                    showAlert('Error: ' + response.message, 'danger');
                }
            }
        },
        error: function(xhr) {
            hideLoading($('#saveStudentBtn'));
            let errorMessage = 'Error connecting to server';
            
            // Try to parse JSON response even on error status codes
            try {
                const response = JSON.parse(xhr.responseText);
                if (response && response.message) {
                    errorMessage = response.message;
                    
                    // Handle specific duplicate email error
                    if (response.message.toLowerCase().includes('email') || 
                        (response.data && response.data.duplicate_email)) {
                        $('#studentEmail').removeClass('is-valid').addClass('is-invalid');
                        $('#studentEmail').siblings('.invalid-feedback').text(response.message);
                        $('#emailHelp').removeClass('text-success text-muted').addClass('text-danger').text('✗ ' + response.message);
                        $('#studentEmail').focus();
                        return; // Don't show the general alert for this case
                    }
                }
            } catch (e) {
                // If JSON parsing fails, use the default error message
                console.log('Could not parse error response:', xhr.responseText);
            }
            
            showAlert(errorMessage, 'danger');
        }
    });
}

function editStudent(studentId) {
    const student = studentsData.find(s => s.id == studentId);
    if (!student) return;
    
    $('#editStudentId').val(student.id);
    $('#editStudentName').val(student.name);
    $('#editStudentEmail').val(student.email || '');
    $('#editStudentPhone').val(student.phone || '');
    
    $('#editStudentModal').modal('show');
}

function updateStudent() {
    const formData = new FormData(document.getElementById('editStudentForm'));
    const studentData = Object.fromEntries(formData);
    
    // Check if email field has validation errors
    const emailInput = $('#editStudentEmail');
    if (emailInput.hasClass('is-invalid')) {
        showAlert('Please fix the email validation errors before submitting', 'warning');
        emailInput.focus();
        return;
    }
    
    showLoading($('#updateStudentBtn'));
    
    $.ajax({
        url: 'api/update_student.php',
        type: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(studentData),
        dataType: 'json',
        success: function(response) {
            hideLoading($('#updateStudentBtn'));
            
            if (response.status === 'success') {
                $('#editStudentModal').modal('hide');
                showAlert('Student updated successfully!', 'success');
                loadStudents();
            } else {
                // Handle specific duplicate email error
                if (response.message && response.message.toLowerCase().includes('email')) {
                    $('#editStudentEmail').removeClass('is-valid').addClass('is-invalid');
                    $('#editStudentEmail').siblings('.invalid-feedback').text(response.message);
                    $('#editEmailHelp').removeClass('text-success text-muted').addClass('text-danger').text('✗ ' + response.message);
                    $('#editStudentEmail').focus();
                } else {
                    showAlert('Error: ' + response.message, 'danger');
                }
            }
        },
        error: function(xhr) {
            hideLoading($('#updateStudentBtn'));
            let errorMessage = 'Error connecting to server';
            
            // Try to parse JSON response even on error status codes
            try {
                const response = JSON.parse(xhr.responseText);
                if (response && response.message) {
                    errorMessage = response.message;
                    
                    // Handle specific duplicate email error
                    if (response.message.toLowerCase().includes('email') || 
                        (response.data && response.data.duplicate_email)) {
                        $('#editStudentEmail').removeClass('is-valid').addClass('is-invalid');
                        $('#editStudentEmail').siblings('.invalid-feedback').text(response.message);
                        $('#editEmailHelp').removeClass('text-success text-muted').addClass('text-danger').text('✗ ' + response.message);
                        $('#editStudentEmail').focus();
                        return; // Don't show the general alert for this case
                    }
                }
            } catch (e) {
                // If JSON parsing fails, use the default error message
                console.log('Could not parse error response:', xhr.responseText);
            }
            
            showAlert(errorMessage, 'danger');
        }
    });
}

function deleteStudent(studentId, studentName) {
    if (!confirmDelete(`Are you sure you want to delete "${studentName}"? This will also delete all attendance records for this student.`)) {
        return;
    }
    
    $.ajax({
        url: 'api/delete_student.php',
        type: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({ id: studentId }),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showAlert('Student deleted successfully!', 'success');
                loadStudents();
            } else {
                showAlert('Error: ' + response.message, 'danger');
            }
        },
        error: function() {
            showAlert('Error connecting to server', 'danger');
        }
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>