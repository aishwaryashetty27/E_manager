<?php require '../connection.php'; ?>

<?php
// Retrieve the hall_name parameter from the URL
$hallName = $_GET['hall_name'];
$user_mail = $_GET['user_mail'];

// Check if the form is submitted
if (isset($_POST['book'])) {
  // Retrieve the user details from the users table based on the user_mail
  $userStmt = $conn->prepare("SELECT user_name, user_phoneNumber FROM users WHERE gmail = ?");
  $userStmt->bind_param("s", $user_mail);
  $userStmt->execute();
  $userResult = $userStmt->get_result();

  if ($userResult->num_rows > 0) {
    $userRow = $userResult->fetch_assoc();
    $user_name = $userRow['user_name'];
    $user_number = $userRow['user_phoneNumber'];

    // Retrieve the selected booking date
    $bookingDate = $_POST['booking_date'];
    $eventType = $_POST['event_type'];
    $menu_name = $_POST['catering_menu'];

    // Insert the booking details into the hallBook table
    $bookStmt = $conn->prepare("INSERT INTO hallBook (gmail, user_name, user_number, hall_name, bookDate, status, menu_name,event_type) VALUES (?, ?, ?, ?, ?, 'PENDING',?,?)");
    $bookStmt->bind_param("sssssss", $user_mail, $user_name, $user_number, $hallName, $bookingDate,$menu_name,$eventType);

    if ($bookStmt->execute()) {
      echo "Booking successful!";
    } else {
      echo "Error: " . $bookStmt->error;
    }

    $bookStmt->close();
  } else {
    echo "User not found.";
  }

  $userStmt->close();
}

// Prepare and execute the SQL query with a join on managers table
$stmt = $conn->prepare("SELECT h.*, m.manager_name, m.manager_phoneNumber, m.gmail FROM halls h JOIN managers m ON h.gmail = m.gmail WHERE h.hall_name = ?");
$stmt->bind_param("s", $hallName);
$stmt->execute();

