<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";
include_once "entidades/venta.php";

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
$venta->cargarFormulario($_REQUEST);


if ($_POST) {
  if (isset($_POST['btnGuardar'])) {
    if (isset($_GET['id']) && $_GET['id'] > 0) {
      $venta->actualizar();
    } else {
      $venta->insertar();
    }
  } else if (isset($_POST['btnBorrar'])) {
    $venta->eliminar();
  }
} //POST
if (isset($_GET['id']) && $_GET['id'] > 0) {
  $venta->obtenerPorId();

} //GET

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

$producto = new Producto();
$aProductos = $producto->cargarGrilla();

if($_GET){
  
if(isset($_GET["do"]) && $_GET["do"] == "buscarProducto"){
  $idProducto = $_GET["id"];
  $producto = new Producto();
  $producto->idproducto = $idProducto; 
  $producto->obtenerPorId();
  echo json_encode($producto->precio);
  exit;
}
if (isset($_GET['id']) && $_GET['id'] > 0) {
  $producto->obtenerPorId();
}


}//GET 

?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>registro de producto</title>

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
        <h2>Venta</h2>
      </div>
      <form action="" method="POST">
        <div class="row">
          <div class="col-3 form-group">
            <label for="txtFecha">Fecha</label>
            <input type="date"class="form-control" name="txtFecha" id="txtFecha"
             value="<?php echo $venta->fecha ;?>" required>
          </div>
          <div class="col-9 form-group">
            <label for="lstCliente">Cliente</label>
            <select required="" class="form-control" name="lstCliente" id="lstCliente">
              <option value="" disabled selected>Seleccionar</option>
              <?php foreach ($aClientes as $cliente) : ?>
                <?php if ($cliente->idcliente == $venta->fk_idcliente) : ?>
                  <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre ;?></option>
                <?php else : ?>
                  <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre ;?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-8 form-group">
            <label for="lstProducto">Producto</label> 
            <select required="" class="form-control" name="lstProducto" id="lstProducto" onchange="fBuscarPrecio();">
              <option value="" disabled selected>Seleccionar</option>
              <?php foreach ($aProductos as $producto) : ?>
                <?php if ($producto->idproducto == $venta->fk_idproducto) : ?>
                  <option selected value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre ;?></option>
                <?php else : ?>
                  <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre ;?></option>
                <?php endif; ?>
            <?php endforeach ;?>
            </select>
          </div>
          <div class="col-2 form-group">
            <label for="txtCantidad">Cantidad</label>
            <input type="text" class="form-control input" id="txtCantidad" name="txtCantidad" onchange="fCalcularTotal();" value="<?php echo $venta->cantidad ;?>" required>
          </div>
          <div class="col-2 form-group">
            <label for="txtImporte" >Importe</label>
            <input type="text" class="form-control input" id="txtImporte" name="txtImporte" value="<?php echo $venta->importe ;?>" required >
          </div>
      
          <div class="col-2 form-group">
            <label for="txtTotal">Total</label>
            <input type="text" class="form-control input" id="txtTotal" name="txtTotal" value="<?php echo $venta->total ;?>" required>
          </div>

        </div>
        <div class="row pt-2">
          <a href="ventaform.php" class="btn btn-primary ml-3">Nuevo</a>
          <button type=" submit" class="btn btn-success ml-3" name="btnGuardar" id="btnGuardar">Guardar</button>
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
<script>
function fBuscarPrecio(){
    var idProducto = $("#lstProducto option:selected").val();
      $.ajax({
            type: "GET",
            url: "ventaform.php?do=buscarProducto",
            data: { id:idProducto },
            async: true,
            dataType: "json",
            success: function (respuesta) {
                $("#txtImporte").val(respuesta);
            }
        });

}

  function fCalcularTotal(){
    var precio = $('#txtImporte').val();
    var cantidad = $('#txtCantidad').val();
    var resultado = precio * cantidad;
    $("#txtTotal").val(resultado);
    
  }


</script>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>