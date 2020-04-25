<?php

include_once "config.php";
include_once "entidades/venta.php";

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
$venta = new Venta();
$aVentas = $venta->cargarGrilla();

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lista de ventas</title>

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

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row pt-4">
                <h2>Lista de ventas
                </h2>
            </div>
            <div class="col-12">
                <table class="table table-hover border mt-2">
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Importe</th>                        
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr> 
                         <?php foreach ($aVentas as $venta) :; ?>
                    <tr>
                        <td><?php echo $venta->fecha; ?></td>
                        <td><?php echo $venta->nombre_cliente; ?></td>
                        <td><?php echo $venta->nombre_producto; ?></td>
                        <td><?php echo $venta->cantidad; ?></td>
                        <td><?php echo $venta->importe; ?></td>
                        <td><?php echo $venta->total; ?></td>
                        <td>
                            <a href="ventaform.php?id=<?php echo $venta->idventa; ?>">
                                <i class="fas fa-search"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>


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

</body>

</html>