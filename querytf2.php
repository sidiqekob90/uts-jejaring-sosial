
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
            <a href="#">Pencarian Undang-undang</a>
          </li>
          <li class="breadcrumb-item active">Hasil Pencarian</li>
        </ol>
        
<form enctype="multipart/form-data" method="POST" action="querytf2.php">
Kata Kunci : <br>
  <input type="text" name="keyword"><br>
<input type=submit value=Submit>
</form>
<?php
////
function hitungsim($query) {
  //ambil jumlah total dokumen yang telah diindex (tbindex atau tbvektor), n
$host='localhost';
$user='id11041702_stbi';
$pass='mei271990';
$database='id11041702_stbi';

//echo "hitung sim";

$conn=mysql_connect($host,$user,$pass);
mysql_select_db($database);

  $resn = mysql_query("SELECT Count(*) as n FROM tbvektor");
  $rown = mysql_fetch_array($resn); 
  $n = $rown['n'];
  //echo "hasil tbvektor";
  
  print_r($resn);
  
  //terapkan preprocessing terhadap $query
  $aquery = explode(" ", $query);
  
  //hitung panjang vektor query
  $panjangQuery = 0;
  $aBobotQuery = array();
  
  for ($i=0; $i<count($aquery); $i++) {
    //hitung bobot untuk term ke-i pada query, log(n/N);
    //hitung jumlah dokumen yang mengandung term tersebut
    $resNTerm = mysql_query("SELECT Count(*) as N from tbindex WHERE Term like '%$aquery[$i]%'");
//    echo "query >SELECT Count(*) as N from tbindex WHERE Term like '%$aquery[$i]%'";
    $rowNTerm = mysql_fetch_array($resNTerm); 
    $NTerm = $rowNTerm['N'] ;
    
    $idf = log($n/$NTerm);
    
    //simpan di array   
    $aBobotQuery[] = $idf;
    
    $panjangQuery = $panjangQuery + $idf * $idf;    
  }
  
  $panjangQuery = sqrt($panjangQuery);
  
  $jumlahmirip = 0;
  
  //ambil setiap term dari DocId, bandingkan dengan Query
  $resDocId = mysql_query("SELECT * FROM tbvektor ORDER BY DocId");
  while ($rowDocId = mysql_fetch_array($resDocId)) {
  
    $dotproduct = 0;
      
    $docId = $rowDocId['DocId'];
    $panjangDocId = $rowDocId['Panjang'];
    
    $resTerm = mysql_query("SELECT * FROM tbindex WHERE DocId = '$docId'");
  //  echo "query ->SELECT * FROM tbindex WHERE DocId = '$docId'".'<br>';
    
    
    while ($rowTerm = mysql_fetch_array($resTerm)) {
      for ($i=0; $i<count($aquery); $i++) {
        //jika term sama
        //echo "1-->".$rowTerm['Term'];
      //  echo "2-->".  $aquery[$i].'<br>';
        
        if ($rowTerm['Term'] == $aquery[$i]) {
          $dotproduct = $dotproduct + $rowTerm['Bobot'] * $aBobotQuery[$i];   
    //      echo "hasil =".$dotproduct.'<br>';
      //    echo "1-->".$rowTerm['Term'];
      //  echo "2-->".  $aquery[$i].'<br>';
          
        } //end if
          else
          {
          }
      } //end for $i    
    } //end while ($rowTerm)
    
    if ($dotproduct != 0) {
      $sim = $dotproduct / ($panjangQuery * $panjangDocId); 
      //echo "insert >>INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', '$docId', $sim)";
      //simpan kemiripan > 0  ke dalam tbcache
      $resInsertCache = mysql_query("INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', '$docId', $sim)");
      $jumlahmirip++;
    } 
      
  if ($jumlahmirip == 0) {
    $resInsertCache = mysql_query("INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', 0, 0)");
  } 
  } //end while $rowDocId
  
    
} //end hitungSim()





////
$host='localhost';
$user='id11041702_stbi';
$pass='mei271990';
$database='id11041702_stbi';
if ($_POST) {
    $keyword=$_POST['keyword'];
} else {
    $keyword = "";
}
$conn=mysql_connect($host,$user,$pass);
mysql_select_db($database);
$resCache = mysql_query("SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC");
  $num_rows = mysql_num_rows($resCache);
  if ($num_rows >0) {

    //tampilkan semua berita yang telah terurut
    while ($rowCache = mysql_fetch_array($resCache)) {
      $docId = $rowCache['DocId'];
      $sim = $rowCache['Value'];
          
        //ambil berita dari tabel tbberita, tampilkan
        //echo ">>>SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'";
        $resBerita = mysql_query("SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'");
        $rowBerita = mysql_fetch_array($resBerita);
          
        $judul = $rowBerita['nama_file'];
        $berita = $rowBerita['deskripsi'];
          
        print($docId . ". (" . $sim . ") <font color=blue><b><a href=" . $judul . "> </b></font><br />");
        print($berita . "<hr /></a>");    
      
    }//end while (rowCache = mysql_fetch_array($resCache))
  }
    else
    {
    hitungsim($keyword);
    //pasti telah ada dalam tbcache   
    $resCache = mysql_query("SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC");
    $num_rows = mysql_num_rows($resCache);
    
    while ($rowCache = mysql_fetch_array($resCache)) {
      $docId = $rowCache['DocId'];
      $sim = $rowCache['Value'];
          
        //ambil berita dari tabel tbberita, tampilkan
        $resBerita = mysql_query("SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'");
        $rowBerita = mysql_fetch_array($resBerita);
          
        $judul = $rowBerita['nama_file'];
        $berita = $rowBerita['deskripsi'];
          
        print($docId . ". (" . $sim . ") <font color=blue><b><a href=" . $judul . "> </b></font><br />");
        print($berita . "<hr /></a>");
    
    } //end while
    }

?>
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
