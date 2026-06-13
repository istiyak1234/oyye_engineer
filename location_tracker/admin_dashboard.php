<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conn.php'; // your conn.php with $conn = new mysqli(...)

$sql = "SELECT device_id, name, latitude, longitude, timestamp FROM user_locations ORDER BY timestamp DESC";
$result = $conn->query($sql);

$locations = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
} else {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - User Location Tracker</title>
  <style>
    #map { height: 500px; width: 100%; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
  </style>
</head>
<body>
  <h2>Live Location Tracker - Admin Panel</h2>
  <div id="map"></div>

  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Device ID</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Timestamp</th>
        <th>Place Name</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($locations as $loc): ?>
      <tr>
        <td><?= htmlspecialchars($loc['name']) ?></td>
        <td><?= htmlspecialchars($loc['device_id']) ?></td>
        <td><?= $loc['latitude'] ?></td>
        <td><?= $loc['longitude'] ?></td>
        <td><?= $loc['timestamp'] ?></td>
        <td class="place-name" data-lat="<?= $loc['latitude'] ?>" data-lng="<?= $loc['longitude'] ?>">Loading...</td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    function initMap() {
      const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 5,
        center: { lat: 22.57, lng: 88.36 }
      });

      const locations = <?= json_encode($locations) ?>;

      locations.forEach(loc => {
        new google.maps.Marker({
          position: { lat: parseFloat(loc.latitude), lng: parseFloat(loc.longitude) },
          map: map,
          title: loc.name + " (" + loc.device_id + ")"
        });
      });

      document.querySelectorAll('.place-name').forEach(td => {
        const lat = td.getAttribute('data-lat');
        const lng = td.getAttribute('data-lng');

        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
          .then(response => response.json())
          .then(data => {
            td.textContent = data.display_name || "Unknown location";
          })
          .catch(() => {
            td.textContent = "Error retrieving place";
          });
      });
    }
  </script>

  <!-- Replace YOUR_GOOGLE_MAPS_API_KEY with your actual API key -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8fRJmv25lVHBcy8KUyzrAWDlzpIKew-c&callback=initMap" async defer></script>


</body>
</html>
