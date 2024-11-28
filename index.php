<?php
include 'conexion.php';
conectar();

$query_viaje = "SELECT * FROM viaje";
$query_hotel = "SELECT * FROM hotel";
$query_comida = "SELECT * FROM comida";
$query_transporte = "SELECT * FROM transporte";
$query_paquete = "SELECT p.id_paquete, v.destino, h.nombre AS hotel, c.tipo AS comida, t.tipo_transporte
                  FROM paquete p
                  JOIN viaje v ON p.id_viaje = v.id_viaje
                  JOIN hotel h ON p.id_hotel = h.id_hotel
                  JOIN comida c ON p.id_comida = c.id_comida
                  JOIN transporte t ON p.id_transporte = t.id_transporte";

$result_viaje = mysqli_query($con, $query_viaje);
$result_hotel = mysqli_query($con, $query_hotel);
$result_comida = mysqli_query($con, $query_comida);
$result_transporte = mysqli_query($con, $query_transporte);
$result_paquete = mysqli_query($con, $query_paquete);

// Procesar acciones de Alta, Baja, Modificación
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        $viaje = $_POST['viaje'];
        $hotel = $_POST['hotel'];
        $comida = $_POST['comida'];
        $transporte = $_POST['transporte'];
        $query = "INSERT INTO paquete (id_viaje, id_hotel, id_comida, id_transporte)
                  VALUES ('$viaje', '$hotel', '$comida', '$transporte')";
        if (mysqli_query($con, $query)) {
            $message = "Paquete agregado exitosamente.";
        } else {
            $message = "Error: " . mysqli_error($con);
        }
    }

    if (isset($_POST['eliminar'])) {
        $paquete = $_POST['paquete'];
        $query = "DELETE FROM paquete WHERE id_paquete = '$paquete'";
        if (mysqli_query($con, $query)) {
            $message = "Paquete eliminado exitosamente.";
        } else {
            $message = "Error: " . mysqli_error($con);
        }
    }

    if (isset($_POST['modificar'])) {
        $paquete = $_POST['paquete'];
        $nueva_comida = $_POST['nueva_comida'];
        $query = "UPDATE paquete SET id_comida = '$nueva_comida' WHERE id_paquete = '$paquete'";
        if (mysqli_query($con, $query)) {
            $message = "Paquete modificado exitosamente.";
        } else {
            $message = "Error: " . mysqli_error($con);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Paquetes de Viaje</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Gestión de Paquetes de Viaje</h1>
    </header>

    <?php if (isset($message)) { ?>
        <p class="mensaje"><?php echo $message; ?></p>
    <?php } ?>

    <section>
        <h2>Agregar Paquete</h2>
        <form action="index.php" method="POST">
            <label for="viaje">Viaje:</label>
            <select name="viaje" id="viaje">
                <?php while($row = mysqli_fetch_assoc($result_viaje)) { ?>
                    <option value="<?php echo $row['id_viaje']; ?>"><?php echo $row['destino']; ?></option>
                <?php } ?>
            </select><br>

            <label for="hotel">Hotel:</label>
            <select name="hotel" id="hotel">
                <?php while($row = mysqli_fetch_assoc($result_hotel)) { ?>
                    <option value="<?php echo $row['id_hotel']; ?>"><?php echo $row['nombre']; ?></option>
                <?php } ?>
            </select><br>

            <label for="comida">Comida:</label>
            <select name="comida" id="comida">
                <?php while($row = mysqli_fetch_assoc($result_comida)) { ?>
                    <option value="<?php echo $row['id_comida']; ?>"><?php echo $row['tipo']; ?></option>
                <?php } ?>
            </select><br>

            <label for="transporte">Transporte:</label>
            <select name="transporte" id="transporte">
                <?php while($row = mysqli_fetch_assoc($result_transporte)) { ?>
                    <option value="<?php echo $row['id_transporte']; ?>"><?php echo $row['tipo_transporte']; ?> (<?php echo $row['compania']; ?>)</option>
                <?php } ?>
            </select><br>

            <input type="submit" name="agregar" value="Agregar Paquete">
        </form>
    </section>

    <section>
        <h2>Eliminar Paquete</h2>
        <form action="index.php" method="POST">
            <label for="paquete">Paquete:</label>
            <select name="paquete" id="paquete">
                <?php while($row = mysqli_fetch_assoc($result_paquete)) { ?>
                    <option value="<?php echo $row['id_paquete']; ?>">
                        <?php echo $row['destino']; ?> - <?php echo $row['hotel']; ?> - <?php echo $row['comida']; ?> - <?php echo $row['tipo_transporte']; ?>
                    </option>
                <?php } ?>
            </select><br>
            <input type="submit" name="eliminar" value="Eliminar Paquete">
        </form>
    </section>

    <section>
        <h2>Modificar Paquete</h2>
        <form action="index.php" method="POST">
            <label for="paquete">Paquete:</label>
            <select name="paquete" id="paquete">
                <?php while($row = mysqli_fetch_assoc($result_paquete)) { ?>
                    <option value="<?php echo $row['id_paquete']; ?>">
                        <?php echo $row['destino']; ?> - <?php echo $row['hotel']; ?> - <?php echo $row['comida']; ?> - <?php echo $row['tipo_transporte']; ?>
                    </option>
                <?php } ?>
            </select><br>

            <label for="nueva_comida">Nuevo Tipo de Comida:</label>
            <input type="text" name="nueva_comida" id="nueva_comida" required><br>

            <input type="submit" name="modificar" value="Modificar Paquete">
        </form>
    </section>

    <section>
        <h2>Consulta de Paquetes</h2>
        <table>
            <thead>
                <tr>
                    <th>Destino</th>
                    <th>Hotel</th>
                    <th>Comida</th>
                    <th>Transporte</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Repetir la consulta de paquetes para mostrar
                $result_paquete = mysqli_query($con, $query_paquete);
                while ($row = mysqli_fetch_assoc($result_paquete)) {
                    echo "<tr>";
                    echo "<td>" . $row['destino'] . "</td>";
                    echo "<td>" . $row['hotel'] . "</td>";
                    echo "<td>" . $row['comida'] . "</td>";
                    echo "<td>" . $row['tipo_transporte'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
    <script src="js/script.js"></script>
</body>
</html>

<?php
desconectar();
?>
