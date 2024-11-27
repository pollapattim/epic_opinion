<?php  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/header.css">
    <script src="https://kit.fontawesome.com/fdaf41001b.js" crossorigin="anonymous"></script>

</head>


<nav class="header_bar">
    <!-- Header left section -->
    <div class="header_left_section">
            
        <a class="header_button" href="index.php">Home</a></li>
        <div class="dropdown">
        
        <button class="header_button">Menu</button>
            <div class="dropdown-content">
                <?php if (!empty($_SESSION["user_id"]) && $_SESSION["admin"] == 1) {?>
                    <a href="admin.php">Admin</a>
                <?php }?>
                <form action="index.php" method="post">
                    <button class="dropdown_btn" type="submit" name="popular">Popular</button>
                </form>
            </div>
   
        </div>
                
    </div>

        <!-- Header middle section -->
        <div class="header_middle_section">

            <div class="search_bar">
                <form class="search_input" action="index.php" method="get">

                    <input type="text" name="search_movie" autocomplete="off">
                    <input style="font-family: FontAwesome" value="&#xf002;" type="submit" name="search">
                </form>
            </div>
        </div>

        <!-- Header left section -->
        <div class="header_right_section"> 

            <?php if (!empty($_SESSION["user_id"])) { ?>
                <a class="header_button" href="account.php"><?php echo $_SESSION["username"]; ?></a></li>
                <form class="logout" action="logout.php" method="post">
                    <input type="submit" value="Logout" class="header_button">
                </form>
                

            <?php } else { ?>
                
                <a class="header_button" href="login.php">Sign In</a></li>
                <a class="header_button" href="register.php">Register</a></li>
            <?php } ?>
        </div>
    </nav> 