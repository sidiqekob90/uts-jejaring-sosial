<?php
//membuat koneksi ke database
$servername = "localhost";
$username = "id11041702_stbi";
$password = "mei271990";
$dbname = "id11041702_stbi";

$connection = mysqli_connect($servername, $username, $password, $dbname);
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.php">Pencarian Dokumen UU</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item ">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Beranda</span>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="upload.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Hasil Upload</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="hasil_tokenisasi.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Hasil Tokenisasi</span></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="querytf2.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Pencarian Undang-Undang</span></a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Tokenisasi</a>
          </li>
          <li class="breadcrumb-item active">Hasil Tokenisasi</li>
        </ol>
        
<table id="rowhover" class="isi"> 
<tr>
    <th> Nama File </th>
    <th> Tokenisasi </th>
    <th> Stemming Nazief Adriani </th>
</tr>

<?php  
// Perintah untuk menampilkan data
$query="SELECT * FROM dokumen" ;  //menampikan SEMUA

 $sql = mysqli_query($connection, $query);

// perintah untuk membaca dan mengambil data dalam bentuk array
while ($data = mysqli_fetch_array ($sql)) {
$id = $data['dokid'];
?>
<tr>
                 
        <td>
            <?php echo $data['nama_file']; ?>
        </td>
        <td>
            <?php echo $data['token']; ?>
        </td>
        <td>
            <?php echo $data['tokenstem']; ?>
        </td>

 
 <?php       
}

?>

</table>
</div>

</div>
        </div>
        </div>
        <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © Your Website 2019</span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

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

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>

</body>

</html>
