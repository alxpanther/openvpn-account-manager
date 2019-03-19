<?php

// initialize the sqlite database with an admin account without 2FA.

$password=substr(md5(mt_rand().mt_rand()),0,12);
define("SKIP_IDENTITY_CONTROL",1);
require_once("head.php");

try {
    $db->exec("CREATE TABLE users (username text PRIMARY KEY, password text, isadmin boolean default false, totp text, usetotp boolean default false, created datetime, updated datetime, used datetime);");
    $db->exec("CREATE TABLE csrf (cookie char(32) , token char(32), created datetime, primary key (cookie,token));");
} catch (PDOException $e) {
    echo "SQLite already initialized, skipping...\n";
}

$db->query("REPLACE INTO users (username,password,isadmin,totp,usetotp,created,updated,used) VALUES ('admin','".password_hash($password,PASSWORD_DEFAULT)."', 1,'',0,datetime('now'),datetime('now'),null);");
echo "password for admin account is now ".$password."\n";

