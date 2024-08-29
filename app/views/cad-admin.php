<?php

require("config/database_pdo.php");

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

            $cad_id = $pdo->lastInsertId();

            echo "CAD uspješno dodan!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif ($action === 'delete_cad' && isset($_POST['cad_id'])) {
        $cad_id = $_POST['cad_id'];

        try {
            $sql = $pdo->prepare("DELETE FROM cad_systems WHERE id = ?");
            $sql->execute([$cad_id]);

            echo "CAD uspješno obrisan!";
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


    <h1>Administracija licenci CAD-ova</h1>

    <h2>Dodajte novi CAD:</h2>
    <form id="cad-add-user-form" action="" method="POST">

        <input type="hidden" name="action" value="add_cad">

        <label for="cad-name">Ime CAD-a:</label>
        <input type="text" id="cad-name" name="cad-name" required><br><br>


        <label for="cad_display_name">Ime za prikaz:</label>
        <input type="text" id="cad_display_name" name="cad_display_name" required><br><br>

        <label for="cad-port">Port:</label>
        <input type="text" id="cad-port" name="cad-port" required><br><br>

        <label for="cad-server">Server:</label>
        <input type="text" id="cad-server" name="cad-server" required><br><br>

        <label for="cad-product">Proizvod:</label>
        <input type="text" id="cad-product" name="cad-product" required><br><br>

        <label for="roles">Aktiviraj?:</label><br>

        <input type="checkbox" id="cad-active" name="cad-active" value="true">

        <br>
        <br>
        <input type="submit" value="Dodaj CAD">
    </form>


    <h2>Dodani CAD-ovi</h2>
    <div class="cad-table-container">

        <?php if (!empty($cads)): ?>
            <table border="1">
                <tr>
                    <th>CAD</th>
                    <th>Ime za prikaz</th>
                    <th>Port</th>
                    <th>Server</th>
                    <th>Proizvod</th>
                    <th>Status</th>
                    <th>Radnje</th>
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
                                <label for="roles_<?= $cad['id']; ?>">Promijeni status:</label><br>
                                <input type="submit" value="Ažuriraj CAD">
                            </form>

                            <!-- Delete User Form -->
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_cad">
                                <input type="hidden" name="cad_id" value="<?= $cad['id']; ?>">
                                <input type="submit" value="Obriši CAD"
                                    onclick="return confirm('Sigurno želite obrisati ovaj CAD?');">
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