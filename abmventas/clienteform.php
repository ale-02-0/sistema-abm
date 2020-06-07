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


$cliente = new Cliente();

if ($_POST) {
    $cliente = new Cliente();
    $cliente->cargarFormulario($_REQUEST);

    if (isset($_POST["btnGuardar"])) {
        $cliente->insertar();

        for ($i = 0; $i < count($_POST["txtTipo"]); $i++) {
            $domicilio = new Domicilio();
            $domicilio->fk_tipo = $_POST["txtTipo"][$i];
            $domicilio->fk_idcliente = $cliente->idcliente;
            $domicilio->fk_idlocalidad = $_POST["txtLocalidad"][$i];
            $domicilio->domicilio = $_POST["txtDomicilio"][$i];
            $domicilio->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        $cliente->eliminar();
    } else if (isset($_POST["btnGuardar"])) {
        $cliente->actualizar();
    }
}


if ($_GET) {

    if (isset($_GET["do"]) && $_GET["do"] == "buscarLocalidad") {
        $idProvincia = $_GET["id"];
        $localidad = new Localidad();
        $aLocalidad = $localidad->obtenerPorProvincia($idProvincia);
        echo json_encode($aLocalidad);
        exit;
    }

    if (isset($_GET["id"]) && $_GET["id"] > 0) {
        $id = $_GET["id"];
        $cliente->obtenerPorId($id);
    }

    if (isset($_GET["do"]) && $_GET["do"] == "cargarGrilla") {
    }

    $idCliente = $_GET['idCliente'];
    $request = $_REQUEST;

    $entidad = new Domicilio();
    $aDomicilio = $entidad->obtenerFiltrado($idCliente);

    $data = array();

    $inicio = $request['start'];
    $registros_por_pagina = $request['length'];

    if (count($aDomicilio) > 0)
        $cont = 0;
    for ($i = $inicio; $i < count($aDomicilio) && $cont < $registros_por_pagina; $i++) {
        $row = array();
        $row[] = $aDomicilio[$i]->tipo;
        $row[] = $aDomicilio[$i]->provincia;
        $row[] = $aDomicilio[$i]->localidad;
        $row[] = $aDomicilio[$i]->domicilio;
        $cont++;
        $data[] = $row;
    }

    $json_data = array(
        "draw" => intval($request['draw']),
        "recordsTotal" => count($aDomicilio), //cantidad total de registros sin paginar
        "recordsFiltered" => count($aDomicilio), //cantidad total de registros en la paginacion
        "data" => $data
    );
    echo json_encode($json_data);
    exit;
}


$provincia = new Provincia();
$aProvincias = $provincia->obtenerTodos();

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
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--tabla-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Custom styles for this template-->

    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once("menu_nav.php"); ?>
        <!-- End of Topbar -->


        <!-- Modal -->

        <div class="modal fade" id="modalDomicilio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Domicilio</h5>
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
                                    <?php foreach ($aProvincias as $prov) : ?>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="fAgregarDomicilio();">Agregar</button>
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
                                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalDomicilio">Agregar</button>
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

    <script>
$(document).ready( function () {

    var idCliente = '<?php echo isset($cliente) && $cliente->idcliente > 0? $cliente->idcliente : 0 ?>';
    var dataTable = $('#grilla').DataTable({
        "processing": false,
        "serverSide": true,
        "bFilter": true,
        "bInfo": true,
        "bSearchable": true,
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "ajax": "clienteform.php?do=cargarGrilla&idCliente=" + idCliente
    });
} );

 function fBuscarLocalidad(){
            idProvincia = $("#lstProvincia option:selected").val();
            $.ajax({
                type: "GET",
                url: "clienteform.php?do=buscarLocalidad",
                data: { id:idProvincia },
                async: true,
                dataType: "json",
                success: function (respuesta) {
                    $("#lstLocalidad option").remove();
                    $("<option>", {
                        value: 0,
                        text: "Seleccionar",
                        disabled: true,
                        selected: true
                    }).appendTo("#lstLocalidad");
                
                    for (i = 0; i < respuesta.length; i++) {
                        $("<option>", {
                            value: respuesta[i]["idlocalidad"],
                            text: respuesta[i]["nombre"]
                            }).appendTo("#lstLocalidad");
                        }
                    $("#lstLocalidad").prop("selectedIndex", "0");
                }
            });
        }

        function fAgregarDomicilio(){
            var grilla = $('#grilla').DataTable();
            grilla.row.add([
                $("#lstTipo option:selected").text() + "<input type='hidden' name='txtTipo[]' value='"+ $("#lstTipo option:selected").val() +"'>",
                $("#lstProvincia option:selected").text() + "<input type='hidden' name='txtProvincia[]' value='"+ $("#lstProvincia option:selected").val() +"'>",
                $("#lstLocalidad option:selected").text() + "<input type='hidden' name='txtLocalidad[]' value='"+ $("#lstLocalidad option:selected").val() +"'>",
                $("#txtDireccion").val() + "<input type='hidden' name='txtDomicilio[]' value='"+$("#txtDireccion").val()+"'>"
            ]).draw();
            $('#modalDomicilio').modal('toggle');
            limpiarFormulario();
        }

        function limpiarFormulario(){
            $("#lstTipo").val(0);
            $("#lstProvincia").val(0);
            $("#lstLocalidad").val(0);
            $("#txtDireccion").val("");
        }
</script>

</body>

</html>