<?php
include('SMTPClass.php');

$use_smtp = '0';
$emailto = 'mbeauty21@ukr.net';

	// retrieve from parameters
//	$emailto = isset($_POST["emailto"]) ? $_POST["emailto"] : "";
	$nocomment = isset($_POST["nocomment"]) ? $_POST["nocomment"] : "";
	$subject = isset($_POST["emailsubject"]) ? $_POST["emailsubject"] : "";
	$message = '';
	$response = '';
	$response_fail = 'There was an error verifying your details.';
	
		// Honeypot captcha
		if($nocomment == '') {
		
			$params = $_POST;
			foreach ( $params as $key=>$value ){
			
				if(!($key == 'ip' || $key == 'emailsubject' || $key == 'url' || $key == 'emailto' || $key == 'nocomment' || $key == 'v_error' || $key == 'v_email')){
				
					$key = ucwords(str_replace("-", " ", $key));
					
					if ( gettype( $value ) == "array" ){
						$message .= "$key: \n";
						foreach ( $value as $two_dim_value )
						$message .= "...$two_dim_value<br>";
					}else {
						$message .= $value != '' ? "$key: $value\n" : '';
					}
				}
			}
			
		$response = sendEmail($subject, $message, $emailto);
			
		} else {
		
			$response = $response_fail;
		
		}

	echo $response;

// Run server-side validation
function sendEmail($subject, $content, $emailto) {
	
	$from = "Кушон";
	$response_sent = 'https://www.yandex.ru/?clid=2136369&win=224';
	$response_error = 'Error. Please try again.';
	$subject =  filter($subject);
	$url = "Страница: ".$_SERVER['HTTP_REFERER'];
	$ip = $_SERVER['REMOTE_ADDR'];;
	$message = $content."\nIP-адрес: $ip\r\n$url";
	
	// Validate return email & inform admin
	$emailto = filter($emailto);

	// Setup final message
	$body = wordwrap($message);
	
	if($use_smtp == '1'){
	
		$SmtpServer = 'SMTP SERVER';
		$SmtpPort = 'SMTP PORT';
		$SmtpUser = 'SMTP USER';
		$SmtpPass = 'SMTP PASSWORD';
		
		$to = $emailto;
		$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body);
		$SMTPChat = $SMTPMail->SendMail();
		$response = $SMTPChat ? $response_sent : $response_error;
		
	} else {
		
		// Create header
		$headers = "From: $from\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/plain; charset=utf-8\r\n";
		$headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
		
		// Send email
		$mail_sent = @mail($emailto, $subject, $body, $headers);
		$response = $mail_sent ? $response_sent : $response_error;
		
	}
	
}

// Remove any un-safe values to prevent email injection
function filter($value) {
	$pattern = array("/\n/", "/\r/", "/content-type:/i", "/to:/i", "/from:/i", "/cc:/i");
	$value = preg_replace($pattern, "", $value);
	return $value;
}
// header('Location: done.html');


     


?>

<script type="text/javascript">
	window.location.href="../spasibo/"
</script>