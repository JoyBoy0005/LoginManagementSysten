<?php
include '../init.php';
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
