    </main>

    <!-- Footer -->
    <footer class="bg-light border-top mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-2">Hệ Thống Điểm Danh Cơ Bản</h6>
                    <p class="text-muted small mb-0">
                        Quản lý điểm danh học sinh một cách đơn giản và hiệu quả.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">
                        Built with PHP, MySQL, Bootstrap & jQuery<br>
                        &copy; <?php echo date('Y'); ?> Hệ thống điểm danh
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/app.js"></script>
    
    <!-- Page-specific scripts -->
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
        // Global JavaScript functions and variables
        
        // Show alert message
        function showAlert(message, type = 'info', duration = 5000) {
            const alertId = 'alert-' + Date.now();
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert" id="${alertId}">
                    <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('#alertContainer').append(alertHtml);
            
            // Auto-dismiss after duration
            if (duration > 0) {
                setTimeout(() => {
                    $('#' + alertId).alert('close');
                }, duration);
            }
        }
        
        // Show loading spinner
        function showLoading(element) {
            const originalContent = element.html();
            element.data('original-content', originalContent);
            element.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            element.prop('disabled', true);
        }
        
        // Hide loading spinner
        function hideLoading(element) {
            const originalContent = element.data('original-content');
            element.html(originalContent);
            element.prop('disabled', false);
        }
        
        // Format date for display
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        // Confirm deletion
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }
        
        // Initialize tooltips
        $(document).ready(function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>