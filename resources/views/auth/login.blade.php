<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Kas Warga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>

<div class="auth-container">

  <!-- LEFT SIDE -->
  <div class="auth-left">
    <div class="overlay">
      <div class="brand">
        <h1>KasWarga</h1>
        <p>Solusi Digital Administrasi Lingkungan</p>

        <ul>
          <li>✔ Pembayaran iuran otomatis</li>
          <li>✔ Monitoring keuangan transparan</li>
          <li>✔ Laporan real-time</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE -->
  <div class="auth-right">
    <div class="login-card">

      <div class="logo">
        <img src="/img/login.png" alt="logo">
      </div>

      <h2>Sign in</h2>
      <p class="subtitle">Masuk ke akun Kas Warga</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="input-group">
          <input type="email" name="email" required>
          <label>Email</label>
        </div>

        <!-- Password -->
        <div class="input-group">
          <input type="password" name="password" id="password" required>
          <label>Password</label>
        </div>

        <div class="options">
          <label>
            <input type="checkbox" onclick="togglePassword()"> Show password
          </label>

          <!-- <a href="#">Forgot password?</a> -->
        </div>

        <button type="submit" class="btn-login">
          Login
        </button>

      </form>

    </div>
  </div>

</div>

<script>
function togglePassword() {
  const pass = document.getElementById("password");
  pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>