<div x-show="registerModal" class="modal" x-cloak>
    <div class="modal-content" @click.away="registerModal = false">
        <div class="close" @click="registerModal = false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="title">
            <h3>Register</h3>
        </div>
        <div class="form">
            <?php echo get_theme_mod('registration_form_code', ''); ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer-wrapper">
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
            </div>
            <div class="poweredby">
                Powered by <a href="https://sequel.io" target="_blank">Sequel.io</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<?php echo get_theme_mod('tracking_codes', ''); ?>

</body>
</html>