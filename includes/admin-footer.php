<head>
    <link rel="stylesheet" href="../css/admin-footer.css">
</head>
<footer class="footer mt-auto py-4 bg-dark text-white">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12 text-center">
                <p class="mb-2">
                    &copy; <?php echo date('Y'); ?> 
                    <?php echo SITE_NAME ?? 'Nepal College of Technology'; ?>. 
                    All Rights Reserved.
                </p>
                <p class="mb-0">
                    NCT Admin Panel - Designed and Developed by 
                    <a href="https://bnabeen.github.io/personal-porfolio/" 
                       class="text-white text-decoration-none" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        Nabeen
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin JS -->
    <script src="<?php echo BASE_URL ?? ''; ?>/assets/js/admin.js"></script>
</footer>
</body>
</html>


