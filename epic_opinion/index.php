<?php
session_start();
include("header.php");
include("function.php");

$_SESSION["search_cookie"] = NULL;

if (isset($_GET["search"])) {
    $search = filter_input(INPUT_GET, "search_movie", FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $search = $_SESSION["search"];
}

if (isset($_POST["popular"])) {
    unset($_GET["search"]);
    $search = NULL;
}

$_SESSION["search"] = $search;

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
    <?php if (empty($movie_list) ) { $movie_list = popular_movies();
                                    $page_title = 'Popular';}
                                   else {$page_title = 'Search';} ?>
    <div class="below_header">
        <div>                               
            <?php print_page_title($page_title); ?>
        </div>
    </div>
    <div class='view_movie'>
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

        <?php endforeach;?>
        
    </div>

</body>

