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

if (!isset($_SESSION["nombre_usuario"])) {
    header("Location: log in.php");
    exit();
}

$nombre_usuario = $_SESSION["nombre_usuario"];

$link = Conectarse();

$query = "SELECT * FROM Usuarios WHERE NOMBRE = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 's', $nombre_usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_info = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($_POST['eliminar'])) {
    $queryDelete = "DELETE FROM $tabla WHERE NOMBRE = ?";
    $stmt2 = mysqli_prepare($link, $queryDelete);
    mysqli_stmt_bind_param($stmt2, 's', $nombre_usuario);
    mysqli_stmt_execute($stmt2);
    
    if (mysqli_stmt_affected_rows($stmt2) > 0) {
        session_unset();
        session_destroy();

        header("Location: log in.php");
        exit;
    }
    
    mysqli_stmt_close($stmt2);
}

if(isset($_POST['guardarEdicion'])) {
    $nombreForm = $_POST['NombreForm'];
    $direccionForm = $_POST['DireccionForm'];
    $nuevaContrasena = $_POST['NuevaContrasena'];
    
    $contrasena = empty($nuevaContrasena) ? $user_info['PASSWORD'] : $nuevaContrasena;
    $queryUpdate = "UPDATE $tabla SET NOMBRE = ?, DIRECCION = ?, PASSWORD = ? WHERE NOMBRE = ?";
    $stmt = mysqli_prepare($link, $queryUpdate);
    mysqli_stmt_bind_param($stmt, 'ssss', $nombreForm, $direccionForm, $contrasena, $nombre_usuario);
    $resultUpdate = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($resultUpdate) {
    $_SESSION["nombre_usuario"] = $nombreForm;

    $queryAfterUpdate = "SELECT * FROM $tabla WHERE NOMBRE = ?";
    $stmtAfterUpdate = mysqli_prepare($link, $queryAfterUpdate);
    mysqli_stmt_bind_param($stmtAfterUpdate, 's', $nombreForm);
    mysqli_stmt_execute($stmtAfterUpdate);
    $resultAfterUpdate = mysqli_stmt_get_result($stmtAfterUpdate);
    $user_info = mysqli_fetch_assoc($resultAfterUpdate);
    mysqli_stmt_close($stmtAfterUpdate);

    header("Location: perfil.php");
    exit;

    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="imagenes/icono.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.css">
</head>
<body style="background-color: #f5ebe9;">
    <header class="header">
  <div class="header-content">
    <div class="logo">
      <a href="Paginap.html"><img src="diamante.png"></a>
    </div>
    <nav>
      <ul class="nav-links">
        <li><a href="collares.html">COLLARES</a></li>
        <li><a href="pulseras.html">PULSERAS</a></li>
        <li><a href="anillos.html">ANILLOS</a></li>
        <li><a href="aretes.html">ARETES</a></li>
      </ul>
    </nav>
    <div class="user">
      <a><button class="dropdown-button"><ion-icon name="person-outline"></ion-icon></button></a>
      <div class="dropdown-menu">
        <a href="log out.php">Cerrar sesión</a>
      </div>
    </div>
  </div>
<table class="enca" width="100%" border="1">
        <tr>
            <td align="center"><img src="imagenes/loguito.jpg" style="width: 250px; height: 150px;" alt="100%"></td>  
        </tr>
    </table>
</header>
<div class="content">
	<center><h2>INFORMACIÓN</h2></center>
		<table border="1" style="width: 50%; margin: 20px auto;">
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Contraseña</th>
            </tr>
            <tr>
                <td align="center"><?php echo isset($user_info['NOMBRE']) ? $user_info['NOMBRE'] : ""; ?></td>
<td align="center"><?php echo isset($user_info['DIRECCION']) ? $user_info['DIRECCION'] : ""; ?></td>
                <td align="center"> ********* </td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 20px;">
        	<form method="POST" class="botones">
        		<button type="submit" name="editar">Editar</button>
        		<button type="submit" name="eliminar">Eliminar</button>
        	</form>
    	</div>

		<?php
    		if (isset($_POST['editar'])) {
    			$queryUpdate = "SELECT NOMBRE, DIRECCION, PASSWORD FROM $tabla WHERE NOMBRE = ?";
    			$stmt3 = mysqli_prepare($link, $queryUpdate);
    			mysqli_stmt_bind_param($stmt3, 's', $nombre_usuario);
    			mysqli_stmt_execute($stmt3);
    			$resultUpdate = mysqli_stmt_get_result($stmt3);
    			$rowSelectByID = mysqli_fetch_assoc($resultUpdate);

    if ($rowSelectByID) {
?>
	<center>
		<br><br>
        <form action="" method="post" class="form">
    		Nombre: <input type="text" name="NombreForm" value="<?= $rowSelectByID['NOMBRE']; ?>"> <br> <br>
    		Dirección: <input type="text" name="DireccionForm" value="<?= $rowSelectByID['DIRECCION']; ?>"> <br> <br>
    		Nueva Contraseña: <input type="password" name="NuevaContrasena" placeholder="Deja en blanco para mantener la actual"> <br> <br>
    		<input type="submit" name="guardarEdicion" value="Guardar">
		</form>
	</center>
<?php
}
    mysqli_stmt_close($stmt3);
}
?>
</div>


<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script> 
</body>
</html>