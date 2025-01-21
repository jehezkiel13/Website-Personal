<?php
include "conf/inc.koneksi.php";

// Mendapatkan No IP Lokal
$NOIP = $_SERVER['REMOTE_ADDR'];

// Perintah Ambil data analisa_hasil
$sql = "SELECT analisa_hasil.*, solusi.*
        FROM analisa_hasil
        JOIN solusi ON solusi.kd_solusi = analisa_hasil.kd_solusi
        WHERE analisa_hasil.noip = ?
        ORDER BY analisa_hasil.id DESC LIMIT 1";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $NOIP);
$stmt->execute();
$qry = $stmt->get_result();
$data = $qry->fetch_assoc();

// Perintah Ambil data tmp_pasien
$sql2 = "SELECT * FROM tmp_pasien WHERE noip = ?";
$stmt2 = $koneksi->prepare($sql2);
$stmt2->bind_param("s", $NOIP);
$stmt2->execute();
$qry2 = $stmt2->get_result();
$data2 = $qry2->fetch_assoc();

// Membuat hasil Pria atau Wanita
$kelamin = ($data2['kelamin'] == "P") ? "Pria" : "Wanita";
?>

<body onload="window.print();" layout="Portrait">

<table width="100%" border="0" cellpadding="3" cellspacing="1">
    <tr align="center">
        <td colspan="3">
            <font color="#d1ad2e"><b>HASIL DIAGNOSIS PENYAKIT</b></font>
            <hr color="#d1ad2e">
        </td>
    </tr>
    <tr>
        <td colspan="3"><br><br><b>DATA PASIEN</b></td>
    </tr>
    <tr>
        <td width="86">Nama </td><td> : </td>
        <td width="989"><?php echo htmlspecialchars($data2['nama']); ?></td>
    </tr>
    <tr>
        <td>Kelamin </td><td> : </td>
        <td><?php echo htmlspecialchars($kelamin); ?></td>
    </tr>
    <tr>
        <td>Alamat </td><td> : </td>
        <td><?php echo htmlspecialchars($data2['alamat']); ?></td>
    </tr>
    <tr>
        <td>Pekerjaan </td><td> : </td>
        <td><?php echo htmlspecialchars($data2['pekerjaan']); ?></td>
    </tr>
</table>

<table width="100%" border="0" cellpadding="2" cellspacing="1">
    <tr>
        <td colspan="2"><br><br><b>HASIL DIAGNOSIS</b></td>
    </tr>
    <tr>
        <td width="86">Penyakit </td>
        <td width="689"><?php echo htmlspecialchars($data['nm_solusi']); ?></td>
    </tr>
    <tr>
        <td valign="top">Gejala </td>
        <td>
            <?php
            // Menampilkan Daftar Gejala
            $sql_gejala = "SELECT gejala.* FROM gejala
                           JOIN rule ON gejala.kd_gejala = rule.kd_gejala
                           WHERE rule.kd_solusi = ?
                           ORDER BY gejala.kd_gejala";
            $stmt_gejala = $koneksi->prepare($sql_gejala);
            $stmt_gejala->bind_param("s", $data['kd_solusi']);
            $stmt_gejala->execute();
            $qry_gejala = $stmt_gejala->get_result();
            $i = 1;
            while ($hsl_gejala = $qry_gejala->fetch_assoc()) {
                echo $i . ". " . htmlspecialchars($hsl_gejala['nm_gejala']) . "<br>";
                $i++;
            }
            ?>
        </td>
    </tr>
    <tr>
        <td valign="top">Definisi </td>
        <td><?php echo htmlspecialchars($data['definisi']); ?></td>
    </tr>
    <tr>
        <td valign="top">Solusi</td>
        <td><?php echo htmlspecialchars($data['solusi']); ?></td>
    </tr>
</table>

</body>

<?php
// Tutup statement
$stmt->close();
$stmt2->close();
$stmt_gejala->close();
?>
