<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body {
      background-image: url("hall6.jpg");
      background-size: cover;
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.8);
      border-radius: 5px;
      margin-top: 100px;
      border: 2px solid #ccc;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
    }

    form {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input {
      width: 100%;
      padding: 5px;
      border-radius: 3px;
      border: 1px solid #ccc;
    }

    .password-toggle {
      position: absolute;
      display: inline-block;
      width: 400px;
    }

    .password-toggle .toggle-icon {
      position: absolute;
      top: 14.90px;
      right: -3px;
      
      transform: translateY(-50%);
      cursor: pointer;
    }

    input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    p {
      text-align: center;
      margin-top: 20px;
    }
  </style>
  <script>
    function togglePasswordVisibility() {
      var passwordInput = document.getElementById("password");
      var toggleIcon = document.getElementById("toggle-icon");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.add("fa-eye-slash");
        toggleIcon.classList.remove("fa-eye");
      } else {
        passwordInput.type = "password";
        toggleIcon.classList.add("fa-eye");
        toggleIcon.classList.remove("fa-eye-slash");
      }
    }
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <form method="POST" >
      <label for="username">Gmail:</label>
      <input type="text" name="username" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid Gmail address" required><br><br>
      
      <label for="password">Password:</label>
      <div class="password-toggle">
        <input type="password" name="password" id="password" title="Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, one digit, and one special character." required>
        <i id="toggle-icon" class="fas fa-eye toggle-icon" onclick="togglePasswordVisibility()"></i>
      </div><br><br>


      <input type="submit" value="Login">
    </form>
    <p>Don't have an account yet? <a href="register.html">Sign up Now</a></p>

<?php require '../connection.php'; ?>
<?php
session_start();


// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userName = sanitizeInput($conn, $_POST['username']);
    $password = $_POST['password'];
    $_SESSION['username'] = $userName;
    // Check if user exists in admin table
    $sql = "SELECT * FROM admin WHERE gmail = '$userName'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['admin_password'])) {
            // Login successful, redirect to admin.php
            header("Location: ../adminFile/admin.php");
            exit;
        }
    }

    // Check if user exists in manager table
    $sql = "SELECT * FROM managers WHERE gmail = '$userName'";
    $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          if ($row['status']=="Suspend") {
            echo "<p>Your Account is suspended";
            exit;
          }else{
            if (password_verify($password, $row['manager_password'])) {
              // Login successful, redirect to manager.php
              header("Location: ../managerFile/manager.php");
              exit;
          }
          }
      }

    // Check if user exists in user table
    $sql = "SELECT * FROM users WHERE gmail = '$userName'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if ($row['status']=="Suspend") {
        echo "<p>Your Account is suspended";
        echo $row['status'];
        echo $row['gmail'];
        exit;
      }else{
        if (password_verify($password, $row['user_password'])) {
          // Login successful, redirect to manager.php
          header("Location: ../userFile/user.php");
          exit;
      }
      }
  }

    // Login failed, display error message
    echo "<p>Invalid username or password</p>";

    $conn->close();
}
?>
  </div>
</body>
</html>