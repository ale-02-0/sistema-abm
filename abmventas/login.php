<?php


ini_set('display_errors','1');
ini_set('display_startup_errors','1');
ini_set('error_reporting', E_ALL); 

include_once "config.php";
include_once "entidades/usuario.php";

session_start();

if ($_POST){

   //comprobamos valores de clave y usuario.
    $usuario= trim($_POST['txtUsuario']);
    $clave= trim($_POST['txtClave']);

    $entidadUsuario= new Usuario();
    $entidadUsuario->obtenerPorUsuario($usuario);

    
    //corroboramos datos. 
    if ($entidadUsuario->verificarClave($clave, $entidadUsuario->clave)){
   
      //redireccionamos a index. 
      $_SESSION['nombre']=$entidadUsuario->nombre;
      header("location: index.php");
    }else{
          //si es incorrecto
          $msg = "Usuario o clave incorrectos, verifique los datos ingresados.";
        } 
       
    //si no inicio sesion
    if ($usuario == "" && $clave == "" ){
        header("location: 404.php");
  }

}
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" 
  rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                  </div>
                  <?php if(isset($msg)): ?>
            <div class="row p-2">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        <?php echo $msg;?>
                    </div>
                </div>
            </div>
            <?php endif;?>
                  <form action=""class="user"method="POST">
                    <div class="form-group method">
                      <input type="txt" class="form-control form-control-user" id="txtUsuario" 
                      placeholder="Introduzca usuario..." name="txtUsuario" required
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="txtClave" 
                      placeholder=" Contraseña" name="txtClave" required>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Recordarme</label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block" name="btnIngresar" id="btnIngresar">Iniciar sesion</button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.php">¿Olvidaste tu contraseña?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.php">¡Crea una cuenta!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>