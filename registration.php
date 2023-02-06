<?php
session_start();
if (isset($_SESSION["user"])) {
  header ("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRetype = $_POST["retype_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRetype)) {
              array_push($errors,"Fill-up all required details");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              array_push($errors, "Email is not valid");
            }
            if (strlen($password)<8) {
              array_push($errors, "Password must be at least 8 characters");
            }
            if ($password !== $passwordRetype) {
              array_push($errors, "Password does not match");
            }

            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
              array_push($errors, "Email already exist!");
            }

            if (count($errors) > 0) {
              foreach ($errors as $error) {
                  echo "<div class='alert alert-danger'>$error</div>";
              }
            }else {
              
              $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
              $stmt = mysqli_stmt_init ($conn);
              $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
              if ($prepareStmt) {
                  mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                  mysqli_stmt_execute($stmt);
                  echo "<div class='alert alert-success'>You are registered successfully.</div>";
              }else {
                  die("Something went wrong");
              }
            }

        }
        ?>
        <h1><b>Welcome</b> World!</h1>
        <form action="registration.php" method="post">
              <h2>Sign-up Form</h2>
              <hr>
              <div class="form-group">
                  <input type="text" class="form-control" name="fullname" placeholder="Full Name :">
              </div>
              <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Email :">
              </div>
              <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Password :">
              </div>
              <div class="form-group">
                  <input type="text" class="form-control" name="retype_password" placeholder="Retype Password :">
              </div>

              <div class="form-btn" >
                  <input type="submit" class="btn btn-secondary w-100" value="Submit" name="submit">
              </div>
            
        </form>
        <div class="login">
                <p>Already have an account? </p>
                <a href="login.php" class="btn btn-info w-100">Log in here</a>  
        </div>
    </div>
  
</body>
</html>