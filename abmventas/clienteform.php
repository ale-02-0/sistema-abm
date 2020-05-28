<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/provincia.entidad.php";
include_once "entidades/localidad.entidad.php";
include_once "entidades/domicilio.entidad.php";


//SESION DE USUARIO
session_start();
if (!isset($_SESSION['nombre'])) {
    header("location: login.php");
}
if ($_POST) {
    if (isset($_POST['btnCerrar'])) {
        session_destroy();
        header("location: login.php");
    }
}

//POO
$cliente = new Cliente();
$cliente->cargarFormulario($_REQUEST);
//POST
if ($_POST) {
    if (isset($_POST['btnGuardar'])) {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            $cliente->actualizar();
        } else {
            $cliente->insertar();
            print_r($_POST['btnGuardar']);
            exit;
        }
    } else if (isset($_POST['btnBorrar'])) {
        $cliente->eliminar();
    }
}
//GET 
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $cliente->obtenerPorId();
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

    <title>Registro Cliente</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once("menu_nav.php"); ?>
        <!-- End of Topbar -->
        <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
            <div class="col-12 form-group">
                <label for="lstTipo">Tipo:</label>
                <select name="lstTipo" id="lstTipo" class="form-control">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="1">Personal</option>
                    <option value="2">Laboral</option>
                    <option value="3">Comercial</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 form-group">
                <label for="lstProvincia">Provincia:</label>
                <select name="lstProvincia" id="lstProvincia" onchange="fBuscarLocalidad();" class="form-control">
                    <option value="" disabled selected>Seleccionar</option>
                    <?php foreach($aProvincias as $prov): ?>
                        <option value="<?php echo $prov->idprovincia; ?>"><?php echo $prov->nombre; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 form-group">
                <label for="lstLocalidad">Localidad:</label>
                <select name="lstLocalidad" id="lstLocalidad" class="form-control">
                    <option value="" disabled selected>Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 form-group">
                <label for="txtDireccion">Dirección:</label>
                <input type="text" name="" id="txtDireccion" class="form-control">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
        <div class="container-fluid">

            <div class="row pt-4">
                <h2>Registro de clientes</h2>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-6 form-group">
                            <label for="txtCuit">Cuit</label>
                            <input type="text" class="form-control input" id="txtCuit" name="txtCuit" value="<?php echo $cliente->cuit ?>" required>
                        </div>
                        <div class="col-6 form-group">
                            <label for="txtNombre">Nombre</label>
                            <input type="text" class="form-control input" id="txtNombre" name="txtNombre" value="<?php echo $cliente->nombre ?>" required>
                        </div>
                        <div class="col-6 form-group">
                            <label for="txtFechaNac">Fecha de nacimiento</label>
                            <input type="date" class="form-control input" id="txtFechaNac" name="txtFechaNac" value="<?php echo  $cliente->fecha_nac ?>">
                        </div>
                        <div class="col-6 form-group">
                            <label for="txtTelefono">Teléfono</label>
                            <input type="text" class="form-control input" id="txtTelefono" name="txtTelefono" value="<?php echo  $cliente->telefono ?>">
                        </div>
                        <div class="col-6 form-group">
                            <label for="txtEmail">Correo</label>
                            <input type="email" class="form-control input" id="txtEmail" name="txtEmail" value="<?php echo $cliente->correo ?>" required>
                        </div>
                    </div>
                    <div class="row">
        <div class="col-12">  
        <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Domicilios
                    <div class="pull-right">
                        <button type="button" class="btn btn-secondary fa fa-plus-circle" data-toggle="modal" data-target="#modalDomicilio">Agregar</button>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="grilla" class="display" style="width:98%">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Provincia</th>
                                <th>Localidad</th>
                                <th>Dirección</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table> 
                 </div>
            </div>          
        </div>
    </div>                
                        <div class="row pt-2">
                            <a href="clienteform.php" class="btn btn-primary ml-3">Nuevo</a>
                            <button type=" submit" class="btn btn-success ml-3" name="btnGuardar" id="btnGuardar">Guardar</button>
                            <button type="submit" class="btn btn-danger ml-3" name="btnBorrar" id="btnBorrar">Borrar</button>
                            <button type="button" class="btn btn-primary ml-3" name="btnModal" id="btnModal"data-toggle="modal" data-target="#exampleModal">Domicilio</button>
       

                        </div>
                        
                </form>
            </div>


        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include_once("footer.php") ?>
    </footer>
    <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
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