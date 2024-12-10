<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant - TKMN POS</title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/extensions/choices.js/public/assets/styles/choices.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">

    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <link rel="stylesheet" href="<?= base_url() ?>assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/pages/datatables.css">


    <link rel="stylesheet" href="<?= base_url() ?>assets/css/webix.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.css'; ?>" rel="stylesheet">
	<link href="<?php echo base_url().'assets/admin/assets/libs/summernote/dist/summernote-bs4.css';?>" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/admin/assets/libs/select2/dist/css/select2.min.css' ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
	<style>
	 .form-control-line {
            display: flex;
            width: 100%;
            padding: 0.375rem 0.75rem;
            line-height: 0;
            color: #4F5467;
            background-color: #fff;
            border: none;
            border-bottom: 1px solid #28282a;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
	</style>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            document.querySelectorAll('#table1_length, #table1_filter,#table1_info,#table1_paginate')
                .forEach(img => img.remove());
            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
    <script>
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
    </script>
    <script>
        var base_path = "<?= base_url() ?>";
    </script>
</head>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<!-- Preloader -->

<body>