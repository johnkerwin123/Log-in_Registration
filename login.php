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
  <title>Log in Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>
<body>
      <div class="container">
          <?php
          if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: index.php");
                    die();
                }else {
                    echo "<div class='alert alert-danger'>Password does not match!</div>";
                }
            }else {
                echo "<div class='alert alert-danger'>Email does not exist!</div>";
            }
          }
          ?>
          <div class="header">
              <h1><b>Welcome</b> World!</h1>
              <h4>Enter your account :</h4>
              <hr>
          </div>
          <form action="login.php" method="post">
              <div class="form-group">
                  <input type="email" placeholder="Enter Email :" name="email" class="form-control">
              </div>
              <div class="form-group">
                  <input type="password" placeholder="Enter Password :" name="password" class="form-control">
              </div>
              <div class="form-btn">
                  <input type="submit" value="Log in" name="login" class="btn btn-secondary w-100">  
              </div>
          </form>
          <div class="register">
                <p>No account yet?</p>
                <a href="registration.php" class="btn btn-info w-100">Sign up here !!</a> 
          </div>
      </div>
</body>
</html>