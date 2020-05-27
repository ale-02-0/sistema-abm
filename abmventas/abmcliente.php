<?php
//print_r(ini_set('error_reporting', E_ALL)); 

if (file_exists("clientes.txt")) {
    $jsonClientes = file_get_contents("clientes.txt");

    $aClientes = json_decode($jsonClientes, true);
} else {
    $aClientes = array();
}

$pos = isset($_GET["pos"]) ? $_GET["pos"] : "";

if ($_POST) {
    /*ACA DEFINI VARIABLES _POST*/
    $dni = $_POST["txtDni"];
    $nombre = $_POST["txtNombre"];
    $telefono = $_POST["txtTelefono"];
    $correo = $_POST["txtCorreo"];

    if (isset($_GET["do"]) && $_GET["do"] == "edit") {

        $aClientes[$pos] = array(
            "dni" => $dni,
            "nombre" => $nombre,
            "telefono" => $telefono,
            "correo" => $correo
        );

        /*CONVERTIR ARRAY A JSON */
        $jsonClientes = json_encode($aClientes);
        /* GUARDAR JSON EN ARCHIVO*/
        file_put_contents("clientes.txt", $jsonClientes);

        $msg = "Datos guardados";
    } else {

        $aClientes[] = array(
            "dni" => $dni,
            "nombre" => $nombre,
            "telefono" => $telefono,
            "correo" => $correo
        );

        /*CONVERTIR ARRAY A JSON */
        $jsonClientes = json_encode($aClientes);
        /* GUARDAR JSON EN ARCHIVO*/
        file_put_contents("clientes.txt", $jsonClientes);

        $msg = "Datos guardados";
    }
}

//elimando variables con unset, elimandola de la tabla
if (isset($_GET["do"]) && $_GET["do"] == "delete") {

    unset($aClientes[$pos]);
    $jsonClientes = json_encode($aClientes);
    file_put_contents("clientes.txt", $jsonClientes);
    $msgEliminado = "Datos eliminados";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="css/font_awesome/css/all.css" rel="stylesheet">
    <link href="css/font_awesome/css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
    <title>Registro de cliente</title>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">ABM Ventas</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Cliente <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Ventas</a>
                </li>
            </ul>
    </nav>
    <div class="container mt-4 pt-4">
        <div class="col-">
            <div class="row mt-4">
                <h1>Registro de clientes</h1>
            </div>
        </div>
        <div class="col-12">
            <div class="row-mt-2">
                <!--ALERTA DATOS GUARDADOS-->
                <?php if (isset($msg) && $msg != "") : ?>
                    <div class="row p-2">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                <?php echo $msg; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!--ALERTA DATOS ELIMINADOS-->
                <?php if (isset($msgEliminado) && $msgEliminado != "") : ?>
                    <div class="row p-2">
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                <?php echo $msgEliminado; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="col-12 form-group">
                            <label for="txtDni">DNI</label>
                            <input type="text" class="form-control" id="txtDni" name="txtDni" 
                                required value="<?php echo isset($aClientes[$pos]["dni"]) ? $aClientes[$pos]["dni"] : ""; ?>">
                        </div>
                        <div class="col-12 form-group">
                            <label for="txtNombre">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre" 
                                required value="<?php echo isset($aClientes[$pos]["nombre"]) ? $aClientes[$pos]["nombre"] : ""; ?>">
                        </div>
                        <div class="col-12 form-group">
                            <label for="txtTelefono">Teléfono</label>
                            <input type="text" class="form-control" id="txtTelefono" name="txtTelefono"
                                required value="<?php echo isset($aClientes[$pos]["telefono"]) ? $aClientes[$pos]["telefono"] : ""; ?>">
                                                                                                                           
                        </div>
                        <div class="col-12 form-group">
                            <label for="txtCorreo">Correo</label>
                            <input type="email" class="form-control" id="txtCorreo" name="txtCorreo" 
                                required value="<?php echo isset($aClientes[$pos]["correo"]) ? $aClientes[$pos]["correo"] : ""; ?>">
                                                                                                                            
                        </div>
                        <div class="col-12 form-group">
                            <label for="">Archivo</label>
                            <input type="file" class="form-control" name="Archivo" accept=".pdf,.png,.jpg">
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
                        <div class="col-12">
                            <button type="submit" name="btnGuardar" class="btn btn-primary">Guardar</button>
                            
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>