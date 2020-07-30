<?php

$db = new SQLite3('../messages_db.db');

$db->exec("CREATE TABLE IF NOT EXISTS messages( 
    id INTEGER PRIMARY KEY,
    recipient TEXT NOT NULL,
    'from' TEXT NOT NULL,
    msg text NOT NULL,
    status text NOT NULL,
    time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP)");
