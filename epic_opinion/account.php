<?php

session_start();
include("header.php");
include("function.php");
include("database.php");
//include("side_bar.php");

$user_id = $_SESSION["user_id"];


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
</head>





<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = (int)$user_id;
        
    if(isset($_POST['delete_review'])) {
        $sel_movie_id = (int)$_POST['delete_review'];
        $user_id = (int)$user_id;
        $sql = "DELETE FROM reviews WHERE movie_id = $sel_movie_id AND user_id = $user_id";
        mysqli_query($conn, $sql);
    } 
    
    if (isset($_POST['edit_button'])){ 
        $_SESSION["movie_id"] = (int)$_POST['edit_button'];
    } 
    
    if (isset($_POST['update_review'])){    
        
        $sel_movie_id = $_SESSION["movie_id"];
        $review = $_POST["review"];
        $rating = $_POST["rating"];
       
        $sql = "UPDATE reviews SET rating = $rating, 
                                    review = '$review'
                WHERE movie_id = $sel_movie_id AND user_id = $user_id;";
        try {
            mysqli_query($conn, $sql);
        }
        catch (mysqli_sql_exception) {}
    }

}   
    get_review_account_page($user_id, $conn);
    mysqli_close($conn); 
?>