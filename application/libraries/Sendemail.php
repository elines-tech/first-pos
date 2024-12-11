<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sendemail
{
	private $CI;
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->library('phpmailer_lib');
	}

	/*

	public function sendMailOnly($to, $subject, $message)
	{
		// PHPMailer object
		$mail = $this->CI->phpmailer_lib->load();
		// SMTP configuration
		$mail->isSMTP();
		$mail->Host = 'localhost';
		$mail->SMTPAuth = false;
		$mail->SMTPAutoTLS = false;
		$mail->Port = 25;
		$mail->SMTPAuth = false;
		$mail->Username = mailId;
		$mail->Password = mailpassword;
		$mail->CharSet = "UTF-8";
		$mail->setFrom(mailId, mailName);
		$mail->addAddress($to);
		$mail->Subject = $subject;
		//$mail->addAttachment($uploadfile, 'My uploaded file');
		// Set email format to HTML
		$mail->isHTML(true);
		// Email body content
		$mailContent = $message;
		$mail->Body = $mailContent;
		// Send email
		if (!$mail->send()) {
			$response['success'] = 'false';
			$response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			$response['success'] = 'true';
			$response['message'] = 'Mail send successfully';
		}
		return $response;
	}
		*/


		public function sendMailOnly($to, $subject, $message)
		{
			$mail = $this->CI->phpmailer_lib->load();
			$mail->isSMTP();
			$mail->Mailer = "smtp";
			$mail->Host = 'tamakan.com.sa';//hostinger.com
			$mail->Port = 465;
			$mail->SMTPAuth = true;

			$mail->SMTPSecure = "ssl";
			$mail->Username = 'service@tamakan.com.sa';
			$mail->Password = 'service@123415678';
			$mail->setFrom('service@tamakan.com.sa', "Tamakan Software");
			$mail->CharSet = "UTF-8";
			$mail->addAddress($to);
			$mail->Subject = $subject;
			$mail->isHTML(true);
			$mailContent = $message;
			$mail->Body = $mailContent;
			if (!$mail->send()) {
				$response['success'] = 'false';
				$response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				$response['success'] = 'true';
				$response['message'] = 'Mail send successfully';
			}
			return $response;
		}

/*
	function sendMailWithAttachment($toAddress, $subject, $message, $attachments = array())
	{
		// PHPMailer object
		$mail = $this->CI->phpmailer_lib->load();
		// SMTP configuration
		$mail->isSMTP();
		$mail->Host = 'localhost';
		$mail->SMTPAuth = false;
		$mail->SMTPAutoTLS = false;
		$mail->Port = 25;
		$mail->SMTPAuth = false;
		$mail->Username = mailId;
		$mail->Password = mailpassword;
		$mail->CharSet = "UTF-8";
		$mail->setFrom(mailId, AppName);
		if (is_array($toAddress)) {
			foreach ($toAddress as $to) {
				$mail->addAddress($to);
			}
		} else {
			$mail->addAddress($toAddress);
		}
		$mail->Subject = $subject;
		if (sizeof($attachments) > 0) {
			for ($ct = 0; $ct < count($attachments); $ct++) {
				$mail->AddAttachment($attachments[$ct]);
			}
		}
		$mail->isHTML(true);
		if ($message != '') {
			$mailContent = $message;
			$mail->Body = $mailContent;
		}
		if (!$mail->send()) {
			$response['success'] = 'false';
			$response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			$response['success'] = 'true';
			$response['message'] = 'Mail send successfully';
		}
		return $response;
	}

	*/

	function sendMailWithAttachment($toAddress, $subject, $message, $attachments = array())
    {
        $mail = $this->CI->phpmailer_lib->load();
        $mail->isSMTP();
        $mail->Mailer = "smtp";
        $mail->Host = 'tamakan.com.sa';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Username = 'service@tamakan.com.sa';
        $mail->Password = 'service@123415678';
        $mail->setFrom('service@tamakan.com.sa', "Tamakan Software");
        $mail->CharSet = "UTF-8";
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $message;
        if (is_array($toAddress)) {
            foreach ($toAddress as $to) {
                $mail->addAddress($to);
            }
        } else {
            $mail->addAddress($toAddress);
        }
        if (sizeof($attachments) > 0) {
            for ($ct = 0; $ct < count($attachments); $ct++) {
                $mail->AddAttachment($attachments[$ct]);
            }
        }
        if (!$mail->send()) {
            $response['success'] = 'false';
            $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            log_message("error", "INVOICE SENT TO $toAddress");
            $response['success'] = 'true';
            $response['message'] = 'Mail send successfully';
        }
        return $response;
    }

}
