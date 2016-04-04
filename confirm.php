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
    <title>Validate</title>
</head>
<body>
    <?php
        if (isset($_POST['form'])){
            $userID = $_POST['memberid'];
            $sent_code = $_POST['code'];
            try {
                $pdo = new PDO('mysql:host=' . $info['db']['host'] . ';charset=utf8;dbname=' . $info['db']['name'], $info['db']['user'], $info['db']['pass']);
                $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            } catch(PDOException $e) {
                echo "DB FAIL: <br>";
                die ($e->getMessage());
            }
            $usertable = $info['db']['tblprefix'].'users';
            $codetable = $info['db']['tblprefix'].'valcodes';
            $sql = "SELECT $usertable.`id` AS m_id, $usertable.`r_name` AS m_name, $usertable.`r_number` AS m_number, $usertable.`validated` AS m_validated, $codetable.`code` AS m_code FROM $usertable INNER JOIN $codetable ON $usertable.`id` = $codetable.`memberid` WHERE $usertable.`id` = :user;";
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':user', $userID);
            $sth->execute();
            
if ($sth->rowCount()){
foreach ($sth->fetchAll() as $row){
    if ($row['m_code'] === $sent_code){
        if ($row['m_validated']) {
            echo "Already validated";
        } else {
            $sql = "UPDATE $usertable SET validated = TRUE WHERE id = :user;";
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':user', $row['m_id']);
            $sth->execute();
            echo "Validated. You can now log in.";
        }
    } else {
        echo "Wrong code<br>";
        echo "<a href='confirm.php'>Click here to try again</a>";
    }
}
            } else {
                echo "<h2 style='color: red;'>User not found.</h2>";
                echo "<a href='confirm.php'>Click here to try again</a>";
            }
        } else {?>
        <form action="" method="post">
            <label for="memberid">Member ID:</label><input type="tel" name="memberid" id="memberid" placeholder="Member ID"><br>
            <label for="code">Code:</label><input type="tel" name="code" id="code" placeholder="Code"><br>
            <input type="submit" value="Validate" name="form" id="form">
        </form>
            <?php
        }
        
    ?>
</body>
</html>