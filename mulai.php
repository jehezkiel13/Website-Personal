<?php
include "conf/inc.koneksi.php";

$NOIP = $_SERVER['REMOTE_ADDR'];

// Periksa apabila sudah ditemukan solusi
$sql_cekh = "SELECT * FROM tmp_solusi WHERE noip=? GROUP BY kd_solusi";
$stmt = $koneksi->prepare($sql_cekh);
$stmt->bind_param("s", $NOIP);
$stmt->execute();
$qry_cekh = $stmt->get_result();
$hsl_cekh = $qry_cekh->num_rows;

if ($hsl_cekh == 1) {
    // Apabila data tmp_solusi isinya 1
    $hsl_data = $qry_cekh->fetch_array(MYSQLI_ASSOC);

    // Memindahkan data tmp ke tabel hasil_analisa
    $sql_pasien = "SELECT * FROM tmp_pasien WHERE noip=?";
    $stmt = $koneksi->prepare($sql_pasien);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $qry_pasien = $stmt->get_result();
    $hsl_pasien = $qry_pasien->fetch_array(MYSQLI_ASSOC);

    // Perintah untuk memindah data
    $sql_in = "INSERT INTO analisa_hasil (nama, kelamin, alamat, pekerjaan, kd_solusi, noip, tanggal) 
               VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql_in);
    $stmt->bind_param("sssssss", $hsl_pasien['nama'], $hsl_pasien['kelamin'], $hsl_pasien['alamat'], 
                      $hsl_pasien['pekerjaan'], $hsl_data['kd_solusi'], $hsl_pasien['noip'], $hsl_pasien['tanggal']);
    $stmt->execute();

    // Redireksi setelah pemindahan data
    echo "<meta http-equiv='refresh' content='0; url=index.php?page=result'>";
    exit;
}

// Apabila BELUM MENEMUKAN solusi
$sqlcek = "SELECT * FROM tmp_analisa WHERE noip=?";
$stmt = $koneksi->prepare($sqlcek);
$stmt->bind_param("s", $NOIP);
$stmt->execute();
$qrycek = $stmt->get_result();
$datacek = $qrycek->num_rows;

if ($datacek >= 1) {
    // Seandainya tmp_analisa tidak kosong
    $sqlg = "SELECT gejala.* FROM gejala 
             JOIN tmp_analisa ON gejala.kd_gejala = tmp_analisa.kd_gejala
             WHERE tmp_analisa.noip=? AND NOT tmp_analisa.kd_gejala 
             IN (SELECT kd_gejala FROM tmp_gejala WHERE noip=?) 
             ORDER BY gejala.kd_gejala LIMIT 1";
    $stmt = $koneksi->prepare($sqlg);
    $stmt->bind_param("ss", $NOIP, $NOIP);
    $stmt->execute();
    $qryg = $stmt->get_result();
    $datag = $qryg->fetch_array(MYSQLI_ASSOC);
    
    $kdgejala = $datag['kd_gejala'];
    $gejala = $datag['nm_gejala'];
} else {
    // Seandainya tmp kosong
    $sqlg = "SELECT * FROM gejala ORDER BY kd_gejala LIMIT 1";
    $stmt = $koneksi->prepare($sqlg);
    $stmt->execute();
    $qryg = $stmt->get_result();
    $datag = $qryg->fetch_array(MYSQLI_ASSOC);
    
    $kdgejala = $datag['kd_gejala'];
    $gejala = $datag['nm_gejala'];
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-hand-up"></span> Konsultasi</h3>
    </div>
    <div class="panel-body">
        <form action="?page=processcon" method="post" name="form1" target="_self">
            <table class="table" width="100%" border="0" cellpadding="2" cellspacing="1">
                <tr>
                    <td style="border:none;" colspan="2" align="center">
                        <h3><span class="label label-default">Apakah <?php echo htmlspecialchars($gejala); ?> ? </span></h3>
                        <input name="TxtKdGejala" type="hidden" value="<?php echo htmlspecialchars($kdgejala); ?>">
                    </td>
                </tr>
                <tr>
                    <td style="border:none;">
                        <span class="input-group-addon">
                            <input type="radio" name="RbPilih" value="YA" checked> Ya
                        </span>
                        <span class="input-group-addon">
                            <input type="radio" name="RbPilih" value="TIDAK"> Tidak
                        </span>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="border:none;">
                        <input type="submit" class="btn btn-success" name="Submit" value="Selanjutnya">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
