<?php
    include("database.php");
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
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input type="text" name="username" placeholder="username" autocomplete="off"><br>
                    <input type="text" name="password" placeholder="password" autocomplete="off"><br>
                    <input type="submit" name="submit" value="Register">
                    <p class="message"><a href="login.php">Sign In</a></p>
                </form>
            </div>
        </div>
</body>
</html>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($username)) {
            echo "Please enter a username";
        }
        elseif (empty($password)) {
            echo "Please enter a password";
        }
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (user, password) VALUES ('$username', '$hash')";
            try {
                mysqli_query($conn, $sql);
                header("Location: login.php");
            }
            catch (mysqli_sql_exception) {
                echo '<script>alert("That username is already taken")</script>';
            }
        }
    }
    mysqli_close($conn);
?>