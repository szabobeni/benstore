<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

require_once DIR_LIB_PROTECTED . '/phpmailer/class.smtp.php';
require_once DIR_LIB_PROTECTED . '/phpmailer/class.phpmailer.php';

/**
 *
 * Email functions
 */
class BaseMailHelper {

	private $error;

	public function getErrormessage() {
		return $this->error;
	}

	public function sendMail($to, $subject, $body, $files = array()) {
		$mail             = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug  = 2;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = EMAIL_SMTPSECURE;
		$mail->Host       = EMAIL_SMTPHOST;
		$mail->Port       = EMAIL_SMTPPORT;
		$mail->Username   = EMAIL_SMTPUSER;
		$mail->Password   = EMAIL_SMTPPASSWORD;
		$mail->SetFrom(EMAIL_SENDER, EMAIL_SENDERNAME);
		$mail->Subject    = $subject;
			foreach ($files as $file)
				$mail->AddAttachment($file,basename($file));
		$mail->MsgHTML($body);
		$mail->AddAddress($to);
		ob_start();
		$ok = $mail->Send();
		$ob = ob_get_contents();
		ob_end_clean(); 
		if (!$ok) {
			$this->error = $mail->ErrorInfo . "\n" . $ob;
			$this->processError();
		}		
	}

	protected function processError() {
		 
	}

}
