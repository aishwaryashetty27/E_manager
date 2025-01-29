<?php require '../connection.php'; 
// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = sanitizeInput($conn, $_POST['userType']);
    $userName = sanitizeInput($conn, $_POST['userName']);
    $phoneNumber = sanitizeInput($conn, $_POST['phoneNumber']);
    $gmail = sanitizeInput($conn, $_POST['gmail']);
    $password = hashPassword($_POST['password']);

    
if ($userType === 'manager') {
        // Insert into manager table
        $sql = "INSERT INTO managers (manager_name, manager_phoneNumber, manager_password, gmail) VALUES ('$userName', '$phoneNumber',  '$password', '$gmail')";
    } elseif ($userType === 'user') {
        // Insert into user table
        $sql = "INSERT INTO users (user_name, user_phoneNumber, user_password, gmail) VALUES ('$userName', '$phoneNumber', '$password', '$gmail')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
