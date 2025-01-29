<?php require __DIR__ . '/../components/header.php'; ?>

<h1>All Users</h1>
<a href="/users/create">Create New User</a>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= $user['name'] ?></td>
        <td><?= $user['email'] ?></td>
        <td>
            <a href="/users/<?= $user['id'] ?>">View</a>
            <a href="/users/<?= $user['id'] ?>/edit">Edit</a>
            <form action="/users/<?= $user['id'] ?>/delete" method="POST" style="display:inline;">
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/../components/footer.php'; ?>