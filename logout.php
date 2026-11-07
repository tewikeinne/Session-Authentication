<?php
session_start();

$_SESSION = [];

session_destroy();

header("Locatrion: index.php");
exit;
?>