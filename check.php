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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Users</title>
</head>
<body>
    <?php
		try {
                $pdo = new PDO('mysql:host=' . $info['db']['host'] . ';charset=utf8;dbname=' . $info['db']['name'], $info['db']['user'], $info['db']['pass']);
                $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            } catch(PDOException $e) {
                echo "DB FAIL: <br>";
                die ($e->getMessage());
            }
            $usertable = $info['db']['tblprefix'].'users';
            $codetable = $info['db']['tblprefix'].'valcodes';
            $sql = "SELECT $usertable.`id` AS m_id, $usertable.`r_name` AS m_name, $usertable.`r_number` AS m_number, $usertable.`validated` AS m_validated, $codetable.`code` AS m_code FROM $usertable INNER JOIN $codetable ON $usertable.`id` = $codetable.`memberid`;";
            $sth = $pdo->prepare($sql);
            $sth->execute();
            
if ($sth->rowCount()){?>
<table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Number</th>
            <th>Code</th>
            <th>Verified</th>
        </tr>
<?php

            foreach ($sth->fetchAll() as $row){
                echo <<<EOD
        <tr>
            <td>{$row['m_id']}</td>
            <td>{$row['m_name']}</td>
            <td>{$row['m_number']}</td>
            <td>{$row['m_code']}</td>
            <td>{$row['m_validated']}</td>
        </tr>

EOD;
}
?>
    </table>
<?php
            } else {
                echo "<h2 style='color: red;'>No users.";
            }
	?>
</body>
</html>