// Fetch the result
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // Display the hall information
  while ($row = $result->fetch_assoc()) {
    // Display the hall image
    $imagePath = $row['hall_img'];
?>
    <!DOCTYPE html>
    <html>
    <head>
      <title>Hall Booking</title>
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

    /* Add CSS styles for right-aligned elements */
    .right-align {
      float: right;
    }
    .left-align {
      float: left;
    }

        body {
          background-image:url("hall6.jpg");
          background-size: auto 100%;
          font-family: Arial, sans-serif;
          margin: 0;

          color: #ffffff;
        }

        .booking-container {
          background-color: rgba(0, 0, 0, 0.7);
          padding: 20px;
          max-width: 500px;
          margin: 0 auto;
          display: flex; /* Add this line to enable flex display */
          flex-direction: column; /* Add this line to display elements in a column */
          align-items: flex-start;
        }

        .booking-container h2 {
          text-align: center;
          margin-bottom: 20px;
        }

        .booking-container label {
          display: block;
          margin-bottom: 10px;
          font-size: 18px;
        }
      
        

        .booking-container input[type="date"] {
          padding: 10px;
          width: 100%;
          border: none;
          border-radius: 5px;
          margin-bottom: 20px;
        }

        .booking-container input[type="submit"] {
          background-color: #4CAF50;
          color: #ffffff;
          border: none;
          padding: 10px 20px;
          font-size: 16px;
          border-radius: 5px;
          cursor: pointer;
        }

        .booking-container input[type="submit"]:hover {
          background-color: #45a049;
        }
        .hall-details-container {
          margin-top: 50px;
          text-align: center;
        }

        .hall-details-container h2 {
          margin-bottom: 20px;
        }

        .hall-details-container p {
          margin: 0;
        }

        .hall-details-container p span {
          font-weight: bold;
        }

        .cards-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

.card {
  position: relative;
}

.card input[type="checkbox"] {
  display: none;
}

.card label {
  display: block;
  cursor: pointer;
  background-color: black;
  padding: 10px;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.card label:hover {
  background-color: #e0e0e0;
}

.card .tooltip {
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  display: none;
  z-index: 1;
}

.card:hover .tooltip {
  display: block;
}

.card .tooltiptext {
  visibility: hidden;
  background-color: #333;
  color: #fff;
  text-align: center;
  padding: 5px;
  border-radius: 5px;
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  white-space: nowrap;
}

.card:hover .tooltiptext {
  visibility: visible;
}

.card input[type="radio"] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.card label:before {
  content: "";
  display: inline-block;
  vertical-align: middle;
  width: 20px;
  height: 20px;
  margin-right: 10px;
  border: 2px solid #ccc;
  border-radius: 50%;
  background-color: transparent;
}

.card input[type="radio"]:checked + label:before {
  background-color: #4CAF50;
}
</style>
      </head>
      <body>
        <!-- Navigation Bar -->
    <ul class="navbar">
    <li class="left-align"><a href="user.php">Home</a></li>
    <li class="left-align"><a href="../starterFile/logout.php">Logout</a></li>
  </ul>

    <div class="booking-container">
    <h2>Book Hall: <?php echo $hallName ;?></h2><hr>

    <p>Hall Type:<?php echo $row['hall_type'] ;?></p>
    <p>Capacity:<?php echo$row['seating_capacity'] ;?></p>
    <p>Hall Address:<?php echo$row['hall_address'] ;?></p>
    <p>Description:<?php echo$row['hall_dis'] ;?></p>
    <p>Location:<?php echo$row['hall_loc'] ;?></p>
    <p>Price:<?php echo$row['hall_price'];?></p>
    <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
    <label for="hall_image" style="margin-right: 10px;">Hall Image:</label>
    <?php echo "<img src='" . $row['hall_img'] . "' width='200' height='200'>"; ?><hr>
  </div>

    <h2>Manager Details:</h2>
    <p>Manager Name:<?php echo$row['manager_name'];?></p>
    <p>Manager Phone Number:<?php echo$row['manager_phoneNumber'];?></p>
    <p>Manager Gmail:<?php echo $row['gmail'] ;?></p><hr>
    <!-- <form method="POST">

    <label for="booking_date">Booking Date:</label>
    <input type="date" id="booking_date" name="booking_date" required>
    <input type="submit" name="book" value="BOOK">

  </form> -->
  

  <div class="booking-container">
  <h2>Book Hall: <?php echo $hallName; ?></h2>
  <form method="POST">
    <label for="booking_date">Booking Date:</label>
    <input type="date" id="booking_date" name="booking_date" required>
    <label for="event_type">Event Type:</label>
        <select id="event_type" name="event_type" required>
          <option value="wedding">Wedding</option>
          <option value="reception">Reception</option>
          <option value="birthday">Birthday Party</option>
          <option value="party">Party</option>
          <option value="engagement">Engagement</option>
          <option value="other">Other</option>
        </select>
        <input type="text" id="other_event_type" name="other_event_type" placeholder="Enter event type" style="display: none;">
        <script>
          const eventTypeDropdown = document.getElementById('event_type');
          const otherEventTypeInput = document.getElementById('other_event_type');

          // Add event listener to check if "Other" is selected
          eventTypeDropdown.addEventListener('change', function() {
            if (eventTypeDropdown.value === 'other') {
              otherEventTypeInput.style.display = 'block';
              otherEventTypeInput.setAttribute('required', 'required');
            } else {
              otherEventTypeInput.style.display = 'none';
              otherEventTypeInput.removeAttribute('required');
            }
          });
          </script>
        <label for="catering_menu">Catering Menu:</label>
    <div class="cards-container">
      <?php
      // Fetch values from the catering table
      $cateringStmt = $conn->prepare("SELECT c.* FROM catering c, halls h WHERE c.gmail=h.gmail and h.hall_name='$hallName'");
     $cateringStmt->execute();
      $cateringResult = $cateringStmt->get_result();

      if ($cateringResult->num_rows > 0) {
        while ($cateringRow = $cateringResult->fetch_assoc()) {
          $menuName = $cateringRow['menu_name'];
          echo '<div class="card">
                  <input type="radio" id="' . $menuName . '" name="catering_menu" value="' . $menuName . '">
                  <label for="' . $menuName . '">' . $menuName . '- RS:'.$cateringRow['price'].'</label>
                  <div class="tooltip">
                    <span class="tooltiptext">';
          
          // Loop through the item_names and display them
          for ($i = 1; $i <= 20; $i++) {
            $itemName = $cateringRow['item_name' . $i];
            if (!empty($itemName)) {
              echo $itemName . '<br>';
            }
          }

          echo '</span>
                </div>
              </div>';
        }
      } else {
        echo "No catering options available.";
      }

      $cateringStmt->close();
      ?>
    </div>
    <input type="submit" name="book" value="BOOK">
  </form>
</div>



</div>
<div class="manager-container">

</body>
</html>
<?php
  }
} else {
  echo "Hall not found.";
}

// Close the statement and connection
$stmt->close();
?>
