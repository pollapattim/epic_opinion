<?php
session_start();
include("header.php");
include("function.php");

$_SESSION["search_cookie"] = NULL;

if (isset($_GET["search"])) {
    $search = filter_input(INPUT_GET, "search_movie", FILTER_SANITIZE_SPECIAL_CHARS);
    $_SESSION["search"] = $search;
} else {
    $search = $_SESSION["search"];
}

$movie_list = get_movie_list($search);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/movie.css">
</head>

<body>
<div class='view_movie'>

    <?php if (empty($movie_list)) { echo '<img class="logo_big" src="img/1.png">';} else {?>
    
    <?php foreach ($movie_list as $movie): ?>
        <?php if(!empty($movie["poster_path"])){ ?>
        <?php echo '<div class="box">'; ?>
        <?php echo '<form action="movie_page.php" method="GET">' ?>
        <?php $poster_url = "https://image.tmdb.org/t/p/w500" . $movie["poster_path"]; ?>
        <?php $movie_id = $movie["id"]; ?> 
        <?php echo " <button type='submit' name='movie_id' value='".$movie_id."' class='movie_btn'>"; ?>
        <?php echo "<img class='movie_img' src='".$poster_url.">'.<br>"; ?>
        <?php echo " </button>"; ?>
        <?php echo '<div class="info">'; ?>
        <h3> <?php echo $movie["title"]; ?></h3>
        <?php echo '</div>'; ?>
        <?php echo '</form>'; ?>
        <?php echo '</div>'; ?>
        <?php } ?>   

    <?php endforeach; }?>
    
</div>

</body>

