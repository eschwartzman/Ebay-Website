<!DOCTYPE html>
<html>
<head>
    <title>My Info</title>
    <link rel="stylesheet" href="Final_css.css">
</head>

<body>

    <?php
    session_start();
    addInfo();
    ?>

    <?php
    function addInfo(){
       ?>
       <form method="post">
        <p>Try Adding info About Yourself:</p>
        Money<input type="number" name="mon_" ><br>
        About Me: <textarea name="about"></textarea><br> 
        <input type="submit" name="set_about" value="Update Profile">  
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>

    <?php
    require 'dbase.php';
    if(isset($_POST['set_about'])){
 	//check for CSRF
      if($_SESSION['token'] !== $_POST['token']){
          echo "error";
          die("Request forgery detected");
      }
      $about = $mysqli->real_escape_string($_POST['about']);

      $money = $mysqli->real_escape_string($_POST['mon_']);

      $id = $_SESSION['userAccount'];
      // echo "got past the stuff";
      $stmt = $mysqli->prepare("UPDATE users set about=?, mon=? where username=?;");
      if(!$stmt){
       printf("Query Prep Failed: %s\n", $mysqli->error);
       exit;}
       $stmt->bind_param('sis', $about, $money, $id);
       $stmt->execute();
       $stmt->close();
       header( "Location: Final_my_home.php" );
   }
   ?>
   <?php
}?>
</body>
</html>