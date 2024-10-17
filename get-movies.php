<?php
// get-movies.php

$apiKey = 'APIKEY';
$apiURL = "https://api.themoviedb.org/3/trending/all/day?api_key={$apiKey}";

// Fetch data from the TMDB API
$response = file_get_contents($apiURL);

// Return the response to the frontend
header('Content-Type: application/json');
echo $response;