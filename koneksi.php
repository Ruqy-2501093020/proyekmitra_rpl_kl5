
  <?php
    $koneksi = mysqli_connect("localhost","root","","perpustakaan_db");

    if($koneksi->connect_error){
        echo "Database error";
    }
    function redirect($url, $tipe, $teks)
{
  $_SESSION['pesan'] = [
    'tipe' => $tipe,
    'teks' => $teks
  ];
  header("Location: $url");
  exit;
}
?>