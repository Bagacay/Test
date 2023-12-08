<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <style>
    #map {
      height: 100vh;
      width: 100vw;
    }
  </style>
  <!-- Make sure you put this AFTER Leaflet's CSS -->

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <title>Location Tracker</title>
</head>

<body>
  <h1>Location Tracker</h1>

  <div id="map"></div>
  <p id="demo"></p>

  <script>
    var map;
    var marker;
    var locationDataList = [];

    function initMap(latitude, longitude) {
      map = L.map("map").setView([latitude, longitude], 19);
      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 20,
        minZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      }).addTo(map);

      marker = L.marker([latitude, longitude]).addTo(map);
    }

    function getLocationFromDatabase() {
      // Fetch the location from the server
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var locationData = JSON.stringify(this.responseText);

          if (locationData && locationData.latitude && locationData.longitude && locationData.timestamp) {
            var latitude = locationData.latitude;
            var longitude = locationData.longitude;
            var timestamp = locationData.timestamp;

            // Check if the location has changed
            if (!locationDataList.length || timestamp > locationDataList[locationDataList.length - 1].timestamp) {
              // Add the location data to the list
              locationDataList.push({
                latitude,
                longitude,
                timestamp
              });

              // Sort the list by timestamp
              locationDataList.sort((a, b) => a.timestamp - b.timestamp);

              // Update the map and marker with the latest data
              var latestLocation = locationDataList[locationDataList.length - 1];
              if (!map) {
                initMap(latestLocation.latitude, latestLocation.longitude);
              } else {
                map.setView([latestLocation.latitude, latestLocation.longitude], 19);
                marker.setLatLng([latestLocation.latitude, latestLocation.longitude]);
              }

              // Log latitude, longitude, and timestamp to the console
              console.log("Latitude:", latestLocation.latitude);
              console.log("Longitude:", latestLocation.longitude);
              console.log("Timestamp:", latestLocation.timestamp);
            }
          } else {
            // Handle case where location data is not available
            console.error("Invalid location data:", this.responseText);
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