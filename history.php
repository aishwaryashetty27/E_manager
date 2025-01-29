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

// Fetch booking history for the logged-in user
$sql = "SELECT hall_name, bookDate, status FROM hallbook WHERE gmail = '$loggedInUser'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Booking History</title>
  <style>
    body {
      background-image: url("hall6.jpg");
      background-size: cover;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 50px;
      color: #ffffff;
    }

    .center {
        text-align: center;
    }

    .booking-history {
      width: 500px;
      margin: 0 auto;
      background-color: rgba(255, 255, 255, 0.3);
      padding: 20px;
      border-radius: 5px;
    }

    table {
      width: 100%;
      background-color: white;
    }

    table th,
    table td {
      padding: 10px;
      text-align: left;
      color: black;
    }

    table th {
      background-color: #4CAF50;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="center">
    <h2>Booking History</h2>
  </div>

  <div class="booking-history">
    <?php
    if ($result->num_rows > 0) {
        echo '<table>
                <tr>
                  <th>Hall Name</th>
                  <th>Booking Date</th>
                  <th>Status</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['hall_name'] . '</td>
                    <td>' . $row['bookDate'] . '</td>
                    <td>' . $row['status'] . '</td>
                  </tr>';
        }

        echo '</table>';
    } else {
        echo "No booking history found.";
    }
    ?>
  </div>
</body>
</html>
