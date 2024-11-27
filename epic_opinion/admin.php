<?php

session_start();
include("header.php");
include("function.php");
include("database.php");
$user_id = NULL;
$_SESSION["admin_user"] = NULL;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/movie.css">
    <link rel="stylesheet" href="styles/movie_page.css">
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="stylesheet" href="styles/review.css">
</head>


<body>
    
    
        <form class="admin_top_page" method="post" action="#">
            <input type="submit" class="admin_top_page_content" name="admin_movies" value="Movies Database">
            <input type="submit" class="admin_top_page_content" name="admin_user" value="Users Database">
        </form>
        


</body>

<?php 

$user_id = (int)$user_id;


if(isset($_POST['admin_delete_movie'])) {
    $sel_movie_id = (int)$_POST['admin_delete_movie'];
    
    $sql = "DELETE FROM movies WHERE movie_id = $sel_movie_id;";
    mysqli_query($conn, $sql);

    $sql = "DELETE FROM reviews WHERE movie_id = $sel_movie_id;";
    mysqli_query($conn, $sql);
} 

if(isset($_POST['admin_delete_review'])) {
    $sel_movie_id = (int)$_SESSION["admin_reviews"];
    $user_id = (int)$_POST['admin_delete_review'];

    $sql = "DELETE FROM reviews WHERE movie_id = $sel_movie_id AND user_id = $user_id;";
    mysqli_query($conn, $sql);    
} 

if(isset($_POST['admin_toggle_status'])) {
    $user_id = (int)$_POST['admin_toggle_status'];
    $_POST['admin_toggle_status'] == NULL;
    $sql = "SELECT admin FROM users WHERE id = $user_id;";
    $result = mysqli_query($conn, $sql);
    $status = mysqli_fetch_assoc($result);

    if ($status["admin"] == 0) {
        $sql = "UPDATE users SET admin = 1 WHERE id = $user_id;";
    } else {$sql = "UPDATE users SET admin = 0 WHERE id = $user_id;";}

    mysqli_query($conn, $sql);
    $_SESSION["admin_user"] = TRUE;
} 

if(isset($_POST['admin_delete_user'])) {
    $user_id = (int)$_POST['admin_delete_user'];
    $sql = "DELETE FROM users WHERE id = $user_id;";
    mysqli_query($conn, $sql);

    $sql = "DELETE FROM reviews WHERE user_id = $user_id;";
    mysqli_query($conn, $sql);
    $_SESSION["admin_user"] = TRUE;
} 

        
if (isset($_POST['admin_reviews'])) {
    $sel_movie_id = (int)$_POST['admin_reviews'];
    $_SESSION["admin_reviews"] = $sel_movie_id;
} 

if (isset($_POST['admin_user'])) {
    $_SESSION["admin_user"] = TRUE;
} 

if (isset($_POST['admin_movies'])) {
    $_POST['admin_reviews']= NULL;
    $_SESSION["admin_reviews"] = NULL;
    $_SESSION["admin_user"] = FALSE;
} 


echo '<div class="view_movie">';
    if ($_SESSION["admin_user"] == TRUE) {
        
        admin_get_users($conn);
    } 
    else {
        if (empty($_SESSION["admin_reviews"])) {
            admin_get_movies($conn);
        
        } else {
            admin_get_reviews($conn,  $_SESSION["admin_reviews"]);
        }
    }
echo '</div>';

mysqli_close($conn); 

?>