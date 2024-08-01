<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="imagenes/icono.png">
    <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>JOYERIA JENIFER</title>
</head>
<body>
    <?php
    $host = "localhost";
    $puerto = "3306";
    $usuario = "root";
    $contrasena = "";
    $baseDeDatos = "Prueba";
    $tabla = "Usuarios";

    function Conectarse (){
        global $host, $puerto, $usuario, $contrasena, $baseDeDatos, $tabla;
        try{
            $link = mysqli_connect($host.":".$puerto, $usuario, $contrasena);
        } catch (Exception $e){
            die("Error conectando a la base de datos. <br> : " .mysqli_connect_error());
        }
        
        try{
            mysqli_select_db($link, $baseDeDatos);
        } catch (Exception $e){
            die("Error seleccionando la base de datos.<br> : " .mysqli_connect_error());
        }

        return $link;
    }

    $link = Conectarse();

    if($_POST){
        $queryInsert = "INSERT INTO $tabla (Nombre, Direccion, Password) VALUES ('".$_POST['NombreForm']."','".$_POST['DireccionForm']."','".$_POST['PasswordForm']."');";
            $resultInsert = mysqli_query($link, $queryInsert);
    }

    $query = "SELECT NOMBRE, DIRECCION, PASSWORD FROM $tabla;";
    $result = mysqli_query($link, $query);
    ?>

    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="post">
                    <h2>Registro</h2>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" id="nombre" name="NombreForm" required>
                        <label for="nombre">Usuario</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="text" id="direccion" name="DireccionForm" required>
                        <label for="direccion">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" id="password" name="PasswordForm" required>
                        <label for="password">Contraseña</label>
                    </div>
                    <button name="enviar" type="submit">Registrarse</button>
                    <div class="register">
                        <p><a href="log in.php">Ya tengo cuenta</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php
    if (isset($_POST['enviar'])) {
         header("Location: log in.php");
    }
    ?>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>