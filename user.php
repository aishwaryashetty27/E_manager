<?php require '../connection.php'; ?>
<?php
session_start();

// Check if the user is logged in, redirect to the login page if not
if (!isset($_SESSION['username'])) {
    header('Location: ../starterFile/login.html');
    exit();
}

// Get the logged-in username from the session
$loggedInUser = $_SESSION['username'];

// Fetch all hall_names from halls table
$sql = "SELECT hall_name FROM halls";
$result = $conn->query($sql);

// Fetch matching results based on hall_loc from halls table
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT hall_name FROM halls WHERE hall_loc LIKE '%$search%'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Page</title>
    <style>
       ul.navbar {
      list-style-type: none;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color: #333;
    }

    ul.navbar li {
      float: left;
    }

    ul.navbar li a {
      display: block;
      color: #fff;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }

    ul.navbar li a:hover {
      background-color: #ddd;
    }

    ul.navbar li.active a {
      background-color: #4CAF50;
      color: white;
    }
    body {
      background-image: url("hall6.jpg");
      background-size: cover;
      font-family: Arial, sans-serif;
      margin: 0;

      color: #ffffff;
    }

    .center {
        text-align: center;
    }


    table {
      width: 50%;
      border-collapse: collapse;
      background-color:black;
      color: black;
      margin: 0 auto; /* Center align the table */
    }

    th, td {
      padding: 10px;
      text-align: left;
      color: black;
    }

    th {
      background-color: white;
      color: black;
    }
    td.hall_name {
      color: black; /* Set the text color to black */
    }
    tr:hover {
      background-color: #ddd;
    }
    a {
      color: #ffffff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    input[type="text"] {
      padding: 10px;
      width: 300px;
      border: none;
      border-radius: 5px;
      color: black;
    }

    button.search-btn {
      background-color: #4CAF50;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    button.search-btn:hover {
      background-color: #45a049;
    }
    ul.hall-list {
      list-style-type: none;
      padding: 0;
    }

    ul.hall-list li {
      margin-bottom: 10px;
    }

    ul.hall-list li a {
      display: inline-block;
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }
    .right-align {
      float: right;
    }

    ul.hall-list li a:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
 <!-- Navigation Bar -->
 <ul class="navbar">
    <li class="right-align"><a href="history.php">Previous History</a></li>
    <li class="right-align"><a href="../starterFile/logout.php">Logout</a></li>
  </ul>
<br>
  <div class="center">
    <input type="text" id="searchInput" placeholder="Search location...">
    <button class="search-btn" onclick="searchHalls()">Search</button>
  </div>
  </br>
  <br>

  <div class="center">
    <?php
    if ($result->num_rows > 0) {
      echo '<table>';
      echo '<tr><th>Hall Name</th></tr>';
        while ($row = $result->fetch_assoc()) {
          echo '<tr><td><a href="booking.php?hall_name=' . $row['hall_name'] . '&user_mail=' . $loggedInUser . '">' . $row['hall_name'] . '</a></td></tr>';
        }
        echo '</table>';
    } else {
        echo "No halls found.";
    }
    ?>
  </div>

  <script>
    function searchHalls() {
        var input = document.getElementById("searchInput").value;
        window.location.href = "user.php?search=" + input;
    }
  </script>

</body>
</html>
