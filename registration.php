<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BUSINESS CRM - Sign Up</title>

  <link rel="stylesheet" href="css/sampa_css/style.css">
    <link rel="stylesheet" href="css/sampa_css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sampa_css/all.min.css">

  <style>
    body {
      background-color: #f5f7fb;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
  </style>
</head>

<body>

  <div class="card-box">

    <div class="title">BUSINESS CRM</div>
    <div class="subtitle">Create your account</div>

    <form id="signupForm" novalidate>
      <div class="mb-3">
        <input type="text" id="name" class="form-control" placeholder="Full Name">
      </div>

      <div class="mb-3">
        <input type="text" id="phone" class="form-control" placeholder="Phone Number">

        <div class="invalid-feedback">
          Phone number must be numaric and 10 digits.
        </div>
      </div>

      <div class="mb-3">
        <input type="email" id="email" class="form-control" placeholder="Email Address" required>

        <div class="invalid-feedback">
          Please enter a valid email address.
        </div>
      </div>

      <div class="mb-3">
        <div class="position-relative">
          <input type="password" id="password" class="form-control pe-5" placeholder="Password">

          <div id="eye_icon">
            <i class="fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3 toggle-eye"
              onclick="togglePassword()"></i>
          </div>

          <div class="invalid-feedback">
            Password must be at least 6 characters.
          </div>
        </div>
      </div>

      <div class="mb-3">
        <div class="position-relative">
          <input type="password" id="confirmPassword" class="form-control pe-5" placeholder="Confirm Password">

          <div id="eye_icon_confirm">
            <i class="fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3 toggle-eye"
              onclick="toggleConfirmPassword()"></i>
          </div>

          <div class="invalid-feedback">
            Confirm password must be same as password.
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Sign Up
      </button>

    </form>

  </div>

  <script src="js/sampa_js/all.min.js"></script>
  <script src="js/sampa_js/script.js"></script>

</body>

</html>