<?php require __DIR__ . '/../components/header.php'; ?>

<h1>User Details</h1>
<p>ID: <?= $user['id'] ?></p>
<p>Name: <?= $user['name'] ?></p>
<p>Email: <?= $user['email'] ?></p>
<a href="/users">Back to List</a>

<?php require __DIR__ . '/../components/footer.php'; ?>
