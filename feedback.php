<?php
session_start();
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $conn->real_escape_string($_POST['name']);
    $email   = $conn->real_escape_string($_POST['email']);
    $rating  = $conn->real_escape_string($_POST['rating']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO feedback (name, email, rating, message) 
            VALUES ('$name', '$email', '$rating', '$message')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['feedback_success'] = true;
        header("Location: feedback.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: url('https://images.unsplash.com/photo-1525182008055-f88b95ff7980?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
      background-size: cover;
    }

    .feedback-form {
      max-width: 600px;
      margin: 100px auto;
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .form-header {
      background-color: #0d6efd;
      color: white;
      border-radius: 20px 20px 0 0;
      padding: 20px;
      text-align: center;
    }

    .form-body {
      padding: 30px;
    }

    .form-footer {
      padding: 20px;
      text-align: center;
      border-top: 1px solid #eee;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
  </style>
</head>
<body>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success text-center m-4">
    🎉 Thank you for your feedback! We appreciate your time and thoughts 💙
  </div>
<?php endif; ?>

<div class="feedback-form shadow">
  <div class="form-header">
    <h3><i class="bi bi-chat-left-dots"></i> We Value Your Feedback</h3>
  </div>
  <form action="feedback.php" method="POST">
    <div class="form-body">
      <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
        <label for="name"><i class="bi bi-person-circle"></i> Your Name</label>
      </div>

      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required>
        <label for="email"><i class="bi bi-envelope"></i> Email Address</label>
      </div>

      <div class="form-floating mb-3">
        <select name="rating" class="form-select" id="rating" required>
          <option value="" selected disabled>Choose one</option>
          <option value="Excellent">Excellent</option>
          <option value="Good">Good</option>
          <option value="Average">Average</option>
          <option value="Poor">Poor</option>
        </select>
        <label for="rating"><i class="bi bi-star-half"></i> Rate Us</label>
      </div>

      <div class="form-floating mb-4">
        <textarea name="message" class="form-control" placeholder="Leave your comment here" id="message" style="height: 120px;" required></textarea>
        <label for="message"><i class="bi bi-pencil-square"></i> Your Message</label>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-send-fill"></i> Submit Feedback
        </button>
      </div>
    

    </div>
    <div class="form-footer">
      <small class="text-muted">We appreciate your time and thoughts 💙</small>
    </div>
  </form>
</div>
<!-- Feedback Success Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="feedbackModalLabel">Feedback Submitted</h5>
      </div>
      <div class="modal-body text-center">
        🎉 Thank you for your feedback! We appreciate your time and thoughts 💙<br> You will be redirected shortly.
      </div>
      <div class="modal-footer justify-content-center">
        <div class="spinner-border text-success" role="status">
          <span class="visually-hidden">Redirecting...</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
<?php if (isset($_SESSION['feedback_success']) && $_SESSION['feedback_success'] === true): ?>
  // Define redirect URL based on role
  <?php
    $redirect = "feedback.php";
    if (isset($_SESSION['role'])) {
        switch ($_SESSION['role']) {
            case 'Admin':       $redirect = "time_office_dashboard.php"; break;
            case 'HOD':         $redirect = "hod_dashboard.php"; break;
            case 'Plant_Head':  $redirect = "plant_head_dashboard.php"; break;
            case 'O_M':         $redirect = "om_dashboard.php"; break;
            case 'Employee':    $redirect = "employee_dashboard.php"; break;
        }
    }
    unset($_SESSION['feedback_success']);
  ?>

  // Show modal and redirect after delay
  document.addEventListener("DOMContentLoaded", function() {
    const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
    modal.show();

    setTimeout(function() {
      window.location.href = "<?= $redirect ?>";
    }, 5000); // Redirect after 5 seconds
  });
<?php endif; ?>
</script>


</body>
</html>
