<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "emanager";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$menu = $_GET['menu_name'];

// SQL query to retrieve data from the "catering" table
$sql = "SELECT * FROM catering WHERE menu_name='$menu'";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $menuName = $_POST['menu_name'];
  $numOfItems = $_POST['num_of_items'];
  $price = $_POST['price'];
  $additionalItemsCount = $_POST['additional_items_count'];

  // SQL query to update data in the "catering" table
  $sql = "UPDATE catering SET menu_name='$menuName', price='$price'";
  for ($i = 1; $i <= $numOfItems; $i++) {
    $itemName = $_POST['item_name' . $i];
    $sql .= ", item_name" . $i . "='$itemName'";
  }

  // Update additional item names
  for ($i = 1; $i <= $additionalItemsCount; $i++) {
    $additionalItemName = $_POST['item_name' . $i];
    $sql .= ", item_name" . ($numOfItems + $i) . "='$additionalItemName'";
  }

  $sql .= " WHERE menu_name='$menuName'";

  // Execute the SQL query
  if ($conn->query($sql) === TRUE) {
    echo "Menu updated successfully!";
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
  <title>Display Catering Data</title>
  <style>
    body {
      background-image: url("hall6.jpg");
      background-color: #f2f2f2;
      background-size: cover;
      font-family: Arial, sans-serif;
      margin: 10;
      padding: 20px;
    }

    h2 {
      color: black;
    }

    form {
      background-color:white;
      background-size:cover;
      padding: 20px;
      border-radius: 5px;
    
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin-bottom: 10px;
    }

    label {
      font-weight: bold;
      color: #333333;
    }

    input[type="text"],
    select {
      padding: 5px ;
      width: 250px;
    }

    button[type="submit"] {
      background-color: #4CAF50;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
     cursor: pointer;
      font-weight: bold;
    }

    button[type="submit"]:hover {
      background-color: #45a049;
    }
  </style>
  <script>
    function createInputBoxes() {
      var remainingCount = document.getElementById("remainingCount").value;
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
</head>
<body>
  <h2>Catering Data</h2>
  <form method="post" action="">
    <ul>
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<li>";

          // Display the menu name
          echo "<label>Menu Name : </label>";
          echo "<input type='text' id='menuName' name='menu_name' value='" . $row['menu_name'] . "'><br>";

          // Display the item names if not null
          $numOfItems = 0; // Initialize item count
          for ($i = 1; $i <= 20; $i++) {
            $itemName = $row['item_name' . $i];
            if ($itemName !== null) {
              $numOfItems++; // Increment item count
              echo "<label>Item Name $numOfItems: </label>";
              echo "<input type='text' name='item_name" . $numOfItems . "' value='$itemName'><br>";
            }
          }

          // Display the number of items
          echo "<label>Number of Items: </label>";
          echo "<input type='text' name='num_of_items' value='$numOfItems' readonly><br>"; // Add the readonly attribute

          // Calculate the remaining count
          $remainingCount = 20 - $numOfItems;

          // Display the dropdown for remaining count
          echo "<label>Additional Items : </label>";
          echo "<select id='remainingCount' onchange='createInputBoxes()' name='additional_items_count'>";
          for ($j = 1; $j <= $remainingCount; $j++) {
            echo "<option value='$j'>$j</option>";
          }
          echo "</select><br>";

          // Display the input boxes container
          echo "<div id='itemsContainer'></div><br>";

          // Display the price
          echo "<label>Price: </label>";
          echo "<input type='text' id='price' name='price' value='" . $row['price'] . "'><br>";

          echo "</li>";
        }
      } else {
        echo "<li>No data found.</li>";
      }
      ?>
    </ul>
    <button type="submit">Update Table</button>
  </form>
</body>
</html>
