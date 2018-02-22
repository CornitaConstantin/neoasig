<?php
//send DB
$con = mysql_connect("s243.altervista.org","cosion","amcispa80");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
 
mysql_select_db("my_cosion", $con);
 
$sql="INSERT INTO contacteazane (Nume, Email, Mesaj)
VALUES
('$_POST[name]','$_POST[email]','$_POST[message]'";
 
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
//echo "1 record added";
 
mysql_close($con)

  // echo '<script>console.log("Your stuff here")</script>';

?>


<?php
$field_name = $_POST['name'];
$field_email = $_POST['email'];
$field_message = $_POST['message'];

$mail_to = 'costica.cornita@yahoo.com';
$subject = '[Contacteaza-ne] Mesaj de la un vizitator: '.$field_name;

$body_message = 'De la: '.$field_name."<br>";
$body_message .= 'E-mail: '.$field_email."<br>";
$body_message .= 'Mesaj: '.$field_message."<br>";

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

  
$mail_status = mail($mail_to, $subject, $body_message, $headers);

if ($mail_status) { ?>
	<script language="javascript" type="text/javascript">
		window.location = '../../contacteaza-ne/mesaj-trimis/';
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
?>