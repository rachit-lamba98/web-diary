<?php
    session_start();
    if(array_key_exists("logout", $_GET)){
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";
    }
    // else if((array_key_exists("id", $_SESSION) AND $_SESSION["id"]) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE["id"])){
    //     header("Location: diaryloggedin.php");
    // }
    $errmsg = "";
    if(array_key_exists("submit", $_POST)){
        if($_POST["email"] == ""){
            $errmsg .= "An email is required.<br>";
        }
        if($_POST["password"]==""){
            $errmsg .= "A password is required.<br>";
        }
        if($_POST["username"] == ""){
            $errmsg .= "An username is required.";
        }
        if($errmsg != ""){
            $errmsg = "<p>There were the following errors:</p>".$errmsg;
        }
        else{
            $link = mysqli_connect("localhost","id6563907_jaiguruji","12345","id6563907_users");
            if(mysqli_connect_error()){
                die("Database connection error.");
            }
            if($_POST["signUp"] == '1'){
                 $email = $_POST["email"];
                 $username = $_POST["username"];
                 $email = mysqli_real_escape_string($link, $email);
                 $result2 = mysqli_query($link, "SELECT id FROM userDetails WHERE username = '$username'");
                 $result = mysqli_query($link, "SELECT id FROM userDetails WHERE email = '$email'");
                 if(mysqli_num_rows($result)!= 0){
                     $errmsg = "<p>This email is already registered.</p>";
                 }
                 if(mysqli_num_rows($result2)!= 0){
                     $errmsg = "<p>This username is already taken.</p>";
                 }
                 else{
                     $password = $_POST["password"];
                     $query = "INSERT INTO userDetails (email, username, password) VALUES('$email', '$username' '$password')";
                     if(mysqli_query($link, $query)){
                         $password = md5(md5(mysqli_insert_id($link)).$password);
                         $query = "UPDATE userDetails SET password = '$password' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
                         mysqli_query($link, $query);
                         $_SESSION["id"] = mysqli_insert_id($link);
                         if($_POST["stayLoggedIn"]=='1'){
                             setcookie("id", mysqli_insert_id($link), time()+60*60);
                         }
                         // header("Location: foobar.php");
                         echo("Successful! (Add header location at line 46)");
                     }
                     else{
                         $errmsg = "There was an error while signing you up, please try again later.";
                     }
                 }
            }
            else{
                $email = $_POST["email"];
                $email = mysqli_real_escape_string($link, $email);
                $password = $_POST["password"];
                $query = "SELECT * FROM userDetails WHERE email = '$email'";
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_array($result);
                if(isset($row)){
                    $hashPassword = md5(md5($row["id"]).$password);
                    if($hashPassword == $row["password"]){
                        $_SESSION["id"] = $row["id"];
                        if($_POST["stayLoggedIn"]=='1'){
                            setcookie("id", $row["id"], time()+60*60);
                        }
                        header("Location: memegenerator.php");
                    }
                    else{
                        $errmsg = "E-mail or password is incorrect.";
                    }
                }
                else{
                    $errmsg = "E-mail or password is incorrect.";
                }
            }
        }
    }
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro:700" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Login/Signup</title>
  </head>
  <body>
  <div class="container">
    <div class="form-container">
        <form class="signUp" method="post">
            <h3>Create Your Account</h3>
            <p>Just enter your email address<br>
    and your password for join.
            </p>
            <input class="w100" type="email" name = "email" placeholder="Insert eMail" autocomplete='off' />
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Insert Password"  >
            <input type="password" name="confirmPassword" placeholder="Verify Password" >
            <input type="hidden" name="signUp" value = "1">
            <button class="form-btn sx log-in" type="button">Log In</button>
            <button class="form-btn dx" name="submit" type="submit">Sign Up</button>
        </form>
        <form class="signIn" method="post">
            <h3>Welcome<br>Back !</h3>
            <button class="fb" type="button">Log In With Facebook</button>
            <p>- or -</p>
            <input type="email" name="email" placeholder="Insert eMail" autocomplete='off' reqired />
            <input type="password" name="password" placeholder="Insert Password" reqired />
            <input type="hidden" name = "logIn" value="0">
            <button class="form-btn sx back" type="button">Back</button>
            <button class="form-btn dx" name="submit" type="submit">Log In</button>
        </form>
      </div>
</div>
<!--
       <div class="alert alert-danger">
      
      </div>
-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <script type="text/javascript" src="scripts.js"></script>
</body> 
</html>
