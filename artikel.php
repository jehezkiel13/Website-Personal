<?php include "conf/inc.koneksi.php"; ?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span> Informasi</h3>
  </div>
  <div class="panel-body">
    <?php 
    $sql = "SELECT * FROM artikel WHERE ket='Y' ORDER BY id ASC LIMIT 10";
    $rs = $koneksi->query($sql);

    if ($rs->num_rows > 0) {
        while($row = $rs->fetch_assoc()) { ?>
          <a href="?page=read&id=<?php echo htmlspecialchars($row['id']); ?>" class="list-group-item">
            <img style="float:left;margin-right:20px;" src="news/<?php echo htmlspecialchars($row['foto']); ?>" class="image-rounded" width="120" height="80"/>
            <h4 class="list-group-item-heading"><?php echo htmlspecialchars($row['judul']); ?></h4>
            <p class="list-group-item-text-justify">
              <?php echo htmlspecialchars(substr($row['isi'], 0, 350)); ?>
            </p>
          </a>
    <?php 
        }
    } else {
        echo "<p>Tidak ada artikel ditemukan.</p>";
    }
    ?>
  </div>
</div>

<?php
$koneksi->close();
?>
