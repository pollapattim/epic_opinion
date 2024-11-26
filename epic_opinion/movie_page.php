<?php

session_start();
include("header.php");
include("function.php");
include("database.php");

$movie_id = $_GET["movie_id"];
$movie = get_movie_info($movie_id);
$movie_genre_list = [];
$genre_list_string = '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/movie.css">
    <link rel="stylesheet" href="styles/movie_page.css">
    <link rel="stylesheet" href="styles/review.css">
</head>

<body class="main_box">
    <div class="movie_page_body">
        <div class="movie_page_content">
            <div class="moive_picture">

                <?php $poster_url = "https://image.tmdb.org/t/p/w500" . $movie->poster_path; ?>
                <?php echo "<img src='".$poster_url."'>"; ?>

            </div>
            <div class="movie_info">
                <div class="title">
                    <h2> <?php echo $movie->title; ?><br></h2>
                </div>
                <h4> <?php echo $movie->release_date; ?><br></h4>
                <div class="discription">
                    <h4> <?php echo $movie->overview; ?><br></h4>
                </div>
                <div class="discription">
                    <h4> <?php 
                            foreach ($movie->genres as $genre) {
            
                                array_push($movie_genre_list, $genre->name);
                                
                            }

                            $genre_list_string = implode(", ", $movie_genre_list);
                            echo $genre_list_string;
                        ?><br>
                    </h4>
                </div>
            </div>
            </div>
    </div>
    <div class="review_header">

        <div>
            <h2 >REVIEW</h2>    
        </div>

        <div>
            <?php if (!empty($_SESSION["user_id"])) {?>
                <a class="button" href="#popup1">Add Comment</a>
            <?php } ?>
        </div>
        
        <?php
            echo '<div id="popup1" class="overlay">';
            echo '<div class="popup">';
            echo '<a class="close" href="#">&times;</a>';
            echo '<h3>Write Your Review</h3>';
            echo '<form method="post" action="#">';
            echo '<input class="comment_submit_btn" type="number" name="rating" min="0" max="10" value="0">';
            echo '<textarea class="textarea_review" name="review" id="" placeholder="Comment..."></textarea>';
            echo '<input class="comment_submit_btn" type="submit" name="update_review" value="Submit">';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            
            ?>
    </div>

</body>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $review = $_POST["review"];
        $rating = $_POST["rating"];
        $user_id = $_SESSION["user_id"];


        $sql = "INSERT INTO reviews (movie_id, user_id, rating, review) VALUES ($movie_id, $user_id, $rating, '$review')";
        try {
            mysqli_query($conn, $sql);
           
        }
        catch (mysqli_sql_exception) {
            
        }
        
        $title = addslashes($movie->title);
        $overview = addslashes($movie->overview);

        $sql = "INSERT INTO movies (movie_id, title, poster_url, genre, overview) VALUES ($movie_id, '$title', '$poster_url', '$genre_list_string', '$overview')";
        try {
    
            mysqli_query($conn, $sql);
              
            
        }
        catch (mysqli_sql_exception) {
            
        }

    }
    echo '<div class="main_box">';
    get_movie_review($movie_id, $conn);
    echo '</div>';
    mysqli_close($conn);
?>