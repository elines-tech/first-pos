        </section>
        <footer>
            <div class="footer clearfix mb-0 text-muted text-center">
                <p>Application Developed By | 2022 ©<a href="www.kaem.in" target="_blank">KAEM Software</a></p>
            </div>
        </footer>
        </div>
        <script src="<?= base_url('assets/js/bootstrap.js') ?>"></script>
        <script src="<?= base_url('assets/js/app.js') ?>"></script>
        <script src="<?= base_url('assets/extensions/parsleyjs/parsley.min.js') ?>"></script>
        <script src="<?= base_url('assets/admin/assets/libs/toastr/build/toastr.min.js') ?>"></script>
        <script src="<?= base_url('assets/admin/assets/libs/sweetalert2/dist/sweet-alert.min.js') ?>"></script>
		<script src="<?php echo base_url() . 'assets/admin/assets/libs/select2/dist/js/select2.full.min.js'; ?>"></script>
		<script src="<?php echo base_url() . 'assets/admin/assets/libs/select2/dist/js/select2.min.js'; ?>"></script>
		<script src="<?php echo base_url() . 'assets/admin/dist/js/pages/forms/select2/select2.init.js'; ?>"></script>
        <script>
            $(window).on('load', function() { // makes sure the whole site is loaded         
                $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
                $('body').delay(250).css({
                    'overflow': 'visible'
                });
            });
            $(document).ready(function() {
                $('ul li a').click(function() {
                    $('.submenu').removeClass("active");
                    $(this).addClass("active");
                });
            });
        </script>
        </body>

        </html>