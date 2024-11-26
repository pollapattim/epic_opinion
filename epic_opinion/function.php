<?php

include("database.php");

function get_movie_list($search) {

    $search = str_replace(' ', '%20', "$search");

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.themoviedb.org/3/search/movie?query={$search}&page=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyOWVmOGUzMDZhNmZhYzcwOTI4MzE5MmRmN2Y1YmJkMSIsIm5iZiI6MTcyNTQ2MDQwMi4wNjgxMTIsInN1YiI6IjY2ZDg2ZDgzNzc5ZTFmZjE5MmJmNTIyMiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.ptEwEoRlEeratWptI_p9GZRo3r9vDVKabsOZthQI2oE",
        "accept: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
        
        $response = json_decode($response);
        $movie_list = array();

        foreach ($response->results as $result) {
            $movie_info = array("id" => "$result->id", "title" => "$result->title", "poster_path" => "$result->poster_path", "overview" => "$result->overview");
            array_push($movie_list, $movie_info);
        }
        
        return $movie_list;
    }

}

function get_movie_info($movie_id) {

    $curl = curl_init();
    
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.themoviedb.org/3/movie/{$movie_id}?language=en-US",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyOWVmOGUzMDZhNmZhYzcwOTI4MzE5MmRmN2Y1YmJkMSIsIm5iZiI6MTcyOTE2MTgyNi43NTU4ODksInN1YiI6IjY2ZDg2ZDgzNzc5ZTFmZjE5MmJmNTIyMiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.RWGkK1aLhdmbHxrQPSVF7Tm8VjLeYyQrmGdnnjbrzfQ",
        "accept: application/json"
      ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    

    curl_close($curl);
    //$response = json_decode($response);
    //echo $response;
    
   //$response = $response["belongs_to_collection"];
    $response = json_decode($response);
    /*
    echo '<pre>';
    print_r($response);
    echo '</pre>';
    */
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {        
       return $response;
    }

}

function get_movie_review($movie_id, $conn) {

  $find_movie_review = "SELECT * FROM reviews  WHERE movie_id = $movie_id ORDER BY date DESC";

  $review_result = mysqli_query($conn, $find_movie_review);
  echo '<div class="admin_col">';
  while ($review_list = mysqli_fetch_assoc($review_result)) {
    echo '<div class="review_content">';

    
    echo '<div class="review_profile">';
    echo "ID: ".$review_list["user_id"]."<br>";
    echo '</div>';

    
    echo '<div class="review_body">';
    echo '<div class="review_body_top">';
    echo 'Comment: ';
    echo '<div>'; 
    echo "Score: ".$review_list["rating"]."<br>";
    echo '</div>';
    echo '</div>';
    
    echo '<div class="review_body_content">';
    echo '"';
    echo nl2br($review_list["review"]);
    echo '"';
    echo '</div>';

    echo '<div class="review_footer">';
    echo "Date: ".$review_list["date"]."<br>";
    echo '</div>';

    echo '</div>';
    echo '</div>';
  }
  echo '</div>';
}

function get_review_account_page($user_id, $conn) {

  $user_reviews = "SELECT * FROM reviews WHERE user_id = $user_id ORDER BY date DESC";

    $review_result = mysqli_query($conn, $user_reviews);
    
    
    while ($review_list = mysqli_fetch_assoc($review_result)) {
        $movie_id = $review_list["movie_id"];
        $find_poster = "SELECT poster_url FROM movies where movie_id = $movie_id";
        $result = mysqli_query($conn, $find_poster);
        $poster_url_array = mysqli_fetch_assoc($result);
        $poster_url =  $poster_url_array["poster_url"];
        $review_string = nl2br($review_list["review"]);
        echo '<div class="item_col">';
       
        echo '<div class="review_content">';
        echo '<form action="movie_page.php" method="GET">'; 
        echo "<button type='submit' name='movie_id' value='".$movie_id."' class='movie_btn'>";
        echo "<img class='movie_small_img' src='".$poster_url.">'.<br>"; 
        echo " </button>";
        echo "</form>";
        

        
        echo '<div class="account_review_body">';
        echo '<div class="review_body_top">';
        echo 'Comment: ';
        echo '<div>'; 
        echo "Score: ".$review_list["rating"]."<br>";
        echo '</div>';
        echo '</div>';
        
        echo '<div class="review_body_content">';
        echo '"';
        echo nl2br($review_list["review"]);
        echo '"';
        echo '</div>';

        echo '<div class="review_footer">';
        echo "Date: ".$review_list["date"]."<br>";
        echo '</div>';

        echo '</div>';

        

            echo '<div>';
            echo '<div>';
            echo '<form action="#popup1" method="post">';
            echo " <button type='submit' href='#popup1' name='edit_button' value='";
            echo $movie_id;
            echo "' class='btn'>";
            echo 'Edit'; 
            echo " </button>";
            echo '</form>';
            echo '</div>';
            echo '<div id="popup1" class="overlay">';
            echo '<div class="popup">';
            echo '<a class="close" href="#">&times;</a>';
            echo '<h3>Write Your Review</h3>';
            echo '<form method="post" action="#">';
            echo '<input class="comment_submit_btn" type="number" name="rating" min="0" max="10" value="';
            echo  $review_list["rating"];
            echo  '">';
            echo '<textarea class="textarea_review" name="review" id="" placeholder="Comment..."></textarea>';
            echo '<input class="comment_submit_btn" type="submit" name="update_review" value="Submit">';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo '<div>';
            echo '<form action="account.php" method="post">';
            echo " <button type='submit' name='delete_review' value='";
            echo $movie_id;
            echo "' class='btn_del'>";
            echo "X"; 
            echo " </button>";
            echo '</form>';
            echo '</div>';

          echo '</div>';
          echo '</div>';
    }
    
}

