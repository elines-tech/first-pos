<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Emailer
{
    /**
     * @param configuration - mail configuration array (supports smtp only)
     * @param tomailaddress - to mail address string
     * @param subject - subject or mail title string
     * @param message - message text or html format string
     * @param attachments - attachments with proper file path array (optional)
     */
    function transact(array $configuration, string $toMailAddress, string $subject, string $message, $attachments = array())
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Mailer = strtolower($configuration['maildriver']);
            $mail->Host = $configuration['host'];
            // if (array_key_exists('encryption', $configuration)) {
            //     if (in_array($configuration['encryption'], ['tls', 'ssl', 'true', true])) {
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
			$mail->SMTPDebug=1;
            //     } else {
            //         $mail->SMTPAuth = false;
            //         $mail->SMTPAutoTLS = false;
            //     }
            // } else {
            //     $mail->SMTPAuth = false;
            //     $mail->SMTPAutoTLS = false;
            // }
            $mail->Username   = $configuration['username'];
            $mail->Password   = $configuration['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $configuration['port'];
            //Recipients
            $mail->setFrom($configuration['fromaddress'], $configuration['fromname']);
            $mail->addAddress($toMailAddress);
            //$mail->addReplyTo('info@example.com', 'Information');       
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $mail->addAttachment($attachment);
                }
            }
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            if ($mail->send()) {
                return 'SUCCESS';
            } else {
                return "FAILED";
            }
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
