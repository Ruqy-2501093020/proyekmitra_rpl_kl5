<?php
include "koneksi.php";


$pesan = "";
if (isset($_POST['simpan'])) {
    $alamat = ($_POST['alamat']);
    $link   = ($_POST['link_maps']);
    $embed  = ($_POST['embed_maps']);
    mysqli_query($koneksi, "UPDATE lokasi SET alamat='$alamat', link_maps='$link', embed_maps='$embed' WHERE id=1");
    $pesan = "Lokasi berhasil disimpan";
}

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kontrakan WHERE lokasi"));
?>
<h2>Lokasi</h2>
<a href="lokasi.php" class="btn btn-success mb-3">Lokasi Baru</a>
<?php if ($pesan != "") { echo "<div class='pesan'>$pesan</div>"; } ?>

<form method="post">
  <label>Alamat <input type="text" name="alamat" value="<?php echo h($data['alamat']); ?>"></label>
  <label>Link "Lihat di google Maps" <input type="text" name="link_maps" value="<?php echo h($data['link_maps']); ?>"></label>
  <label>Link embed peta (Google Maps > Bagikan > Sematkan peta, ambil isi src)
    <input type="text" name="embed_maps" value="<?php echo h($data['embed_maps']); ?>">
  </label>
  <p><button class="tombol" type="submit" name="simpan">Simpan</button></p>
</form>

<?php include "index.php"; ?>