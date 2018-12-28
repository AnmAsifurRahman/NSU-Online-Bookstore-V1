<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.html");
  exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM admin_info WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: index.html");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/uikit.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" type="image/x-icon" href="img/icon.jpg">
  <title>NSU Online Bookstore</title>
</head>
<body>
 <!-- Navigation -->
 <nav class="navbar navbar-dark navbar-expand-md" uk-sticky="top: 200; animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up">
   <div class="container">
     <a href="adminlogin.php" class="navbar-brand">NSU Online Bookstore</a>
     <button class="navbar-toggler navbar-toggler-right" data-toggle="collapse" data-target="#navbarNav">
       <span class="navbar-toggler-icon"></span>
     </button>
     <div id="navbarNav" class="collapse navbar-collapse">
       <ul class="navbar-nav ml-auto">

         <li class="nav-item">
           <a class="nav-link" href="signup.html">Signup</a>
         </li>
       </ul>
      </div>
    </div>
  </nav>
  <!-- Carousel Slider -->
  <section id="showcase" class="bg-dark">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-slide-to="0" data-target="#myCarousel" class="active"></li>
        <li data-slide-to="1" data-target="#myCarousel"></li>
        <li data-slide-to="2" data-target="#myCarousel"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item carousel-img-1 active">
          <div class="container">
            <div class="carousel-caption text-right mb-5 pb-5">
              <h2 class="display-4">The largest NSU Online Bookstore</h2>
              <p class="font-italic" >Buy Book, Gain Knoladge, Save Time!</p>
              <br/>
              <br/>
              <br/>

            </div>
          </div>
        </div>
        <div class="carousel-item carousel-img-2">
          <div class="container">
            <div class="carousel-caption mb-5 pb-5 text-right">
              <h2 class="display-4">20% Cash Back</h2>
              <p class="lead my-4">for Signup of the student</p>
              <br/>
              <br/>
            </div>
          </div>
        </div>
        <div class="carousel-item carousel-img-3">
          <div class="container">
            <div class="carousel-caption text-left mb-5 pb-5">
              <h2 class="display-4">Free Home Delivery </h2>
              <p class="lead my-4">for shop 1000TK or more!</p>
              <br/>
              <br/>
            </div>
          </div>
        </div>
      </div>
      <a href="#myCarousel" class="carousel-control-prev pb-5" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </a>
      <a href="#myCarousel" class="carousel-control-next pb-5" data-slide="next">
        <span class="carousel-control-next-icon"></span>
      </a>
    </div>
  </section>





<!-- login -->
  <section id="loginpart" class="py-5 bg-dark text-center text-light">
    <div class="container">
      <div class="row">
        <div class="col">
          <h2> Login</h2>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-inline justify-content-center mt-4" method="POST">
             <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label class="sr-only" for="name">username</label>
            <input type="text" placeholder="Enter Username" name = "username" class="form-control m-2" value="<?php echo $username; ?>" >
            <span class="help-block"><?php echo $username_err; ?></span>
           </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label class="sr-only" for="password">Password</label>
            <input type="password" name="password" placeholder="Enter Password" class="form-control m-2">
            <span class= "help-block"><?php echo $password_err; ?></span>
           </div>
            <input type="submit" class="btn btn-primary m-2" value="Login">
          </form>
        </div>
      </div>
    </div>
  </section>
  <!-- Footer -->
  <footer id="copyright" class="py-3 text-light text-center">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="lead mb-0">Copyright 2018 © NSU Online Bookstore</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/uikit.min.js"></script>
  <script src="js/uikit-icons.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
