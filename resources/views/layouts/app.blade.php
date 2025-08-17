<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light mb-3">
  <div class="container">
    <a class="navbar-brand" href="/admin/plugins">Plugins</a>
    <div class="d-flex gap-3">
      <a href="/form" class="nav-link">Form</a>
      <a href="/forms" class="nav-link">Forms</a>
      <a href="/backups" class="nav-link">Backups</a>
      <a href="/backup" class="nav-link">Run Backup</a>
      <a href="/notes" class="nav-link">Notes</a>
    </div>
  </div>
</nav>
<div class="container">
  @yield('content')
</div>
</body>
</html>