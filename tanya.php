<?php include "conf/inc.koneksi.php"; ?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-book"></span> Buku Tamu</h3>
  </div>
  <div class="panel-body">
    <form class="form-horizontal" method="post" action="">
      <div class="form-group">
        <label class="col-sm-3 control-label">Nama</label>
        <div class="col-sm-9">
          <input class="form-control" autofocus required type="text" name="a" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">Email</label>
        <div class="col-sm-9">
          <input class="form-control" required type="email" name="b" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">Isi Pesan</label>
        <div class="col-sm-9">
          <textarea name="c" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <button name="kirim" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> Kirim</button>        
        </div>
      </div>
    </form>

    <?php
    if (isset($_POST['kirim'])) {
        $a = $koneksi->real_escape_string($_POST['a']);
        $b = $koneksi->real_escape_string($_POST['b']);
        $c = $koneksi->real_escape_string($_POST['c']);

        $stmt = $koneksi->prepare("INSERT INTO buku_tamu (nama, email, isi) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $a, $b, $c);

        if ($stmt->execute()) {
            echo "<script>alert('Berhasil Dikirim')</script>";
            echo "<meta http-equiv='refresh' content='0; url=?page=guest'>";
        } else {
            echo "<script>alert('Gagal Dikirim')</script>";
            echo "<meta http-equiv='refresh' content='0; url=?page=guest'>";
        }

        $stmt->close();
    }

    $koneksi->close();
    ?>
  </div>
</div>
