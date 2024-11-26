<?php
    include("database.php");
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
        <div class="login-page">
            <div class="form">

                <a href="index.php"><img class="logo" src="img/2.png"></a> 

                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="login-form">
                    <input type="text" name="username" placeholder="username" autocomplete="off"><br>
                    <input type="text" name="password" placeholder="password" autocomplete="off"><br>
                    <input type="submit" name="login" value="Login" class="login_bar">
                    <p class="message"><a href="register.php">Create an account</a></p>
                </form>
            </div>
        </div>
</body>
</html>

<?php

    if (isset($_POST["login"])) {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($username)) {
            echo "Please enter a username";
        }
        elseif (empty($password)) {
            echo "Please enter a password";
        }
        else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            $find_user_sql = "SELECT * FROM users WHERE user = '$username'";
            $result = mysqli_query($conn, $find_user_sql);
            
            if(mysqli_num_rows($result) > 0) {
                $user_info = mysqli_fetch_assoc($result);
                if($user_info["password"] == password_verify($password, $hash_pass)) {
                    echo "You are login" . "<br>";
                    echo "ID: " . $user_info["id"] . "<br>";
                    echo "Name: " . $user_info["user"] . "<br>";
                    
                    $_SESSION["user_id"] = $user_info["id"];
                    $_SESSION["username"] = $user_info["user"];
                    $_SESSION["admin"] = $user_info["admin"];
                    
                    session_start();
                    header("Location: index.php");
                 
                }
                else {

                    echo"Password is incorrect";

                } 
            }
            else {
               
                echo "<p class='message'>This username is not registered</p>";
                

            }
        }
    }
    mysqli_close($conn);
?>