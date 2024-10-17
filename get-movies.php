<?php
// get-movies.php

header("Access-Control-Allow-Origin: *"); // Allow any origin
header("Access-Control-Allow-Methods: GET"); // Allow GET requests
header("Access-Control-Allow-Headers: Content-Type"); // Allow specific headers

$apiKey = 'YOUR_TMDB_API_KEY_HERE';
$apiURL = "https://api.themoviedb.org/3/trending/all/day?api_key={$apiKey}";

// Fetch data from the TMDB API
$response = file_get_contents($apiURL);

// Return the response to the frontend
header('Content-Type: application/json');
echo $response;
?>