function admin_get_users($conn) {
  $sql = "SELECT * FROM users;";
        $result = mysqli_query($conn, $sql);
        
        echo '<div class="admin_col">';

        while ($users_database = mysqli_fetch_assoc($result)) {
        echo '<div class="users_body">';

        echo '<div class="review_profile">';
        echo "ID: ".$users_database["id"]."<br>";
        echo '</div>';

        
        echo '<div class="user_body_content">';
        echo '<div>';
        echo "Username: ".$users_database["user"]."<br>"."<br>";
        echo "Status Admin: ".$users_database["admin"]."<br>";
        echo '</div>';
        echo '</div>';
        
        echo '<div>';
        echo '<form action="#" method="post">';
        echo " <button type='submit' name='admin_toggle_status' value='";
        echo $users_database["id"];
        echo "' class='btn'>";
        echo "Toggle"; 
        echo " </button>";
        echo '</form>';
        
        echo '<form action="#" method="post">';
        echo " <button type='submit' name='admin_delete_user' value='";
        echo $users_database["id"];
        echo "' class='btn_del'>";
        echo "Delete"; 
        echo " </button>";
        echo '</form>';
       
        
        echo '</div>';
        
        echo '</div>';
  }
  

            
        
}

function admin_get_movies($conn) {
        $sql = "SELECT * FROM movies;";
        $result = mysqli_query($conn, $sql);
        while ($movies_database = mysqli_fetch_assoc($result)) {
            $movie_id = $movies_database["movie_id"];
            $poster_url =  $movies_database["poster_url"];
            
            echo '<div class="box"'; 
      
            echo '<br>'; 
    
            echo '<form action="movie_page.php" method="GET">'; 
            echo "<button type='submit' name='movie_id' value='".$movie_id."' class='movie_btn'>";
            echo "<img class='movie_img' src='".$poster_url.">'.<br>"; 
            echo " </button>";
            echo "</form>";
            
            
            echo '<div class="info">'; 
            echo $movies_database["title"]; 
            echo '</div>';

            echo '<div class="admin_movie_options">';
            echo '<form method="post">';
            echo " <button type='submit' name='admin_reviews' value='";
            echo $movie_id;
            echo "' class='btn'>";
            echo "Reviews"; 
            echo " </button>";
            echo '</form>';
          
            echo '<form method="post">';
            echo " <button type='submit' name='admin_delete_movie' value='";
            echo $movie_id;
            echo "' class='btn_del'>";
            echo "X"; 
            echo " </button>";
            echo '</form>';
            echo '</div>'; 
            
            echo '</div>'; 
        
        }
}

function admin_get_reviews($conn, $movie_id) {

  echo '<div class="admin_col">';
  $sql = "SELECT title FROM movies WHERE movie_id = $movie_id;";
  $result = mysqli_query($conn, $sql);
  $movie_info = mysqli_fetch_assoc($result);
  $title = $movie_info["title"];
  echo '<div>';
  echo '<p class="admin_movie_title">';
  echo $title;
  echo '</p>';
  echo '</div>';
  


  $find_movie_review = "SELECT * FROM reviews WHERE movie_id = $movie_id ORDER BY date DESC";
  $review_result = mysqli_query($conn, $find_movie_review);
  

  while ($review_list = mysqli_fetch_assoc($review_result)) {
    echo '<div class="review_content">';
    movie_review_print($review_list);
    
    echo '<form action="#" method="post">';
    echo " <button type='submit' name='admin_delete_review' value='";
    echo $review_list["user_id"];
    echo "' class='btn_del'>";
    echo "X"; 
    echo " </button>";
    echo '</form>';
    echo '</div>';

  }
  echo '</div>';
}

function movie_review_print($review_list) {
   
    echo '<div class="review_profile">';
    echo "ID: ".$review_list["user_id"]."<br>";
    echo '</div>';

    
    echo '<div class="review_body">';
    echo '<div class="review_body_top">';
    echo 'Comment: ';
    echo '<div>'; 
    echo "Score: ".$review_list["rating"]."<br>";
    echo '</div>';
    echo '</div>';
    
    echo '<div class="review_body_content">';
    echo '"';
    echo nl2br($review_list["review"]);
    echo '"';
    echo '</div>';

    echo '<div class="review_footer">';
    echo "Date: ".$review_list["date"]."<br>";
    echo '</div>';

    echo '</div>';
    
}


