<?php
session_start();
require_once 'classes/members.php';
$members = new Members();

//Log Out
if(isset($_GET['status']) && $_GET['status'] == 'loggedout'){
    $members->log_User_Out();
}

//Checks input
if($_POST && !empty($_POST['username']) && !empty($_POST['password'])){
    $response = $members->validate_user($_POST['username'],$_POST['password']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/foundation.min.css">
    <link rel="stylesheet" href="css/master.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans:400,700" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UC Brabo | Login</title>
</head>
<body>
    <div id="login">
        <form action="" method="post">
            <h1>Login Boekenshop UC</h1>
                <label for="name">Username:</label>
                <input type="text" id="name" name="username" required>
                <label for="pwd">Password:</label>
                <input type="password" name="password" id="pwd" required>
                <input class="button" type="submit" value="Inloggen">
			<?php if(isset($response)) echo "<h4>" . $response . "</h4>"; ?>
        </form>

    </div>
</body>
</html>
