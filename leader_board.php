<?php
session_start();
$_SESSION['action'] = 'leaderboard'; // Set the session variable
header('Location: home.php'); // Redirect to the desired page
exit();
?>