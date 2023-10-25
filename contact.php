<?php

require("PHPMailer\src\PHPMailer.php");
require("PHPMailer\src\SMTP.php");
$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl';
$mail->Host = "mail.esmebeautycare.com";
$mail->Port = 465; //465  or 587
$mail->IsHTML(true);
$mail->Username = "contacto@esmebeautycare.com";
$mail->Password = "EsmeBeautyCare";
$mail->SetFrom("contacto@esmebeautycare.com");
$mail->Subject = "Contacto Desde : Esme Beauty Care";

$name = $_POST['name'];
$email = $_POST['email'];
$comment = $_POST['comment'];

if (!$name) $errors[count($errors)] = 'Please enter your name.';
if (!$email) $errors[count($errors)] = 'Please enter your email.'; 
if (!$comment) $errors[count($errors)] = 'Please enter your comment.'; 

$recaptcha_secret = '6LfmfMgoAAAAAJciCuPI9wIhr0C5mB8EI1vansgU';
$recaptcha_response = $_POST['token'];

$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret' => $recaptcha_secret,
    'response' => $recaptcha_response,
];

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$result_json = json_decode($result, true);

if ($result_json['success']) {
    // reCAPTCHA verification successful
    // Proceed with your form processing
} else {
    // reCAPTCHA verification failed
    // Handle accordingly (e.g., show an error message)
}

if (!$errors) {

	$mail->Body = "Nombre: {$name}  <br> Correo Electronico: {$email} <br> Mensaje : {$comment}";
	$mail->AddAddress("contacto@esmebeautycare.com");
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "OK";
	}

} else {
	for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
	echo '<a href="index.html">Back</a>';
	exit;
}
?>