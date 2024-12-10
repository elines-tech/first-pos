<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(0);
$session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
$role = ($this->session->userdata['cash_logged_in' . $session_key]['role']);
$lang = "english";
if (isset($this->session->userdata['cash_logged_in' . $session_key])) {
    $code = ($this->session->userdata['cash_logged_in' . $session_key]['code']);
    $empno = ($this->session->userdata['cash_logged_in' . $session_key]['empno']);
    $name = ($this->session->userdata['cash_logged_in' . $session_key]['name']);
    $username = ($this->session->userdata['cash_logged_in' . $session_key]['username']);
    $role = ($this->session->userdata['cash_logged_in' . $session_key]['role']);
    $lang = ($this->session->userdata['cash_logged_in' . $session_key]['lang']);
    $profilePhoto = ($this->session->userdata['cash_logged_in' . $session_key]['userImage']);
    $branchCode = ($this->session->userdata['cash_logged_in' . $session_key]['branchCode']);
} else {
    return redirect('Cashier/login');
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="branchCode" content="<?= $branchCode ?>">
    <meta name="userLang" content="<?= strtolower($lang) ?>">
    <title>Restaurant - Cashier - TKMN POS</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.svg') ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/css/main/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/shared/iconly.css') ?>">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <link href="<?= base_url('assets/admin/assets/libs/toastr/build/toastr.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/admin/assets/libs/sweetalert2/dist/sweet-alert.css') ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/admin/assets/libs/select2/dist/css/select2.min.css' ?>">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <style>
        footer {
            border-radius: 8px;
        }
    </style>
    <script>
        var base_path = "<?= base_url() ?>";
        var lang = "<?= $lang ?>";
    </script>
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- Preloader -->
    <div class="container-fluid">
        <section id="multiple-column-form" class=" catproduct">