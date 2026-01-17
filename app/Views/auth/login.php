<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - FAM</title>
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg">
  <div class="card">
    <h1>Welcome back</h1>
    <p class="sub">Log in to FAM</p>

    <?php if (isset($error)): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

      <label>Email</label>
      <input name="email" type="email" required>

      <label>Password</label>
      <input name="password" type="password" required>

      <button type="submit">Login</button>
    </form>

    <p class="small">No account yet? <a href="/register">Create one</a></p>
  </div>

  <script src="/assets/js/app.js"></script>
</body>
</html>
