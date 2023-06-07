<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Begin signup processing script
require_once 'UserManager.php';
require_once 'DBConnect.php';

// Initialize variables to empty strings
$errUsername = $errEmail = $errPassword = $errCredentialsTaken = "";
$username = $email = $password = "";

// Sanitize data inputs


// Sanitize data inputs
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = (filter_var($data, FILTER_SANITIZE_STRING));
    return $data;
}

// Check if submit button is clicked
if (isset($_POST['submit'])) {

    // Check if username is set
    if (empty($_POST["username"])) {
        $errUsername = "Username is required.";
    } else {
        $username = test_input($_POST["username"]);
    }

    // Check if email is set and valid
    if (empty($_POST["email"])) {
        $errEmail = "Email is required.";
    } else {
        $email = test_input($_POST["email"]);
        // Check if email address is in a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errEmail = "Please insert a valid email address.";
        }
    }

    // Check if password is set
    if (empty($_POST["password"])) {
        $errPassword = "A password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    // If there are no errors on the form and username and email are not already taken, redirect user to the login page
    if (empty($errUsername) && empty($errEmail) && empty($errPassword)) {
      
  
      $user = new UserManager($username, $email, $password);
      if ($user->userRegistration()) {
          session_start();
          $_SESSION['user'] = serialize($user);
          header("Location: index.php");
      } else {
          $errCredentialsTaken = "Username or email already taken";
      }
  }
}
// End signup processing script

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration</title>
    
    <!-- CSS -->
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.css">
    
     <!-- JS -->
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
     integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    </head>
    <body>
      <section id="style-form">
        <h2 style="text-align:center;">Register</h2>
          <div class="container">
              <div class="col-md-6">
            <div class="block">
                  <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                  <div class="form-group">
                    <input id="username" name="username" type="text" class="form-control" placeholder="Username" value=""></span>
                    <span class="required small"><?php echo $errUsername;?></span>
                  </div>
                  <div class="form-group">
                    <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="">
                    <span class="required small"><?php echo $errEmail;?></span>
                  </div>
                  <div class="form-group">
                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" value="">
                    <span class="required small"><?php echo $errPassword;?></span>
                  </div>
                    <button id="submit" name="submit" class="btn btn-default" type="submit">Sign-up</button>
                  </form>
                  <div class="alert alert-danger" style="color:red; margin-top: 5%">
                      <i class="glyphicon glyphicon-warning-sign" style="color:red"></i><?php echo $errCredentialsTaken; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
    </body>
</html>