<?php $user = session_user(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars(config('app.name')) ?></title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<nav class="nav">
  <h1>Online Examination System</h1>
  <div>
    <?php if ($user): ?>
      <span><?= htmlspecialchars($user['full_name']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
      <a href="index.php?page=logout">Logout</a>
    <?php else: ?>
      <a href="index.php?page=login">Login</a>
      <a href="index.php?page=register">Register</a>
    <?php endif; ?>
  </div>
</nav>
<main class="container">
  <?php if ($msg = flash()): ?><p class="flash"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
  <?php require $viewPath; ?>
</main>
<script src="../assets/js/app.js"></script>
</body>
</html>
