<?php
/**
* SMS Validation using SMSgateway.me
*
* @package    SMS_validation
* @author     Fidde.nu
* @version    1.0
* 
*/
require_once ('settings.php');
require_once ('smsGateway.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register</title>
</head>
<body>
	<?php
	 if (isset($_POST['form'])){
            $info['data'] = [
			'name'		=>	$_POST['name'],
			'number'	=>	$_POST['number']
			];
			if ($info['data']['name'] == '' OR $info['data']['number'] == ''){
				echo "Your input was not registred. Please try again";
			} else {
		$code = rand(1000, 9999);
		try {
                $pdo = new PDO('mysql:host=' . $info['db']['host'] . ';charset=utf8;dbname=' . $info['db']['name'], $info['db']['user'], $info['db']['pass']);
                $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            } catch(PDOException $e) {
                echo "DB FAIL: <br>";
                die ($e->getMessage());
            }
            $pdo->beginTransaction();
            $usertable = $info['db']['tblprefix'].'users';
            $sql = "INSERT INTO $usertable (r_name, r_number) VALUES (:rname, :rnumber);";
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':rname', $info['data']['name']);
            $sth->bindParam(':rnumber', $info['data']['number']);
            $sth->execute();
            $codetable = $info['db']['tblprefix'].'valcodes';
            $insertid = $pdo->lastInsertId();
            $sql2 = "INSERT INTO $codetable (memberid, code) VALUES (:memberid, :code);";

            $sth2 = $pdo->prepare($sql2);
            $sth2->bindValue(':code', $code);
            $sth2->bindParam(':memberid', $insertid);
            $sth2->execute();
            $pdo->commit();

$smsGateway = new SmsGateway($info['gw']['email'], $info['gw']['pass']);
$s_deviceID = $info['gw']['deviceID'];
$s_number = $info['data']['number'];
$s_message = "Hello {$info['data']['name']}, your validation code for Your site is {$code} and your member id is {$insertid}.";

$result = $smsGateway->sendMessageToNumber($s_number, $s_message, $s_deviceID);

            ?>
	<p>Check your phone, for your validation code and click <a href="confirm.php">here</a> to finish your registration</p>

            <?php
}        } else {
?>
	<form action="" method="post">
            <label for="name">Name:</label><input type="text" name="name" id="name" placeholder="Your Name" required="true"><br>
            <label for="number">Number:</label><input type="tel" name="number" id="number" placeholder="Your number" required="true"><br>
            <input type="submit" value="Register" name="form" id="form">
        </form>

<?php
        }

	?>
</body>
</html>