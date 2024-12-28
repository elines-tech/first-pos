<footer>
    <div class="footer clearfix mb-0 text-muted text-center">
        <p>Application Developed By | 2024 ©<a href="https://elines.tech" target="_blank">Elines.tech</a></p>
    </div>
</footer>
</div>

<script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
<script src="<?= base_url() ?>assets/js/app.js"></script>

<script src="<?= base_url() ?>assets/js/webix.js"></script>

<script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/parsley.js"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.min.js'; ?>"></script>
<script>
    $(window).on('load', function() { // makes sure the whole site is loaded 
        $('#status').fadeOut(); // will first fade out the loading animation 
        $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(250).css({
            'overflow': 'visible'
        });
    });

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        document.querySelectorAll('#table1_length, #table1_filter,#table1_info,#table1_paginate')
            .forEach(img => img.remove());
        window.print();

        document.body.innerHTML = originalContents;
    }
    // ScrollFade 0.1
    var fadeElements = document.getElementsByClassName('scrollFade');

    function scrollFade() {
        var viewportBottom = window.scrollY + window.innerHeight;

        for (var index = 0; index < fadeElements.length; index++) {
            var element = fadeElements[index];
            var rect = element.getBoundingClientRect();

            var elementFourth = rect.height / 4;
            var fadeInPoint = window.innerHeight - elementFourth;
            var fadeOutPoint = -(rect.height / 2);

            if (rect.top <= fadeInPoint) {
                element.classList.add('scrollFade--visible');
                element.classList.add('scrollFade--animate');
                element.classList.remove('scrollFade--hidden');
            } else {
                element.classList.remove('scrollFade--visible');
                element.classList.add('scrollFade--hidden');
            }

            if (rect.top <= fadeOutPoint) {
                element.classList.remove('scrollFade--visible');
                element.classList.add('scrollFade--hidden');
            }
        }
    }

    document.addEventListener('scroll', scrollFade);
    window.addEventListener('resize', scrollFade);
    document.addEventListener('DOMContentLoaded', function() {
        scrollFade();
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