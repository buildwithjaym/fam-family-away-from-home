<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - FAM</title>
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg">
  <div class="card">
    <h1>Create your FAM account</h1>
    <p class="sub">Family Away from Home</p>

    <?php if (isset($error)): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/register">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

      <label>Full name</label>
      <input name="fullname" required>

      <label>Email</label>
      <input name="email" type="email" required>

      <label>Password</label>
      <input name="password" type="password" required>

      <label>Role</label>
      <select name="role">
        <option value="Tenant">Tenant</option>
        <option value="Caretaker">Caretaker</option>
      </select>

      <button type="submit">Create account</button>
    </form>

    <p class="small">Already have an account? <a href="/login">Login</a></p>
  </div>

  <script src="/assets/js/app.js"></script>
</body>
</html>
