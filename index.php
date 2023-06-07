<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'UserManager.php';

//begin login processing script


//initialize variable to empty strings and login variable to false
$errUsernameEmail = $errPassword = $errCredentials = "";

$usernameEmail = $password = "";

//sanitize data inputs   
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = (filter_var($data, FILTER_SANITIZE_STRING));
    return $data;
}

// Check if submit button is entered
if (isset($_POST['submit'])) {

    // Check if username or email is set 
    if (empty($_POST["usernameEmail"])) {
        $errUsernameEmail = "Your username or email is required.";
    } else {
        $username = test_input($_POST['usernameEmail']);
        $email = test_input($_POST['usernameEmail']);
    }

    // Check if password is set
    if (empty($_POST["password"])) {
        $errPassword = "Your password is required";
    } else {
        $password = test_input($_POST['password']);
    }

    // If there are no errors on the form and username or email match the password, redirect user to dashboard
    if (!$errUsernameEmail && !$errPassword) {
      $user = new UserManager($username, $email, $password);
        if ($user->login()) {
            session_start();
            $_SESSION['user'] = serialize($user);
            header("Location: home.php");
        } else {
            $errCredentials = "Wrong credentials!";
        }
    }
}
//end login processing script

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    
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
        <h2 style="text-align:center;">Login</h2>
          <div class="container">
              <div class="col-md-6">
            <div class="block">
                  <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                  <div class="form-group">
                    <input id="usernameEmail" name="usernameEmail" type="text" class="form-control" placeholder="Username or Email" value=""></span>
                    <span class="required small"><?php echo $errUsernameEmail;?></span>
                  </div>
                  <div class="form-group">
                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" value="">
                    <span class="required small"><?php echo $errPassword;?></span>
                  </div>
                    <button id="submit" name="submit" class="btn btn-default" type="submit">Login</button>
                  </form>
                  <div class="alert alert-danger" style="color:red; margin-top: 5%">
                      <i class="glyphicon glyphicon-warning-sign" style="color:red"></i><?php echo $errCredentials . "</br>"; ?>
                      <a href="signup.php">You don't have an account? Click here to signup.</a> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
    </body>
</html>