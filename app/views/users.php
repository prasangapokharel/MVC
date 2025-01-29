<?php require __DIR__ . '/components/header.php'; ?>

<h1>Welcome to My MVC Application!</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['name']; ?></td>
        <td><?php echo $user['email']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/components/footer.php'; ?>