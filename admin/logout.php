<?php
// Destroy the session and redirect to login page
session_start();
session_destroy();
header('Location: ../index.php');
exit();
?>
