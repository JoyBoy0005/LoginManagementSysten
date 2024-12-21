<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload classes
spl_autoload_register(function ($class) {
    include_once "classes/$class.php";
});
?>
