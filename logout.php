<?php
session_start();

// Rips off the wristband (destroys all session data)
session_destroy(); 

// Send them back to the login page
header("Location: login.php");
exit();
?>