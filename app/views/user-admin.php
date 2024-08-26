<?php

require("config/database_pdo.php");

// Forma za dodavanje korisnika
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_user') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $roles = $_POST['roles'] ?? [];

        try {
            $sql = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $sql->execute([$username, $password, $email]);

            // Get the last inserted user id
            $user_id = $pdo->lastInsertId();

            // Insert user roles into the user_roles table
            foreach ($roles as $role_id) {
                $sql = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $sql->execute([$user_id, $role_id]);
            }

            echo "User created successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif ($action === 'delete_user' && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        try {
            // Delete user roles
            $sql = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $sql->execute([$user_id]);

            // Delete user
            $sql = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $sql->execute([$user_id]);

            echo "User deleted successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif ($action === 'update_roles' && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        $roles = $_POST['roles'] ?? [];

        try {
            // Delete existing roles for the user
            $sql = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $sql->execute([$user_id]);

            // Insert the new roles
            foreach ($roles as $role_id) {
                $sql = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $sql->execute([$user_id, $role_id]);
            }

            echo "User roles updated successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Fetch users and their roles
$usersStmt = $pdo->query("SELECT users.id, users.username, users.email, GROUP_CONCAT(roles.role_name SEPARATOR ', ') as roles
                          FROM users
                          LEFT JOIN user_roles ON users.id = user_roles.user_id
                          LEFT JOIN roles ON user_roles.role_id = roles.id
                          GROUP BY users.id");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch roles for the role selection
$rolesStmt = $pdo->query("SELECT * FROM roles");
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="user-mgmt-container">


    <h1>User Management</h1>

    <!-- Add New User Form -->
    <h2>Add New User</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="add_user">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="roles">Assign Roles:</label><br>
        <?php foreach ($roles as $role): ?>
            <input type="checkbox" id="role_<?= $role['id']; ?>" name="roles[]" value="<?= $role['id']; ?>">
            <label for="role_<?= $role['id']; ?>"><?= $role['role_name']; ?></label><br>
        <?php endforeach; ?>
        <br>
        <input type="submit" value="Create User">
    </form>

    <!-- Current Users and Roles -->
    <h2>Current Users</h2>
    <div class="user-table-container">


        <?php if (!empty($users)): ?>
            <table border="1">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['roles']); ?></td>
                        <td>
                            <!-- Update Roles Form -->
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="update_roles">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <label for="roles_<?= $user['id']; ?>">Change Roles:</label><br>
                                <?php foreach ($roles as $role): ?>
                                    <input type="checkbox" id="role_<?= $user['id'] . '_' . $role['id']; ?>" name="roles[]"
                                        value="<?= $role['id']; ?>" <?= strpos($user['roles'], $role['role_name']) !== false ? 'checked' : ''; ?>>
                                    <label for="role_<?= $user['id'] . '_' . $role['id']; ?>"><?= $role['role_name']; ?></label><br>
                                <?php endforeach; ?>
                                <input type="submit" value="Update Roles">
                            </form>

                            <!-- Delete User Form -->
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <input type="submit" value="Delete User"
                                    onclick="return confirm('Are you sure you want to delete this user?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</div>