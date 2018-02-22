
<?php
//send DB
$con = mysql_connect("s243.altervista.org","cosion","amcispa80");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
 
mysql_select_db("my_cosion", $con);
 
$sql="INSERT INTO sunama (Numele, Telefon)
VALUES
('$_POST[snumele]','$_POST[stelefon]')";
 
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
//echo "1 record added";
 
mysql_close($con)

  // echo '<script>console.log("Your stuff here")</script>';

?>


<?php
//send email
$field_name = $_POST['snumele'];
$field_phone = $_POST['stelefon'];

$mail_to = 'costica.cornita@yahoo.com';
$subject = 'Suna-ma: '.$field_name;

$body_message = 'Ma numesc: '.$field_name.'<br>';
$body_message .= 'Suna-ma la acest numar: '.$field_phone.'<br>';

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


		$mail_status = mail($mail_to, $subject, $body_message, $headers);
        if ($mail_status) { ?>
		<script language="javascript" type="text/javascript">
				alert('Mesaj trimis!');
				window.location = document.referrer;
			</script>
		<?php
		}
		else { ?>
			<script language="javascript" type="text/javascript">
				alert('Message failed.');
				window.location = document.referrer;
			</script>
		<?php
		}
//}  


?>
