function sendLocationToServer(deviceId, name) {
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(function(position) {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;

      fetch("user_update_location.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `device_id=${deviceId}&name=${name}&latitude=${lat}&longitude=${lng}`
      });
    });
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}
