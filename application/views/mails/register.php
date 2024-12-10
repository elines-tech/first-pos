<?php
$category = strtolower($category);
$url = base_url($category . "/login");
$category = ucwords(strtolower($category));
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>TAMAKAN POS Softaware</title>
</head>

<body style="padding:5px;width:100%;margin:0 auto;">
    <h3>Welcome to <b>TAMAKAN Software</b> </h3>
    <h5>Hi, <span style="color:#1d4097"><?= $name ?></span></h5>
    <p>Your <b style="color:#5b0788"><?= $category ?></b> account is created successfully.</p>
    <p>We’re excited to have you on board and we’d love to say thank you on behalf of our whole company for choosing us. We believe our service will help you. To ensure you gain the very best out of our service, please visit our product-service by visitng the link below or copy page the url.</p>
    <p>You need to use the following credentials so that you could interact with the web-application software. Please do-not share the credentials with any one. After login into the system, complete the on-boarding process and change the password once with new one of your choice for security purpose.</p>
    <div style="padding:5px;color:#3b444b;background: #d8e4bc ;font-size:1.0rem">
        <p>Site (Login URL) : <a style="color:#525356;font-size:1.0rem;font-weight:bold" href="<?= $url ?>" target="_blank"><?= $url ?></a></p>
        <p>Company Code : <span style="color:#525356;font-size:1.0rem;font-weight:bold"><?= $cmpcode ?></span></p>
        <p>Username : <span style="color:#525356;font-size:1.0rem;font-weight:bold"><?= $username ?></span></p>
        <p>Passcode : <span style="color:#525356;font-size:1.0rem;font-weight:bold"><?= $password ?></span></p>
    </div>
    <div style="padding:5px;color:#ff0000;margin-top:15px;background: #fde7e7;">Note: <strong>Please use only the details provided above. Only these can be used used to enter into the system.</strong></div>
    <p style="margin-top:15px">Thank you.<br><b>TAMAKAN SOFTWARE</b></p>
</body>

</html>