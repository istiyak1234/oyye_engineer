<?php require 'conn.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin | Live Tracker</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script>
    function loadData() {
      fetch('get_locations.php')
      .then(res => res.json())
      .then(data => {
        let html = '<table class="table table-bordered"><thead><tr><th>Name</th><th>Coordinates</th><th>Location</th><th>Last Update</th></tr></thead><tbody>';
        data.forEach(item => {
          html += `<tr>
                    <td>${item.name}</td>
                    <td>${item.latitude}, ${item.longitude}</td>
                    <td>${item.location_name}</td>
                    <td>${item.updated_at}</td>
                  </tr>`;
        });
        html += '</tbody></table>';
        document.getElementById('tracker').innerHTML = html;
      });
    }

    setInterval(loadData, 5000); // Refresh every 5 seconds
    window.onload = loadData;
  </script>
</head>
<body class="p-4">
  <h2>Live User Location Tracker</h2>
  <div id="tracker" class="mt-3"></div>
</body>
</html>
