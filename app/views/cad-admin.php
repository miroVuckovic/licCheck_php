<?php

require("config/database_pdo.php");

// Forma za dodavanje korisnika
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];

    if ($action === 'add_cad') {
        $cad_name = $_POST['cad-name'];
        $cad_display_name = $_POST['cad_display_name'];
        $cad_port = $_POST['cad-port'];
        $cad_server = $_POST['cad-server'];
        $cad_product = $_POST['cad-product'];
        if ($_POST['cad-active'] === 'true') {
            $cad_active = true;
        } else {
            $cad_active = false;
        }


        try {
            $sql = $pdo->prepare("INSERT INTO cad_systems (cad_name, display_name, port, server, product, active) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->execute([$cad_name, $cad_display_name, $cad_port, $cad_server, $cad_product, $cad_active]);

            // Get the last inserted user id
            $cad_id = $pdo->lastInsertId();

            // // Insert user roles into the user_roles table
            // foreach ($roles as $role_id) {
            //     $sql = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            //     $sql->execute([$user_id, $role_id]);
            // }

            echo "CAD added successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif ($action === 'delete_cad' && isset($_POST['cad_id'])) {
        $cad_id = $_POST['cad_id'];

        try {
            // Delete user roles
            // $sql = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
            // $sql->execute([$user_id]);

            // Delete user
            $sql = $pdo->prepare("DELETE FROM cad_systems WHERE id = ?");
            $sql->execute([$cad_id]);

            echo "CAD deleted successfully!";
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

// Fetch cads and data
$cadQuery = $pdo->query("SELECT cad_systems.id, cad_systems.cad_name, cad_systems.display_name, cad_systems.port, cad_systems.server, cad_systems.product, cad_systems.active 
                          FROM cad_systems");
$cads = $cadQuery->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="cad-mgmt-container">


    <h1>CAD License Check Management</h1>

    <!-- Add New User Form -->
    <h2>Add New CAD</h2>
    <form action="" method="POST">

        <input type="hidden" name="action" value="add_cad">

        <label for="cad-name">CAD name:</label>
        <input type="text" id="cad-name" name="cad-name" required><br><br>


        <label for="cad_display_name">Display name:</label>
        <input type="text" id="cad_display_name" name="cad_display_name" required><br><br>

        <label for="cad-port">Port:</label>
        <input type="text" id="cad-port" name="cad-port" required><br><br>

        <label for="cad-server">Server:</label>
        <input type="text" id="cad-server" name="cad-server" required><br><br>

        <label for="cad-product">Product:</label>
        <input type="text" id="cad-product" name="cad-product" required><br><br>

        <label for="roles">Activate?:</label><br>

        <input type="checkbox" id="cad-active" name="cad-active" value="true">

        <br>
        <br>
        <input type="submit" value="Add CAD">
    </form>


    <h2>Current CADs</h2>
    <div class="cad-table-container">

        <?php if (!empty($cads)): ?>
            <table border="1">
                <tr>
                    <th>CAD</th>
                    <th>Display name</th>
                    <th>Port</th>
                    <th>Server</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($cads as $cad): ?>
                    <tr>
                        <td><?= htmlspecialchars($cad['cad_name']); ?></td>
                        <td><?= htmlspecialchars($cad['display_name']); ?></td>
                        <td><?= htmlspecialchars($cad['port']); ?></td>
                        <td><?= htmlspecialchars($cad['server']); ?></td>
                        <td><?= htmlspecialchars($cad['product']); ?></td>
                        <td><?= htmlspecialchars($cad['active']); ?></td>
                        <td>
                            <!-- Update Roles Form -->
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="update-status">
                                <input type="hidden" name="cad_id" value="<?= $cad['id']; ?>">
                                <label for="roles_<?= $cad['id']; ?>">Change status:</label><br>
                                <input type="submit" value="Update CAD">
                            </form>

                            <!-- Delete User Form -->
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_cad">
                                <input type="hidden" name="cad_id" value="<?= $cad['id']; ?>">
                                <input type="submit" value="Delete CAD"
                                    onclick="return confirm('Are you sure you want to delete this CAD?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No CADs found.</p>
        <?php endif; ?>
    </div>
</div>
