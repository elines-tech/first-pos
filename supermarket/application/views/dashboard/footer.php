<footer>
    <div class="footer clearfix mb-0 text-muted text-center">
        <p>Application Developed By | 2024 ©<a href="https://elines.tech/" target="_blank">Elines.tech</a></p>
    </div>
</footer>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/app.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/extensions/choices.js/public/assets/scripts/choices.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/pages/form-element-select.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/datatables.js"></script>
<script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/parsley.js"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/select2/dist/js/select2.full.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/select2/dist/js/select2.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/dist/js/pages/forms/select2/select2.init.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/summernote/dist/summernote-bs4.min.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(window).on('load', function() { // makes sure the whole site is loaded   
        $('#status').fadeOut();
        $('#preloader').delay(250).fadeOut('slow');
        $('body').delay(250).css({
            'overflow': 'visible'
        });
    });

    $(function() {
        $(".datepicker").flatpickr({
            "dateFormat": "d-m-Y H:i",
            "enableTime": true,
            "time_24hr": true,
            "confirmText": "OK",            
            "minDate": new Date()
        });

        $(".editstartDate").flatpickr({
            "dateFormat": "d-m-Y H:i",
            "enableTime": true,
            "time_24hr": true,
            "confirmText": "OK",
            "showAlways": true,
        });

        $(".editendDate").flatpickr({
            "dateFormat": "d-m-Y H:i",
            "enableTime": true,
            "time_24hr": true,
            "confirmText": "OK",
            "showAlways": true,
        });
    });

    // const page = "<?= strtolower($this->router->fetch_class()) ?>";
    const page = "<?= strtolower($this->uri->segment(1)) ?>";
    //console.log(page);
    $(document).ready(function() {
        $('ul li a').click(function() {
            $('.submenu').removeClass("active");
            $(this).addClass("active");
        });

        const anchors = $("a.side-anchors");
        if (anchors.length > 0) {
            anchors.each(function(item) {
                // console.log("anchor tag", $(this).data("attr"));
                if ($(this).data("attr").toLowerCase() === page) {
                    $(this).addClass("active");
                    $(this).parentsUntil("li.sidebar-item").addClass("active");
                }
            })
        }
    });
</script>
</body>

</html>