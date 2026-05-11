<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - SPK Jamur</title>
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <style>
    body{margin:0}
  </style>
</head>
<body class="login-wrap">
  <div class="card login-card" style="background:linear-gradient(180deg,#fff,#fbfeff);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
      <h2 style="margin:0">Welcome back</h2>
      <small style="color:var(--muted)">SPK Diagnosa</small>
    </div>

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-row">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}">
      </div>

      <div class="form-row">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
      </div>

      <div class="form-row" style="display:flex;align-items:center;justify-content:space-between">
        <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="remember"> Remember me</label>
        <a href="{{ route('password.request') }}" style="color:var(--accent)">Forgot?</a>
      </div>

      <div style="display:flex;gap:10px;align-items:center">
        <button class="btn btn-primary" type="submit">Log in</button>
        <a href="{{ route('register') }}" class="btn" style="border:1px solid #e6edf3">Create account</a>
      </div>
    </form>
  </div>
</body>
</html>
