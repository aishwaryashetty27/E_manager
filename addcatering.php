<?php
session_start();

// Check if the user is logged in, redirect to the login page if not
if (!isset($_SESSION['username'])) {
  header('Location: ../starterFile/login.html');
  exit();
}

// Include the connection.php file to establish a database connection
require '../connection.php';

// Get the logged-in username from the session
$loggedInUser = $_SESSION['username'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $menuName = $_POST['menu_name'];
  $numOfItems = $_POST['num_of_items'];
  $price = $_POST['price'];

  // SQL query to insert data into the "catering" table
  $sql = "INSERT INTO catering (gmail, menu_name, price";
  for ($i = 1; $i <= $numOfItems; $i++) {
    $sql .= ", item_name" . $i;
  }
  $sql .= ") VALUES ('$loggedInUser', '$menuName', $price";
  for ($i = 1; $i <= $numOfItems; $i++) {
    $itemName = $_POST['item_name' . $i];
    $sql .= ", '$itemName'";
  }
  $sql .= ")";

  // Execute the SQL query
  if ($conn->query($sql) === TRUE) {
    echo "Menu added successfully!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Retrieve the halls associated with the logged-in manager
$stmt = $conn->prepare("SELECT * FROM catering WHERE gmail = ?");
$stmt->bind_param("s", $loggedInUser);
$stmt->execute();

// Fetch the result
$result = $stmt->get_result();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Menu</title>
  <style>
    /* Add CSS styles for the navigation bar */
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

    /* Add CSS styles for right-aligned elements */
    .right-align {
      float: right;
    }
    .left-align {
      float: left;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-image: url("hall6.jpg");
      background-repeat: no-repeat;
      background-size: cover;
    }

    .container {
      max-width: 800px;
      margin: 50px auto;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.9);
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    h3 {
      color: #333;
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

    ul.hall-list li a:hover {
      background-color: #45a049;
    }

    p.no-halls {
      color: #666;
    }
    input[type="submit"] {
      background-color: #4CAF50;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <ul class="navbar">
    <li class="left-align"><a href="manager.php">Home</a></li>
    <li><a href="addhall.php?mail=<?php echo $loggedInUser; ?>">Add Hall</a></li>
    <li class="right-align"><a href="addcatering.php">Add Catering</a></li>
    <li class="left-align"><a href="../starterFile/logout.php">Logout</a></li>
  </ul>
    
  <div class="container">
  <h3>Your Menu's:</h3>
      <?php if ($result->num_rows > 0): ?>
        
        <ul class="hall-list">
          <?php while ($row = $result->fetch_assoc()): ?>
            <li>
              <a href="editmenu.php?menu_name=<?php echo $row['menu_name']; ?>"><?php echo $row['menu_name']; ?></a>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="no-halls">No menu's found.</p>
      <?php endif; ?>

    <input type="submit" value="Add Menu" onclick="location.href='addmenu.php?mail=<?php echo $loggedInUser; ?>';" title="Click here to add a menu">

  </div>
</body>
</html>
