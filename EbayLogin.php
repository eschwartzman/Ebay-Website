<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="Final_css.css">
</head>

<body>
    <form method="post">
       New User Name:<br>
       <input type="text" name="username"><br><br>
       New User Password:<br>
       <input type="password" name="password"><br><br>
       <input type="submit" name="submit" value="Sign Up"><br><br>
       Name:<br>
       <input type="text" name="username1"><br><br>
       Password:<br>
       <input type="password" name="password1"><br><br>
       <input type="submit" name="submit1" value="Log In"><br><br>
   </form>

   <?php
   require 'dbase.php';
   session_start();
   if(isset($_POST['submit'])){
     $userName = $mysqli->real_escape_string($_POST['username']);
     $password = $mysqli->real_escape_string($_POST['password']);
     $nameUsed = False;
     if((!preg_match('/^[\w_\-]+$/',$userName)) || (!preg_match('/^[\w_\-]+$/',$password))){
        echo "Invalid Username or Password";
        exit;
    }
    //check if username already exists
    $stmt = $mysqli->prepare("SELECT username from users where username=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('s', $userName);
    $stmt->execute();       
    //if already in table prompt for a new one
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        if (sizeof($row["username"])>0){ 
            $nameUsed= True;
            echo "Username Taken Please Enter a New One";}
        }
        //allow the name/password to be added to table
        if(!$nameUsed){
            echo "Thanks for signing up!";
            $crypt_pass = crypt($password);
            $add_name = $mysqli->prepare("insert into users (username, password)
                values('$userName', '$crypt_pass')");
            if(!$add_name){
                printf("Query Failed: %s\n", $mysqli->error);
                exit;
            }
            $add_name->execute();
            $add_name->close();
        }
        
        $stmt->close();
    }

    if(isset($_POST['submit1'])){
        $userName = $mysqli->real_escape_string($_POST['username1']);
        $password = $mysqli->real_escape_string($_POST['password1']);


// Use a prepared statement
        $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");

// Bind the parameter
        $stmt->bind_param('s', $userName);
        $userName = $_POST['username1'];
        $stmt->execute();

// Bind the results
        $stmt->bind_result($cnt, $user_id, $pwd_hash);
        $stmt->fetch();
        $pwd_guess = $_POST['password1'];

// Compare the submitted password to the actual password hash
        if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
    // Login succeeded!
            $_SESSION['loggedIn']= True;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['userAccount']= $userName;
            $_SESSION['token'] = substr(md5(rand()), 0, 10);
            header( "Location: Final_main.php" );
        }else{
            echo "Try Again";
        }
    }
    ?>
</body>
</html>