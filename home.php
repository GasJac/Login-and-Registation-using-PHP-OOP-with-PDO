<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'DBConnect.php';
require_once 'UserManager.php';
require_once 'index.php';

$user = new UserManager($username, $email, $password);

//verify if the user as passed the login process

  if(!$user->isLogged()){
    header("Location: index.php");
  }
//store users informations
$dbConnect = new DBConnect(DB_SERVER, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
$connect = $dbConnect->getConnection();
$userId = $_SESSION['user_session'];
$stmt = $connect->prepare("SELECT * FROM users WHERE id=:userId");
$stmt->execute(array(":userId"=>$userId));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);


//logoff on form submit
$user->logoffAction($user);
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    
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
        <h2 style="text-align:center;">Glad to see you <?php echo($userRow['username']);?></h2>
        <!-- Destroy user session after clicking logoff button -->
        <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <button id="logOff" name="logOff" class="btn btn-default btn-logoff" type="submit" style="cursor: pointer;">Log off</button>
        </form>
      </section>
    </body>
</html>