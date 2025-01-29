<?php
// Destroy the session and redirect to the login page
session_start();
session_destroy();
header("Location: login.php");
exit;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Logout</title>
</head>
<body>
  <h2>Logout</h2>
</body>
</html>
