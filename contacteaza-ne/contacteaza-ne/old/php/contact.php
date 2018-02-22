<?php
$field_name = $_POST['name'];
$field_email = $_POST['email'];
$field_message = $_POST['message'];

$mail_to = 'raijin10.ic@gmail.com';
$subject = 'Message from a site visitor '.$field_name;

$body_message = 'De la: '.$field_name."\n";
$body_message .= 'E-mail: '.$field_email."\n";
$body_message .= 'Mesaj: '.$field_message;

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

if (($field_name=="")||($field_email=="")||($field_message==""))
        {?>
		<script language="javascript" type="text/javascript">
			alert('Completati campul!');
			window.location = '../../contacteaza-ne/';
		</script>
		<?php
}
else{        
		$mail_status = mail($mail_to, $subject, $body_message, $headers);
        if ($mail_status) { ?>
		<script language="javascript" type="text/javascript">
			alert('Multumim, te vom recontacta.');
			window.location = '../../contacteaza-ne/';
		</script>
		<?php
		}
		else { ?>
			<script language="javascript" type="text/javascript">
				alert('Message failed.');
				window.location = '../../contacteaza-ne/';
			</script>
		<?php
		}
}  


?>