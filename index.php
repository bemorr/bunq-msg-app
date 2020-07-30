<?php
error_reporting(E_ALL);

require 'vendor/autoload.php';

use App\SQLiteConnection;

try {
    $pdo = (new SQLiteConnection())->connect();
    echo 'Successful connection to SQLite database established!';
} catch (\PDOException $e) {
    var_dump($e);
    trigger_error('SQLite3 Fail: ' . $e->getMessage(), E_USER_ERROR);
}

?>

<head>
    <title>BunqsApp Users Login</title>
    <style>
        body {
            background-image: url('./public_html/assets/bunq-login-img.png');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
    </style>
</head>
<form method="post" id="chat_credentials" action="app/messaging_interface.php">
    <h1 style="background-color: white; width:150px"><b>BunqsApp</b></h1>
    <div>
        <label for="my_username">Create a username for yourself:</label>
        </br>
        <input type="text" name="my_user" value="" id="myUsername" required="required">
    </div>
    </br></br>
    <div>
        <label for="friend_username">Enter username of friend you want to message:</label>
        </br>
        <input type="text" name="f_user" value="" id="fUsername" required="required">
    </div>
    </br></br>
    <div>
        <input type="submit" value="Let's connect!">
    </div>
</form>