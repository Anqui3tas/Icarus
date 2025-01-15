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

// Update the script in index.html with the below to use a server side api call. 

<script>
    document.addEventListener("DOMContentLoaded", function() {
      const apiURL = 'https://myurl.com/get-movies.php'; // Your get-movies.php file location, public facing url
      const imageBaseURL = 'https://image.tmdb.org/t/p/original';  // Keep original size for large images
      const container = document.getElementById("background-container");
      const overlay = document.getElementById("black-overlay");
      let images = [];
      let currentIndex = 0;
  
      async function fetchImages() {
        try {
          const response = await fetch(apiURL);  // Fetch from your PHP server
          
          if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
          }
  
          const data = await response.json();
          
          console.log('Fetched data:', data); // Check the structure of the data
  
          // Adjust the mapping based on the data structure
          images = data.results.map(item => {
            // Check if backdrop_path exists
            if (item.backdrop_path) {
              return `${imageBaseURL}${item.backdrop_path}`;
            }
            // Check if known_for exists and is an array
            if (Array.isArray(item.known_for) && item.known_for.length > 0) {
              const knownForBackdrop = item.known_for[0].backdrop_path;
              return knownForBackdrop ? `${imageBaseURL}${knownForBackdrop}` : null; // Return URL if exists
            }
            return null; // Return null if no backdrop path found
          }).filter(path => path); // Filter out null values
  
          if (images.length > 0) {
            preloadImage(images[0], () => {
              container.style.backgroundImage = `url("${images[0]}")`;
              setInterval(changeBackground, 6000);  // Change every 6 seconds
            });
          } else {
            console.error('No images found');
          }
        } catch (error) {
          console.error('Error fetching images:', error);
        }
      }
  
      function preloadImage(src, callback) {
        const img = new Image();
        img.src = src;
        img.onload = callback;
      }
  
      function changeBackground() {
        const nextIndex = (currentIndex + 1) % images.length;
        preloadImage(images[nextIndex], () => {
          overlay.classList.add('fade-out');
          setTimeout(() => {
            container.style.backgroundImage = `url("${images[nextIndex]}")`;
            currentIndex = nextIndex;
            setTimeout(() => {
              overlay.classList.remove('fade-out');
            }, 4000);
          }, 2000);
        });
      }
  
      fetchImages();
    });
  </script>