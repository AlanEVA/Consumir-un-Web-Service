<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<?php
require __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;
//Crear el cliente para llamadas al servicio
//Debes cambiar el valor de base_uri a la dirección en donde esta tu servicio
//El valor de timeout, en este caso es para decir que despues de 5 segundos
//si el servicio no responde, se cancela el proceso.
$client = new Client([
    'base_uri' => 'http://localhost/webservice/index.php',
    'timeout'  => 5.0

]);
?>
<body class="hold-transition sidebar-mini">
<div class="container">
  <?php


  $myId=0;
  if(isset($_REQUEST['accion']))
    {
    //  header("Content-type: json/");
    //var_dump ($_REQUEST['accion']);
      switch($_REQUEST['accion'])
        {
          case 'Eliminar':
          echo $_GET['varId'];
          $res = $client->request('DELETE','',['query' => ['id' => $_GET['varId']]]);

          if ($res->getStatusCode() == '200')
          {
           echo $res->getBody();
            header ("location: index.php");
          }
          break;
          case 'Buscar':

        ?>


<div class="card">
  <div class="card-header">
    <h3 class="card-title">Resultado de la busqueda</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
      <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Telefóno</th>
        <th>email</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      ///////**** MOSTRAR TODOS LOS REGISTROS **** ///////
      //Hacer la llamada al metodo get, sin ningún parametro
      $res = $client-> request('GET','',['query' => ['id' => $_GET['id']]]);
      if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
      {

        $data = json_decode($res->getBody(),true);

        foreach ($data as $row)
        {

          ?>
          <tr>
            <td> <?php echo $row['id']; ?> </td>
          <td> <?php echo $row["nombre"]; ?> </td>
          <td> <?php echo $row["telefono"]; ?> </td>
          <td> <?php echo $row["email"]; ?></td>
          <td><a href="index.php?accion=Editar&id=<?php echo $row['id'];?>" style="margin-right: 30px;"> <i class = "fas fa-edit"></i></a>
            <a href ="index.php?accion=Eliminar&id=<?php echo $row['id'];?>"  class="text-danger borrarUsuario"> <i class="fas fa-trash"></i></a>

          </td>
        </tr>
<?php
        }
      }

      ?>



      </tbody>

    </table>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->



<?php


          break;

          case 'Registrar':
          ///////**** INSERTAR UN REGISTRO **** ///////
          //Hago la llamada al servicio rest, para insertar un articulo
          $contacto = ['nombre'=>$_REQUEST['nombre'],
                       'telefono'=>$_REQUEST['telefono'],
                       'email'=>$_REQUEST['email'],
                       'id'=>''
                      ];
          $res = $client->request('POST', '', ['form_params' => $contacto]);
          if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
          {
           echo "Se inserto un post (articulo)";
            header ("location: index.php");
          }
          break;

          case 'Editar':
          $myId =$_REQUEST['idEdit'];
          var_dump($myId);
          break;

          case 'Actualizar':
          $contacto = ['nombre'=>$_REQUEST['nombre'],
                     'telefono'=>$_REQUEST['telefono'],
                     'email'=>$_REQUEST['email'],
                     'id'=>$_REQUEST['id']
                    ];
                    var_dump($_REQUEST['id']);
                    var_dump($contacto);

        $res = $client->request('PUT','',['query' => $contacto]);
          var_dump($res);
        if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
          {
           //echo $res->getBody();
          // header ("location: index.php");
          }


          break;

          default:
          break;


        }
    }

   ?>


