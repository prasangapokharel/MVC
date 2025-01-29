<?php require __DIR__ . '/../components/header.php'; ?>

<h1>Create User</h1>
<form action="/users/store" method="POST">
    <label>Name: <input type="text" name="name" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <button type="submit">Save</button>
</form>

<?php require __DIR__ . '/../components/footer.php'; ?>

