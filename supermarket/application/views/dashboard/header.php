<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(0);
$session_key = $this->session->userdata('key' . SESS_KEY);
$role = ($this->session->userdata['logged_in' . $session_key]['role']);
if (isset($this->session->userdata['logged_in' . $session_key])) {
    $code = ($this->session->userdata['logged_in' . $session_key]['code']);
    $username = ($this->session->userdata['logged_in' . $session_key]['username']);
    $name = ($this->session->userdata['logged_in' . $session_key]['name']);
    $empno = ($this->session->userdata['logged_in' . $session_key]['empno']);
    $role = ($this->session->userdata['logged_in' . $session_key]['role']);
    $roleCode = ($this->session->userdata['logged_in' . $session_key]['rolecode']);
    $profilePhoto = ($this->session->userdata['logged_in' . $session_key]['userImage']);
    $language = ($this->session->userdata['logged_in' . $session_key]['lang']);
    $userBranch = ($this->session->userdata['logged_in' . $session_key]['userBranch']);
} else {
    return redirect('login');
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/extensions/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/pages/datatables.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/webix.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.css' ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/admin/assets/libs/select2/dist/css/select2.min.css' ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/admin/assets/libs/summernote/dist/summernote-bs4.css'; ?>">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

        a.side-anchors.active {
            background: #ffe8efdb;
            border-radius: 8px;
        }

        div.dataTables_wrapper {
            overflow-x: scroll;
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
        var base_path = "<?= base_url() ?>";
    </script>
</head>

<body>
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <div id="app">
        <?php include_once("sidebar.php"); ?>
        <div id="main" class='layout-navbar'>
            <header class='mb'>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block d-xl-none">
                            <i class="bi bi-justify fs-3"></i>
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                            </ul>
                            <div class="dropdown dropdown-menu-end">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-white"><?= ucwords(strtolower($name)) ?></h6>
                                            <p class="mb-0 text-sm text-white"><?= ucwords($role) ?></p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="<?= $profilePhoto != "" ? base_url($profilePhoto) : base_url('assets/images/faces/1.jpg') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                                    <li>
                                        <h6 class="dropdown-header"><?php echo $translations['Hello']?>, <?= $name ?>!</h6>
                                    </li>
                                    <li>
                                        <form class="form" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url(); ?>Profile/view">
                                            <input type="hidden" name="code" value="<?= $code ?>">
                                            <button class="dropdown-item" type="submit">
                                                <i class="icon-mid bi bi-person me-2"></i><?php echo $translations['My Profile'] ?>
                                            </button>
                                        </form>
                                    </li>
                                    <?php
                                    if ($roleCode != 'R_5') {
                                    ?>
                                        <li>
                                            <form class="form" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url(); ?>Profile/updatePassword">
                                                <input type="hidden" name="code" value="<?= $code ?>">
                                                <button class="dropdown-item" type="submit">
                                                    <i class="fa fa-unlock me-2"></i><?php echo $translations['Password Update'] ?>
                                                </button>
                                            </form>
                                        </li>
                                    <?php
                                    }
                                    if ($roleCode == 'R_1') {
                                        //echo '<li><a class="dropdown-item" href="' . base_url("company") . '"><i class="fa fa-book me-2"></i>Company</a></li>';

                                    ?>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo base_url("company"); ?>">
                                                <i class="fa fa-book me-2"></i>
                                                <?php echo $translations['Company']; ?>
                                            </a>
                                        </li>
                                    <?php

                                    }
                                    ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo base_url(); ?>Authentication/logout"><i class="icon-mid bi bi-box-arrow-left me-2"></i><?php echo $translations['Logout'] ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>