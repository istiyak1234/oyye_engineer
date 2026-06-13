<?php
if (empty($_POST['imageData'])) {
    die("No image data received!");
}

$data = $_POST['imageData'];
if (strpos($data, 'base64,') !== false) {
    $data = explode('base64,', $data)[1];
}

$image = base64_decode($data);
if ($image === false) {
    die("Failed to decode image data!");
}

// Ensure 'photos' folder exists
$photosDir = __DIR__ . "/photos";
if (!is_dir($photosDir)) {
    mkdir($photosDir, 0777, true);
}

// Save captured image temporarily
$capturePath = "$photosDir/captured.jpg";
file_put_contents($capturePath, $image);

// Load captured photo
$photo = @imagecreatefromjpeg($capturePath);
if (!$photo) {
    die("Image not supported or corrupted!");
}

/*
📏 Kodak 4×6 inch paper = 1200×1800 px @ 300 DPI
📸 Passport photo = 3.5×4.5 cm ≈ 413×531 px
📋 Layout = 4 columns × 2 rows = 8 photos
*/

$width = 1800;   // 6 inches × 300 DPI
$height = 1200;  // 4 inches × 300 DPI
$canvas = imagecreatetruecolor($width, $height);

// White background
$white = imagecolorallocate($canvas, 255, 255, 255);
imagefilledrectangle($canvas, 0, 0, $width, $height, $white);

// Border color
$black = imagecolorallocate($canvas, 0, 0, 0);

// Photo size (3.5×4.5 cm)
$passportW = 413;
$passportH = 531;

// Spacing and margins
$spacingX = 40;
$spacingY = 40;

// Total photo area (for centering)
$totalWidth = ($passportW * 4) + ($spacingX * 3);
$totalHeight = ($passportH * 2) + ($spacingY * 1);

// Center photos on paper
$startX = ($width - $totalWidth) / 2;
$startY = ($height - $totalHeight) / 2;

// Draw 8 photos with black borders
$count = 0;
for ($row = 0; $row < 2; $row++) {
    for ($col = 0; $col < 4; $col++) {
        $x = $startX + ($passportW + $spacingX) * $col;
        $y = $startY + ($passportH + $spacingY) * $row;

        // Draw photo
        imagecopyresampled(
            $canvas, $photo,
            $x, $y, 0, 0,
            $passportW, $passportH,
            imagesx($photo), imagesy($photo)
        );

        // Draw 2px thick black border
        for ($i = 0; $i < 2; $i++) {
            imagerectangle($canvas, $x + $i, $y + $i, $x + $passportW - $i, $y + $passportH - $i, $black);
        }

        $count++;
        if ($count >= 8) break 2;
    }
}

// Save final output in 'photos/' folder
$timestamp = time();
$filename = "passport_sheet_kodak_blackborder_$timestamp.jpg";
$outputFile = "$photosDir/$filename";
imagejpeg($canvas, $outputFile, 100);

// Cleanup
imagedestroy($canvas);
imagedestroy($photo);

// Relative path for browser
$outputWebPath = "photos/$filename";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>4×6 Passport Photo Sheet (3.5×4.5 cm × 8)</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center p-4">
  <h2>🎉 Your 4×6 Kodak Passport Photo Sheet (3.5×4.5 cm × 8)</h2>
  <p>✅ Saved in server folder <code>/photos/</code><br>📥 Will auto-download to your computer in 2 seconds.</p>
  <img src="<?= htmlspecialchars($outputWebPath) ?>" class="img-fluid border shadow mb-3" style="max-width:90%;"/>
  <br>
  <a href="<?= htmlspecialchars($outputWebPath) ?>" download class="btn btn-success">Download Now</a>
  <a href="index.html" class="btn btn-secondary">Go Back</a>

  <script>
    // Auto-download to user's local drive (browser default folder)
    setTimeout(() => {
      const link = document.createElement('a');
      link.href = "<?= htmlspecialchars($outputWebPath) ?>";
      link.download = "passport_photo_sheet_kodak_blackborder.jpg";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }, 2000);

    // Redirect back to index.html after 5 seconds
    setTimeout(() => {
      window.location.href = "index.html";
    }, 5000);
  </script>
</body>
</html>
