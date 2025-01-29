<?php
session_start();

// Check if the user is logged in, redirect to the login page if not
if (!isset($_SESSION['username'])) {
  header('Location: ../starterFile/login.html');
  exit();
}

// Include the connection.php file to establish a database connection
require '../connection.php';

$loggedInUser = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $menuName = $_POST['menu_name'];
  $numOfItems = $_POST['num_of_items'];
  $price = $_POST['price'];

  $sql = "INSERT INTO catering (gmail, menu_name, price";
  for ($i = 1; $i <= $numOfItems; $i++) {
    $sql .= ", item_name" . $i;
  }
  $sql .= ") VALUES ('$loggedInUser', '$menuName', $price";
  for ($i = 1; $i <= $numOfItems; $i++) {
    $itemName = $_POST['additional_item_name' . $i];
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

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
  <title>Add Menu</title>
  <style>
    body {
      background-image: url("hall6.jpg");
      background-color: #f2f2f2;
      background-size: stretch;
      font-family: Arial, sans-serif;
      margin:0px;

    }

    h2 {
      color: #333333;
    }
.form{
      padding-bottom: 300px;
      padding-left: 300px;

      padding-right: 300px;

}
    form {
      background-image: url("menu1.jpg");
      background-color: #ffffff;
      padding: 60px;
      border-radius: 5px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
      color :black;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
      width: 100%;
      padding: 10px;
      border-radius: 0px;
      border: 1px solid #cccccc;
      margin-bottom: 15px;
    }

    input[type="file"] {
      margin-bottom: 15px;
    }

    input[type="radio"] {
      margin-right: 5px;
    }

    input[type="submit"] {
      background-color: #4CAF50;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    .error {
      color: #ff0000;
      margin-bottom: 10px;
    }


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

  <div class="form">
  <h2 <b style="color:white">Add Menu</b></h2>
<form method="post">
  <label for="menuName">Menu Name:</label>
  <input type="text" id="menuName" name="menu_name"><br><br>

  <label for="numOfItems">Number of Items:</label>
  <select id="numOfItems" name="num_of_items" onchange="createInputBoxes()">
  <option>SELECT NO. OF ITEMS</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option> 
    <option value="13">13</option>
    <option value="14">14</option> 
    <option value="15">15</option>
    <option value="16">16</option>
    <option value="17">17</option>
    <option value="18">18</option>
    <option value="19">19</option>
    <option value="20">20</option>
  </select><br>

  <div id="itemsContainer"></div><br>

  <label for="price">Price:</label>
  <input type="number" id="price" name="price"><br><br>

  <input type="submit" >
</form>

<script>
  function createInputBoxes() {
    var remainingCount = parseInt(document.getElementById("numOfItems").value);
    var itemsContainer = document.getElementById("itemsContainer");
    itemsContainer.innerHTML = ""; // Clear previous input boxes

    for (var i = 1; i <= remainingCount; i++) {
      var inputBox = document.createElement("input");
      inputBox.type = "text";
      inputBox.placeholder = "Additional Item Name " + i;
      inputBox.name = "additional_item_name" + i;
      itemsContainer.appendChild(inputBox);
      itemsContainer.appendChild(document.createElement("br"));
    }
  }
</script>
</div>
</body>
</html> 