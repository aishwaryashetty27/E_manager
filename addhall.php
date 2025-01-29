<?php 
session_start();

// Check if the user is logged in, redirect to the login page if not
if (!isset($_SESSION['username'])) {
  header('Location: ../starterFile/login.html');
  exit();
}
$loggedInUser = $_SESSION['username'];
require '../connection.php'; 
?>
<!DOCTYPE html>
<html>

<head>
  <title>Add Hall</title>
  <style>
    body {
      background-image: url("hall6.jpg");
      background-color: #f2f2f2;
      background-repeat: no-repeat;
      background-size: cover;
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

      background-color: #ffffff;
      padding: 60px;
      border-radius: 5px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
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
    <h2 <b style="color:white">Add Hall</b></h2>

    <form method="POST" enctype="multipart/form-data">
      <label for="hallName">Hall Name:</label>
      <input type="text" name="hallName" id="hallName" required><br>

      <label for="hallType">Hall Type:</label>
      <select name="hallType" id="hallType" required>
        <option value="AC">AC</option>
        <option value="NON AC">NON AC</option>
        <option value="AC and NON AC">AC and NON AC</option>
      </select><br>

      <label for="capacity">Capacity:</label>
      <input type="number" name="capacity" id="capacity" required><br>

      <label for="hallAddress">Hall Address:</label>
      <input type="text" name="hallAddress" id="hallAddress" required><br>

      <label for="description">Description:</label>
      <textarea name="description" id="description" required></textarea><br>

      <label for="hallImages">Hall Images:</label>
      <input type="file" name="hallImages" id="hallImages" multiple><br>

      <label for="location">Location:</label>
      <input type="text" name="location" id="location" required><br>

      <label for="price">price:</label>
      <input type="number" name="price" id="price" required><br>

      <input type="submit" value="Submit" title="Click here to submit">
    </form>
  </div>
</body>

<?php
if (isset($_POST['hallName'])) {
  $mail = $_GET['mail'];

  // Retrieve form data
  $hallName = $_POST['hallName'];
  $hallType = $_POST['hallType'];
  $capacity = $_POST['capacity'];
  $hallAddress = $_POST['hallAddress'];
  $description = $_POST['description'];
  $location = $_POST['location'];
  $price = $_POST['price'];

  $upload_dir = "../hallIMG/";
  if (!file_exists($upload_dir)) {
      mkdir($upload_dir, 0777, true);
  }

  $upload_file = $upload_dir . basename($_FILES["hallImages"]["name"]);
  if (move_uploaded_file($_FILES["hallImages"]["tmp_name"], $upload_file)) {
      // Insert the file information into the "files" table in the database
      $sql = "INSERT INTO halls (gmail, hall_name, hall_address, hall_loc, hall_type, hall_dis, seating_capacity, hall_price, hall_img)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssssiis", $mail, $hallName, $hallAddress, $location, $hallType, $description, $capacity, $price, $upload_file);

      if ($stmt->execute()) {
          echo "Hall added successfully!";
      } else {
          echo "Error: " . $stmt->error;
      }
  } else {
      echo "Error uploading file.";
  }

  $stmt->close();
  $conn->close();
}
?>

</html>
