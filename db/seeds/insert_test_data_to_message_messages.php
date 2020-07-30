<?php

$db = new SQLite3('../messages_db.db');

$db->exec('UPDATE messages SET status = 1  WHERE id = 64');

