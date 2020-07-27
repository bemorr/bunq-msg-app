<?php
require 'vendor/autoload.php';

use App\SQLiteConnection;

$pdo = (new SQLiteConnection())->connect();
if ($pdo != null)
    echo 'Connected to the SQLite database successfully!';
else
    echo 'Whoops, could not connect to the SQLite database!'

?>

<form method="post" action="message.php">
    <h1><b>Bunq Chat app</b></h1>
    <div>
        <label for="username">Create a username for yourself:</label>
        </br>
        <input type="text" name="myUn" value="" id="username" required="required">
    </div>
    </br></br>
    <div>
        <label for="friendU/N">Enter username of friend you want to message:</label>
        </br>
        <input type="text" name="fUn" value="" id="friendUsername" required="required">
    </div>
    </br></br>
    <div>
        <input type="submit" value="Let's connect!">
    </div>
</form>