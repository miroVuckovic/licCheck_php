<?php
// Include the database connection
// include 'config/database.php';

$host = 'localhost';
$dbname = 'liccheck';
$username = 'root'; // Replace with your DB username
$password = ''; // Replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}


// if ($action === 'delete_user' && isset($_POST['user_id'])) {
//         $user_id = $_POST['user_id'];

//         try {
//             // Delete user roles
//             $sql = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
//             $sql->execute([$user_id]);

//             // Delete user
//             $sql = $pdo->prepare("DELETE FROM users WHERE id = ?");
//             $sql->execute([$user_id]);

//             echo "User deleted successfully!";
//         } catch (PDOException $e) {
//             echo "Error: " . $e->getMessage();
//         }
//     } elseif ($action === 'update_roles' && isset($_POST['user_id'])) {
//         $user_id = $_POST['user_id'];
//         $roles = $_POST['roles'] ?? [];

//         try {
//             // Delete existing roles for the user
//             $sql = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
//             $sql->execute([$user_id]);

//             // Insert the new roles
//             foreach ($roles as $role_id) {
//                 $sql = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
//                 $sql->execute([$user_id, $role_id]);
//             }

//             echo "User roles updated successfully!";
//         } catch (PDOException $e) {
//             echo "Error: " . $e->getMessage();
//         }
//     }


// Dohvaćanje usera i rola.
$userssql = $pdo->query("SELECT users.id, users.username, users.email, GROUP_CONCAT(roles.role_name SEPARATOR ', ') as roles
                          FROM users
                          LEFT JOIN user_roles ON users.id = user_roles.user_id
                          LEFT JOIN roles ON user_roles.role_id = roles.id
                          GROUP BY users.id");
$users = $userssql->fetchAll(PDO::FETCH_ASSOC);

// Dohvaćanje rola za selekciju.
$rolessql = $pdo->query("SELECT * FROM roles");
$roles = $rolessql->fetchAll(PDO::FETCH_ASSOC);
?>
    <h1>User Management</h1>

    <!-- Korisnici i role -->
    <h2>Korisnici</h2>
    <?php if (!empty($users)): ?>
        <table border="1">
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
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
                                <input type="checkbox" id="role_<?= $user['id'] . '_' . $role['id']; ?>" name="roles[]" value="<?= $role['id']; ?>" <?= strpos($user['roles'], $role['role_name']) !== false ? 'checked' : ''; ?>>
                                <label for="role_<?= $user['id'] . '_' . $role['id']; ?>"><?= $role['role_name']; ?></label><br>
                            <?php endforeach; ?>
                            <input type="submit" value="Update Roles">
                        </form>

                        <!-- Delete User Form -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_user">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <input type="submit" value="Delete User" onclick="return confirm('Are you sure you want to delete this user?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
