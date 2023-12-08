<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <style>
    #map {
      height: 80vh;
      width: 100%;
    }
  </style>
  <!-- Make sure you put this AFTER Leaflet's CSS -->

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <title>Location Tracker</title>
</head>

<body>
  <h1>Latest Location on the database</h1>
  <h1> <a href="./insert.lalo.php">Insert your location here</a></h1>


  <div id="map"></div>
  <p id="demo"></p>

  <script>
    var map;
    var marker;
    var previousLatitude;
    var previousLongitude;

    function initMap(latitude, longitude) {
      map = L.map("map").setView([latitude, longitude], 19);
      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        minZoom: 1,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      }).addTo(map);

      marker = L.marker([latitude, longitude]).addTo(map);
    }

    function getLocationFromDatabase() {
      // Fetch the location from the server
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var locationData = JSON.parse(this.responseText);

          if (locationData && locationData.latitude && locationData.longitude) {
            var latitude = locationData.latitude;
            var longitude = locationData.longitude;

            // Check if the location has changed
            if (latitude !== previousLatitude || longitude !== previousLongitude) {
              // Update the map and marker
              if (!map) {
                initMap(latitude, longitude);
              } else {
                map.setView([latitude, longitude], 19);
                marker.setLatLng([latitude, longitude]);
              }

              // Log latitude and longitude to the console
              console.log("Latitude:", latitude);
              console.log("Longitude:", longitude);

              // Update previous coordinates
              previousLatitude = latitude;
              previousLongitude = longitude;
            }
          } else {
            // Handle case where location data is not available
            console.error("Location data not available");
          }
        }
      };

      xmlhttp.open("GET", "get_location.php", true);
      xmlhttp.send();
    }

    // Initialize the map when the page loads
    window.onload = function() {
      getLocationFromDatabase();
      // Refresh location based on change, not every 5 seconds
      setInterval(getLocationFromDatabase, 1000); // 1 second (adjust as needed)
    };
  </script>

</body>

</html>