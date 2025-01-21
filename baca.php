<?php 
include "conf/inc.koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Mengambil dan menyaring nilai 'id' dari URL

if ($id > 0) {
    $sql = "SELECT * FROM artikel WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id); // Menggunakan parameter terikat untuk keamanan
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo htmlspecialchars($row['judul']); ?></h3>
  </div>
  <div class="panel-body">
    <p style="text-align:center;">
      <img src="news/<?php echo htmlspecialchars($row['foto']); ?>" class="image-rounded" width="400" height="300"/>
    </p>
    <p><?php echo nl2br(htmlspecialchars($row['isi'])); ?></p>
  </div>
</div>
<?php
    } else {
        echo "<p>Artikel tidak ditemukan.</p>";
    }

    $stmt->close();
} else {
    echo "<p>ID artikel tidak valid.</p>";
}

$koneksi->close();
?>
