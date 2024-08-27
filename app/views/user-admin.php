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

            $user_id = $pdo->lastInsertId();

            foreach ($roles as $role_id) {
                $sql = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $sql->execute([$user_id, $role_id]);
            }

            echo "Korisnik uspješno dodan!";
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

            echo "Korisnik uspješno obrisan!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif ($action === 'update_roles' && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        $roles = $_POST['roles'] ?? [];

        try {
            $sql = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $sql->execute([$user_id]);

            foreach ($roles as $role_id) {
                $sql = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $sql->execute([$user_id, $role_id]);
            }

            echo "Korisničke role uspješto ažurirane!";
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

    <h1>Administracija korisnika</h1>

    <h2>Dodajte novog korisnika</h2>

    <form id="form-login" action="" method="POST">
        <div id="form-login-input">
        <input type="hidden" name="action" value="add_user">
        <label class="login-input" for="username">Korisničko ime:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label class="login-input" for="password">Lozinka:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label class="login-input" for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label class="login-input" for="roles">Dodjela rola:</label><br><br>
        <?php foreach ($roles as $role): ?>
            <input type="checkbox" id="role_<?= $role['id']; ?>" name="roles[]" value="<?= $role['id']; ?>">
            <label for="role_<?= $role['id']; ?>"><?= $role['role_name']; ?></label><br>
        <?php endforeach; ?>
        <br>
        <input type="submit" value="Dodaj korisnika">
        </div>
    </form>

    <!-- Current Users and Roles -->
    <h2>Sadašnji korisnici</h2>
    <div class="user-table-container">


        <?php if (!empty($users)): ?>
            <table border="1">
                <tr>
                    <th>Korisničko ime</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Radnje</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['roles']); ?></td>
                        <td>

                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="update_roles">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <label for="roles_<?= $user['id']; ?>">Promjena rola:</label><br>
                                <?php foreach ($roles as $role): ?>
                                    <input type="checkbox" id="role_<?= $user['id'] . '_' . $role['id']; ?>" name="roles[]"
                                        value="<?= $role['id']; ?>" <?= strpos($user['roles'], $role['role_name']) !== false ? 'checked' : ''; ?>>
                                    <label for="role_<?= $user['id'] . '_' . $role['id']; ?>"><?= $role['role_name']; ?></label><br>
                                <?php endforeach; ?>
                                <input type="submit" value="Ažuriraj role">
                            </form>


                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <input type="submit" value="Obriši korisnika"
                                    onclick="return confirm('Sigurno želite obrisati ovog korisnika?');">
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