<!-- Content Wrapper. Contains page content -->
  <div class="content-container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <h1>Contactos</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- PARA AGREGAR LA CAJA DE BUSQUEDA Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">


      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline" method="get" action="#">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" id="id" name="id" type="search" placeholder="Escriba el Id del contacto a buscar" aria-label="Search">
                <input name="accion" type="hidden" value="Buscar">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" name="Bus" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>


      </ul>
    </nav>
    <!--FIN PARA AGREGAR LA CAJA DE BUSQUEDA  /.navbar -->
     </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <!-- FORMULARIO DE REGISTRO -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Registro de Contactos</h3>
              </div>
              <!-- /.card-header -->

              <!-- form start -->
              <form id="formRegistro" method="post" action="?accion=<?php echo ($myId > 0) ? 'Actualizar' : 'Registrar'; ?>">
                <input type="hidden" name="id" value="<?php  echo ($myId > 0) ? $_REQUEST['idEdit']: 0 ;?>" />
              <?php var_dump($myId); ?>
                <div class="card-body">
                  <div class="form-group">
                    <label for="Nombre">Nombre</label>
                    <input type="text" name="nombre" class="form-control"  placeholder="Ingresa un nombre" <?php if ($myId > 0) echo 'value="'.$_REQUEST['b'].'""'; ?>>
                  </div>
                  <div class="form-group">
                    <label for="Telefono">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"  placeholder="Ingresa un numero telefónico" value="<?php if ($myId > 0)  echo $_REQUEST['c'] ; ?>">
                  </div>
                  <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="text" name="email" class="form-control"  placeholder="Ingresa un correo electronico válido" value="<?php if ($myId > 0)  echo $_REQUEST['d'] ; ?>">
                  </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="<?php echo ($myId > 0) ? 'Actualizar' : 'Registrar'; ?>"><?php echo ($myId > 0) ? 'Actualizar' : 'Registrar';?></button>
                </div>
              </form>
            </div>

            <!-- FIN FORMULARIO DE REGISTRO -->


            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de contactos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Telefóno</th>
                    <th>email</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  ///////**** MOSTRAR TODOS LOS REGISTROS **** ///////
                  //Hacer la llamada al metodo get, sin ningún parametro
                  $res = $client->request('GET');
                  if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
                  {
                    //echo $res->getBody();
                    $data = json_decode($res->getBody(),true);
                    //print_r ($data);

                    //echo "<br>";

                    foreach ($data as $row)
                    {
                      ?>
                      <tr>
                        <td> <?php  $a=$row['id']; echo $a; ?> </td>
                      <td> <?php  $b=$row["nombre"]; echo $b; ?> </td>
                      <td> <?php  $c=$row["telefono"]; echo $c; ?> </td>
                      <td> <?php $d=$row["email"]; echo $d; ?></td>
                      <td>
                        <a href="index.php?accion=Editar&idEdit=<?php echo $a.'&'.'b='.$b.'&'.'c='.$c.'&'.'d='.$d; ?>" style="margin-right: 30px;"><i class = "fas fa-edit"></i></a>
                        <a href ="index.php?accion=Eliminar&varId=<?php echo $row['id'];?>"  class="text-danger borrarUsuario"> <i class="fas fa-trash"></i></a>

                      </td>
                    </tr>
<?php
                    }
                  }

                  ?>



                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Telefóno</th>
                    <th>email</th>
                    <th>Acciones</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.1.0
    </div>
    <strong>Copyright &copy; 2021 Desarrollo de Aplicaciones Web.</strong> Derechos Reservados.
  </footer>


</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Page specific script -->
<script>

  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#formRegistro').validate({
      rules: {
        Nombre: {
          required: true,
          minlength: 10
        },
        Email: {
          required: true,
          email: true
        },
        Telefono: {
          required: true,
          minlength: 10
        },

      },
      messages: {
        Nombre: {
          required: "Ingrese un nombre, por favor!",
          minlength: "La longitud minima es de 10 caracteres"
        },
        Email: {
          required: "Ingrese un correo electrónico, por favor",
          email: "Por favor ingrese un correo electrónico válido"
        },
      Telefono: {
          required: "Por favor proporcione un numero telefonico",
          minlength: "El numero telefonico dede contener 10 digitos"
        },
      },
  });
</script>
</body>
</html>
