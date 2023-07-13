<?php
session_start();
session_destroy();
header("Location: ../php/loginPage.php");
exit;
?>