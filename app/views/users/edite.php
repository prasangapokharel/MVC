<?php require __DIR__ . '/../components/header.php'; ?>

<h1>Edit User</h1>
<form action="/users/<?= $user['id'] ?>/update" method="POST">
    <label>Name: <input type="text" name="name" value="<?= $user['name'] ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= $user['email'] ?>" required></label><br>
    <button type="submit">Update</button>
</form>

<?php require __DIR__ . '/../components/footer.php'; ?>