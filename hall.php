<?php 
session_start();

// Check if the user is logged in, redirect to the login page if not
if (!isset($_SESSION['username'])) {
  header('Location: ../starterFile/login.html');
  exit();
}
$loggedInUser = $_SESSION['username'];
require '../connection.php'; 


// Retrieve the hall_name parameter from the URL
$hallName = $_GET['hall_name'];
$status = "REGISTERED";
// Prepare and execute the SQL query to fetch bookings for the specified hall
// $stmt = $conn->prepare("SELECT * FROM hallBook WHERE hall_name = ? AND status = ?");
// $stmt->bind_param("ss", $hallName, $status);
$stmt = $conn->prepare("SELECT * FROM hallBook WHERE hall_name = ?");
$stmt->bind_param("s", $hallName);

$stmt->execute();

// Fetch the result
$result = $stmt->get_result();
?>
  <!-- Process the form submission -->
  <?php
  if (isset($_POST['accept'])) {
    $bookingId = $_POST['booking_id'];

    // Update the status of the booking to "BOOKED"
    $updateStmt = $conn->prepare("UPDATE hallBook SET status = 'BOOKED' WHERE booking_id = ?");
    $updateStmt->bind_param("i", $bookingId);

    if ($updateStmt->execute()) {
      // Refresh the page to reflect the updated status
      header("Location: hall.php?hall_name=$hallName");
      exit();
    } else {
      echo "Error updating booking status: " . $updateStmt->error;
    }

    $updateStmt->close();
  }
  elseif (isset($_POST['cancel'])) {
    $bookingId = $_POST['booking_id'];

    // Update the status of the booking to "BOOKED"
    $updateStmt = $conn->prepare("UPDATE hallBook SET status = 'CANCELLED' WHERE booking_id = ?");
    $updateStmt->bind_param("i", $bookingId);

    if ($updateStmt->execute()) {
      // Refresh the page to reflect the updated status
      header("Location: hall.php?hall_name=$hallName");
      exit();
    } else {
      echo "Error updating booking status: " . $updateStmt->error;
    }

    $updateStmt->close();
  }
  elseif (isset($_POST['pending'])) {
    $bookingId = $_POST['booking_id'];

    // Update the status of the booking to "BOOKED"
    $updateStmt = $conn->prepare("UPDATE hallBook SET status = 'PENDING' WHERE booking_id = ?");
    $updateStmt->bind_param("i", $bookingId);

    if ($updateStmt->execute()) {
      // Refresh the page to reflect the updated status
      header("Location: hall.php?hall_name=$hallName");
      exit();
    } else {
      echo "Error updating booking status: " . $updateStmt->error;
    }

    $updateStmt->close();
  }
  elseif (isset($_POST['delete'])) {
    $bookingId = $_POST['booking_id'];

    // Update the status of the booking to "BOOKED"
    $deleteStmt = $conn->prepare("DELETE FROM hallBook WHERE booking_id = ?");
    $deleteStmt->bind_param("i", $bookingId);
    

    if ($deleteStmt->execute()) {
      // Refresh the page to reflect the updated status
      header("Location: hall.php?hall_name=$hallName");
      exit();
    } else {
      echo "Error updating booking status: " . $updateStmt->error;
    }

    $updateStmt->close();
  }
  // Close the statement and connection
  $stmt->close();
  $conn->close();
  ?>

<!DOCTYPE html>
<html>
<head>
  <title>Hall Page</title>
  <style>
    body {
      background-image: url("hall6.jpg");
      background-size: cover;
      font-family: Arial, sans-serif;
      margin: 0;
      color: #ffffff;
    }

    h2 {
      font-weight: bold;
      font-size: 30px;
      text-transform: uppercase;
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ffffff;
    }

    th {
      background-color: #333333;
      color: #ffffff;
      font-weight: bold;
    }

    form {
      margin-top: 20px;
    }

    button {
      background-color: #4CAF50;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }

    p {
      text-align: center;
      margin-top: 20px;
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
  <h2>Hall: <?php echo $hallName; ?></h2>

  <!-- Display the bookings in a table -->
  <?php if ($result->num_rows > 0): ?>
    <h3>Bookings:</h3>
    <table>
      <thead>
        <tr>
          <th>User Name</th>
          <th>User Phone Number</th>
          <th>Booking Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['user_name']; ?></td>
            <td><?php echo $row['user_number']; ?></td>
            <td><?php echo $row['bookDate']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
              <?php //if ($row['status'] !== 'BOOKED'): ?>
                <form method="POST">
                  <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                  <button type="submit" name="accept">ACCEPT</button>
                  <button type="submit" name="cancel">CANCEL</button>
                  <button type="submit" name="pending">PENDING</button>
                  <button type="submit" name="delete">DELETE</button>
                </form>
              <?php //endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No bookings found for this hall.</p>
  <?php endif; ?>



<!-- Rest of your HTML content -->

</body>
</html>
