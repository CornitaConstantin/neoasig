<?php
//send DB
$con = mysql_connect("s243.altervista.org","cosion","amcispa80");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
 
mysql_select_db("my_cosion", $con);
 
$sql="INSERT INTO reminder (Tipul, Data, Email, Telefon)
VALUES
('$_POST[tipul]','$_POST[dataexp]','$_POST[remail]','$_POST[rtelefon]')";
 
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
//echo "1 record added";
 
mysql_close($con)

  // echo '<script>console.log("Your stuff here")</script>';

?>



<?php
$field_tip = $_POST['tipul'];
$field_data = $_POST['dataexp'];
$field_email = $_POST['remail'];
$field_tel = $_POST['rtelefon'];
$field_name = $_POST['snumele'];
$field_phone = $_POST['stelefon'];

$mail_to = 'costica.cornita@yahoo.com';
$subject = 'Reminder: '.$field_tip;

$body_message = 'Asigurare mea '.$field_tip.'<br>';
$body_message .= 'Expira pe: '.$field_data.'<br>';
$body_message .= 'Suna-ma la acest numar pentru reminder: '.$field_tel.'<br>';
$body_message .= 'sau email: '.$field_email.'<br>';

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

if (($field_data=="")||($field_email=="")||($field_tel=="")||!(is_numeric($field_tel)))
        {?>
		<script language="javascript" type="text/javascript">
			alert('Completati campul!');
			window.location = document.referrer;
		</script>
		<?php
}
else{        
		$mail_status = mail($mail_to, $subject, $body_message, $headers);
        if ($mail_status) { ?>
		<script language="javascript" type="text/javascript">
			alert('Multumim, te vom recontacta.');
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
}  


?>