<?php

include_once "config.php";
include_once "entidades/categoria.php";
include_once "entidades/producto.php";

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
$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

//POST
if($_POST){
    if(isset($_POST["btnGuardar"])){
        if(isset($_GET["id"]) && $_GET["id"] > 0){
              //Actualizo un id existente
              $producto->actualizar();
        } else {
            //Es nuevo
            $producto->insertar();
        }
    } else if(isset($_POST["btnBorrar"])){
        $producto->eliminar();
    }
} 
//GET
if(isset($_GET["id"]) && $_GET["id"] > 0){
    $producto->obtenerPorId();
}
//CATEGORIA
$categoria= new Categoria();
$aCategorias=$categoria->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registro de producto</title>

    <!-- Custom fonts for this template-->

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once("menu_nav.php"); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <div class="row pt-4">
                <h2>Registro de producto</h2>
            </div>
            <form action="" method="POST">
                <div class="row">
                    <div class="col-8 form-group">
                        <label for="txtNombre">Producto</label>
                        <input type="text" class="form-control input" id="txtNombre" name="txtNombre"
                        value="<?php echo $producto->nombre ?>" required>
                    </div>
                    <div class="col-2 form-group">
                        <label for="txtPrecio">Precio</label>
                        <input type="text" class="form-control input" id="txtPrecio" name="txtPrecio"
                        value="<?php echo $producto->precio ?>"  required>
                    </div>
                    <div class="col-2 form-group">
                        <label for="txtCantidad">Cantidad</label>
                        <input type="text" class="form-control input" id="txtCantidad" name="txtCantidad" 
                        value="<?php echo $producto->cantidad ?>" required>
                    </div>
                    <div class="col-8 form-group">
                        <label for="txtDescripcion">Descripción</label>
                        <textarea type="text" class="form-control input" id="txtDescripcion" name="txtDescripcion" 
                        ><?php echo $producto->descripcion ?></textarea>
                    </div>
                    <div class="col-4 form-group">
                        <label for="lstCategoria">Categoría</label>
                        <select required="" class="form-control" name="lstCategoria" id="lstCategoria">
                            <option value="" disabled selected>Seleccionar</option>
                            <?php foreach ($aCategorias as $categoria) : ?>
                                
                                <?php if ($categoria->idtipoproducto == $producto->fk_idtipoproducto) : ?>
                                    <option selected value="<?php echo $categoria->idtipoproducto; ?>"><?php echo $categoria->nombre ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $categoria->idtipoproducto; ?>"><?php echo $categoria->nombre ?></option>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </select>
                    </div>
                   
                </div>
                <div class="row pt-2">
                    <a href="productoform.php" class="btn btn-primary ml-3">Nuevo</a>
                    <button type="submit" class="btn btn-success ml-3" name="btnGuardar" id="btnGuardar">Guardar</button>
                    <button type="submit" class="btn btn-danger ml-3" name="btnBorrar" id="btnBorrar">Borrar</button>
                </div>
            </form>


        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php include_once("footer.php") ?>
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
    <script>
        ClassicEditor
            .create( document.querySelector( '#txtDescripcion' ) )
            .catch( error => {
            console.error( error );
            } );
        </script>
</body>

</html>