<?php 
include "conf/inc.koneksi.php";

// Baca variabel Form
$nama = $_REQUEST['nama'];
$kelamin = $_REQUEST['jk'];
$alamat = $_REQUEST['alamat'];
$pekerjaan = $_REQUEST['pekerjaan'];

// Validasi Form
if (trim($nama) == "") {
    include "konsultasi.php";
    echo "Nama belum diisi, ulangi kembali";
} elseif (trim($alamat) == "") {
    include "konsultasi.php";
    echo "Alamat masih kosong, ulangi kembali";
} elseif (trim($pekerjaan) == "") {
    include "konsultasi.php";
    echo "Pekerjaan masih kosong, ulangi kembali";
} else {
    $NOIP = $_SERVER['REMOTE_ADDR'];

    // Menghapus data lama berdasarkan noip
    $sqldel = "DELETE FROM tmp_pasien WHERE noip=?";
    $stmt = $koneksi->prepare($sqldel);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $stmt->close();

    // Menyisipkan data baru ke tmp_pasien
    $sql = "INSERT INTO tmp_pasien (nama, kelamin, alamat, pekerjaan, noip, tanggal) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sssss", $nama, $kelamin, $alamat, $pekerjaan, $NOIP);
    $stmt->execute();
    $stmt->close();

    // Menghapus data lama berdasarkan noip di tabel lain
    $sqlhapus = "DELETE FROM tmp_solusi WHERE noip=?";
    $stmt = $koneksi->prepare($sqlhapus);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $stmt->close();

    $sqlhapus2 = "DELETE FROM tmp_analisa WHERE noip=?";
    $stmt = $koneksi->prepare($sqlhapus2);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $stmt->close();

    $sqlhapus3 = "DELETE FROM tmp_gejala WHERE noip=?";
    $stmt = $koneksi->prepare($sqlhapus3);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $stmt->close();

    echo "<meta http-equiv='refresh' content='0; url=index.php?page=start'>";
}
?>
