<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Caretaker Dashboard - FAM</title>
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
  <div class="topbar">
    <div>
      <h2>Caretaker Dashboard</h2>
      <p class="sub">Hi, <?= htmlspecialchars($name) ?></p>
    </div>
    <form method="POST" action="/logout">
      <button type="submit" class="ghost">Logout</button>
    </form>
  </div>

  <div class="grid">
    <div class="kpi">
      <div class="kpi-title">Listings</div>
      <div class="kpi-val">0</div>
      <div class="kpi-sub">Create your first listing</div>
    </div>
    <div class="kpi">
      <div class="kpi-title">Total Capacity</div>
      <div class="kpi-val">0</div>
      <div class="kpi-sub">Beds/rooms across listings</div>
    </div>
    <div class="kpi">
      <div class="kpi-title">Available Slots</div>
      <div class="kpi-val">0</div>
      <div class="kpi-sub">Live availability</div>
    </div>
    <div class="kpi">
      <div class="kpi-title">Pending Applicants</div>
      <div class="kpi-val">0</div>
      <div class="kpi-sub">Submitted + Scheduled</div>
    </div>
  </div>
</body>
</html>
