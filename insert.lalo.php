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
    <h1>Got you location, have a nice day!</h1>

    <a href="./index.php">
        <h1>See Latest Location Stored in the database</h1>
    </a>

    <div id="map"></div>
    <p id="demo"></p>

    <script>
        var map;
        var marker;

        function initMap(latitude, longitude) {
            map = L.map("map").setView([latitude, longitude], 19);
            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                minZoom: 1,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(map);

            marker = L.marker([latitude, longitude]).addTo(map);
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                document.getElementById("demo").innerHTML =
                    "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

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

            // Send the coordinates to a PHP script
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("demo").innerHTML = this.responseText;
                }
            };
            xmlhttp.open(
                "GET",
                "save_location.php?lat=" + latitude + "&lng=" + longitude,
                true
            );
            xmlhttp.send();
        }

        // Initialize the map when the page loads
        window.onload = function() {
            getLocation();
            setInterval(getLocation, 300000); // 300,000 milliseconds = 5 minutes
        };
    </script>
</body>

</html>