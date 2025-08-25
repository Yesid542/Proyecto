<?php
    session_start();
    


        $config=include('../data/config.php');

        try{
            $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
            $conexion = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
        } catch(PDOException $error){
            $error = $error -> getMessage();
        }

    if (isset($_SESSION['id'])) {
        $usuario = $_SESSION['id'];

        if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['accion']=="persona") {
            $name= $_GET["name"];
            $email = $_GET["email"];
            $document = $_GET["document"];
            $phone = $_GET["phone"];
                try {
            // Preparar la consulta para evitar inyección SQL
            $stmt = $conexion->prepare('UPDATE persona SET Nombre=:nombre,Documento=:document,Telefono=:phone,Correo = :email WHERE IdPersona = :usuario');
            
            $stmt->bindParam(':nombre', $name, PDO::PARAM_STR);
            $stmt->bindParam(':document', $document, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();

            header('Location: /src/Rol/perfil.php?exito');
            
            }catch (PDOException $e) {
        // Manejo de errores en caso de fallas en la consulta
            exit('Error en la consulta: ' . $e->getMessage());
            }
        }
        if($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['accion']=="producto"){
            $description=$_GET['descripcion'];
            $nombre= $_GET['nombreTipo'];
            $price=$_GET['precio'];
            
            $idCalzado=$_GET['id'];

            

            try{
                $stmt=$conexion ->prepare('SELECT idTipoCalzado FROM tipoCalzado WHERE Nombre =:tipo');
                $stmt ->bindParam(':tipo',$nombre,PDO::PARAM_STR);
                $stmt -> execute();
                $tipo = $stmt->fetch(PDO::FETCH_ASSOC);
                $idTipo = $tipo['idTipoCalzado'];
            

            }catch (PDOException $e) {
                // Manejo de errores en caso de fallas en la consulta
                    exit('Error en la consulta: ' . $e->getMessage());
                    }

            try{
            $stmt = $conexion -> prepare('UPDATE calzado SET Descripcion=:descripcion,IdTipoCalzado=:id,Precio=:precio WHERE IdCalzado = :idCalzado');
            $stmt ->bindParam(':descripcion',$description,PDO::PARAM_STR);
            $stmt ->bindParam(':id',$idTipo,PDO::PARAM_INT);
            $stmt ->bindParam(':precio',$price,PDO::PARAM_INT);
            $stmt ->bindParam(':idCalzado',$idCalzado,PDO::PARAM_INT);

            $stmt -> execute();
            

            

            }catch (PDOException $e) {
                // Manejo de errores en caso de fallas en la consulta
                    exit('Error en la consulta: ' . $e->getMessage());
                    }

            header('location: ./carrito-master/editarProducto.php?id='.$idCalzado);
        }


        
        if(!empty($_FILES['imagen']['name'])&&isset($_POST['usuario'])) {
            $archivo_nombre = time() . "_" . basename($_FILES['imagen']['name']);
            $directorio = __DIR__ . "../../public/img/uploads/"; // Cambia la ruta según tu estructura.
    
            // Crear la carpeta si no existe
            if (!is_dir($directorio)) {
                if (!mkdir($directorio, 0777, true)) {
                    throw new Exception("No se pudo crear el directorio.");
                }
            }
    
            $archivo_destino = $directorio . $archivo_nombre;
    
            // Mover archivo y guardar la ruta relativa
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $archivo_destino)) {
                $archivo_path = "uploads/" . $archivo_nombre;
    
                // Guarda la ruta en la base de datos o úsala como prefieras
                $userImageUrl = htmlspecialchars($archivo_path);
            } else {
                throw new Exception("Error al subir la imagen.");
            }
            $stmt = $conexion->prepare('UPDATE persona SET archivo_nombre=:nombre,archivo_path=:ruta WHERE IdPersona=:usuario');
                
            $stmt->bindParam(':nombre', $archivo_nombre, PDO::PARAM_STR);
            $stmt->bindParam(':ruta', $archivo_path, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();

            header('location: ./Rol/perfil.php');
    
        }  
        if(!empty($_FILES['imagen']['name']) && isset($_POST['producto']) &&isset($_POST['id'])){
            $idCalzado = $_POST['id'];
            $archivo_nombre = time() . "_" . basename($_FILES['imagen']['name']);
            $directorio = __DIR__ . "/carrito-master/uploads/"; // Cambia la ruta según tu estructura.
    
            // Crear la carpeta si no existe
            if (!is_dir($directorio)) {
                if (!mkdir($directorio, 0777, true)) {
                    throw new Exception("No se pudo crear el directorio.");
                }
            }
    
            $archivo_destino = $directorio . $archivo_nombre;
    
            // Mover archivo y guardar la ruta relativa
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $archivo_destino)) {
                $archivo_path = "uploads/" . $archivo_nombre;
    
                // Guarda la ruta en la base de datos o úsala como prefieras
                $userImageUrl = htmlspecialchars($archivo_path);
            } else {
                throw new Exception("Error al subir la imagen.");
            }
            $stmt = $conexion->prepare('UPDATE calzado SET archivo_nombre=:nombre,archivo_path=:ruta WHERE IdCalzado=:idCalzado');
                
            $stmt->bindParam(':nombre', $archivo_nombre, PDO::PARAM_STR);
            $stmt->bindParam(':ruta', $archivo_path, PDO::PARAM_STR);
            $stmt->bindParam(':idCalzado', $idCalzado, PDO::PARAM_STR);
            $stmt->execute();


            header('location: ./carrito-master/editarProducto.php?id='.$idCalzado);

            
    
        }  
   
    }if($_POST['accion']=="contrasena"){
        $contrasena=$_POST['password'];
        $correo = $_POST['correo'];

        $stmt = $conexion->prepare('UPDATE persona SET Contrasena=:contrasena WHERE Correo=:correo');
            
            $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();

            header('Location: /src/InicioDeSesion/login.php?exito');
    }

?>
