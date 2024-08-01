<?php
session_start();

$host = "localhost";
$puerto = "3306";
$usuario = "root";
$contrasena = "";
$baseDeDatos = "Prueba";
$tabla = "Usuarios";

function Conectarse(){
    global $host, $puerto, $usuario, $contrasena, $baseDeDatos, $tabla;
    $link = mysqli_connect($host.":".$puerto, $usuario, $contrasena);
    if (!$link) {
        die("Error conectando a la base de datos: " . mysqli_connect_error());
    }

    mysqli_select_db($link, $baseDeDatos) or die("Error seleccionando la base de datos: " . mysqli_error($link));

    return $link;
}

$link = Conectarse();

if (isset($_POST['submit'])) {
    $usuario = trim($_POST['NombreForm']);
    $contraseña = trim($_POST['PasswordForm']);

    $query = "SELECT *
              FROM Usuarios
              WHERE NOMBRE = ? AND PASSWORD = ?";

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $usuario, $contraseña);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['nombre_usuario'] = $usuario;
        header("Location: Paginap.html");
        exit;
    } else {
        $error_message = "La contraseña o el nombre de usuario es incorrecto.";
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="imagenes/icono.png">
    <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>JOYERIA JENIFER</title>
  <style>
    .error-popup {
    display: none;
    position: fixed;
    top: 50px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #CCCCCC;
    color: #000;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    z-index: 1000;
}
  </style>
</head>
<body>

    <?php
    if (isset($error_message)) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var errorPopup = document.getElementById("error-popup");
                errorPopup.innerHTML = "'.$error_message.'";
                errorPopup.style.display = "block";
                setTimeout(function() {
                    errorPopup.style.display = "none";
                }, 5000); // Ajusta la duración (en milisegundos) según sea necesario
            });
        </script>';
    }
    ?>

    <div id="error-popup" class="error-popup"></div>

    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="post">
                    <h2>Iniciar sesión</h2>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" id="nombre" name="NombreForm" required>
                        <label for="nombre">Usuario</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" id="password" name="PasswordForm" required>
                        <label for="password">Contraseña</label>
                    </div>
                    <button type="submit" name="submit">Iniciar sesión</button>
                    <div class="register">
                        <p>Si no tiene cuenta... <a href="sign up.php">Registro</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<?php
if (isset($error_message)) {
    echo "<p>$error_message</p>";
}
?>
</body>
</html>