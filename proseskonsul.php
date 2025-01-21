<?php 
include "conf/inc.koneksi.php";

// Baca variabel Form
$RbPilih = $_REQUEST['RbPilih'];
$TxtKdGejala = $_REQUEST['TxtKdGejala'];

// Mendapatkan No IP
$NOIP = $_SERVER['REMOTE_ADDR'];

// Fungsi untuk menambah data ke tmp_analisa
function AddTmpAnalisa($kdgejala, $NOIP, $koneksi) {
    $sql_solusi = "SELECT rule.* FROM rule, tmp_solusi 
                   WHERE rule.kd_solusi = tmp_solusi.kd_solusi 
                   AND noip = ? ORDER BY rule.kd_solusi, rule.kd_gejala";
    $stmt = $koneksi->prepare($sql_solusi);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $qry_solusi = $stmt->get_result();
    while ($data_solusi = $qry_solusi->fetch_assoc()) {
        $sqltmp = "INSERT INTO tmp_analisa (noip, kd_solusi, kd_gejala)
                   VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($sqltmp);
        $stmt->bind_param("sss", $NOIP, $data_solusi['kd_solusi'], $data_solusi['kd_gejala']);
        $stmt->execute();
    }
    $stmt->close();
}

// Fungsi untuk menambah data ke tmp_gejala
function AddTmpGejala($kdgejala, $NOIP, $koneksi) {
    $sql_gejala = "INSERT INTO tmp_gejala (noip, kd_gejala) VALUES (?, ?)";
    $stmt = $koneksi->prepare($sql_gejala);
    $stmt->bind_param("ss", $NOIP, $kdgejala);
    $stmt->execute();
    $stmt->close();
}

// Fungsi hapus tabel tmp_solusi
function DelTmpSakit($NOIP, $koneksi) {
    $sql_del = "DELETE FROM tmp_solusi WHERE noip = ?";
    $stmt = $koneksi->prepare($sql_del);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $stmt->close();
}

// Fungsi hapus tabel tmp_analisa
function DelTmpAnalisa($NOIP, $koneksi) {
    $sql_del = "DELETE FROM tmp_analisa WHERE noip = ?";
    $stmt = $koneksi->prepare($sql_del);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $stmt->close();
}

// Pemeriksaan
if ($RbPilih == "YA") {
    $sql_analisa = "SELECT * FROM tmp_analisa WHERE noip = ?";
    $stmt = $koneksi->prepare($sql_analisa);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $qry_analisa = $stmt->get_result();
    $data_cek = $qry_analisa->num_rows;
    
    if ($data_cek >= 1) {
        // Kode saat tmp_analisa tidak kosong
        DelTmpSakit($NOIP, $koneksi);
        $sql_tmp = "SELECT * FROM tmp_analisa 
                    WHERE kd_gejala = ? AND noip = ?";
        $stmt = $koneksi->prepare($sql_tmp);
        $stmt->bind_param("ss", $TxtKdGejala, $NOIP);
        $stmt->execute();
        $qry_tmp = $stmt->get_result();
        while ($data_tmp = $qry_tmp->fetch_assoc()) {
            $sql_rsolusi = "SELECT * FROM rule 
                            WHERE kd_solusi = ? 
                            GROUP BY kd_solusi";
            $stmt_rsolusi = $koneksi->prepare($sql_rsolusi);
            $stmt_rsolusi->bind_param("s", $data_tmp['kd_solusi']);
            $stmt_rsolusi->execute();
            $qry_rsolusi = $stmt_rsolusi->get_result();
            while ($data_rsolusi = $qry_rsolusi->fetch_assoc()) {
                // Data solusi gizi yang mungkin dimasukkan ke tmp
                $sql_input = "INSERT INTO tmp_solusi (noip, kd_solusi)
                              VALUES (?, ?)";
                $stmt_input = $koneksi->prepare($sql_input);
                $stmt_input->bind_param("ss", $NOIP, $data_rsolusi['kd_solusi']);
                $stmt_input->execute();
            }
            $stmt_rsolusi->close();
        } 
        DelTmpAnalisa($NOIP, $koneksi);
        AddTmpAnalisa($TxtKdGejala, $NOIP, $koneksi);
        AddTmpGejala($TxtKdGejala, $NOIP, $koneksi);
    } else {
        // Kode saat tmp_analisa kosong
        $sql_rgejala = "SELECT * FROM rule WHERE kd_gejala = ?";
        $stmt = $koneksi->prepare($sql_rgejala);
        $stmt->bind_param("s", $TxtKdGejala);
        $stmt->execute();
        $qry_rgejala = $stmt->get_result();
        while ($data_rgejala = $qry_rgejala->fetch_assoc()) {
            $sql_rsolusi = "SELECT * FROM rule 
                            WHERE kd_solusi = ? 
                            GROUP BY kd_solusi";
            $stmt_rsolusi = $koneksi->prepare($sql_rsolusi);
            $stmt_rsolusi->bind_param("s", $data_rgejala['kd_solusi']);
            $stmt_rsolusi->execute();
            $qry_rsolusi = $stmt_rsolusi->get_result();
            while ($data_rsolusi = $qry_rsolusi->fetch_assoc()) {
                // Data solusi gizi yang mungkin dimasukkan ke tmp
                $sql_input = "INSERT INTO tmp_solusi (noip, kd_solusi)
                              VALUES (?, ?)";
                $stmt_input = $koneksi->prepare($sql_input);
                $stmt_input->bind_param("ss", $NOIP, $data_rsolusi['kd_solusi']);
                $stmt_input->execute();
            }
            $stmt_rsolusi->close();
        } 
        AddTmpAnalisa($TxtKdGejala, $NOIP, $koneksi);
        AddTmpGejala($TxtKdGejala, $NOIP, $koneksi);
    }
    echo "<meta http-equiv='refresh' content='0; url=index.php?page=start'>";
}

if ($RbPilih == "TIDAK") {
    $sql_analisa = "SELECT * FROM tmp_analisa WHERE noip = ?";
    $stmt = $koneksi->prepare($sql_analisa);
    $stmt->bind_param("s", $NOIP);
    $stmt->execute();
    $qry_analisa = $stmt->get_result();
    $data_cek = $qry_analisa->num_rows;

    if ($data_cek >= 1) {
        // Kode saat tmp_analisa tidak kosong
        $sql_rule = "SELECT * FROM tmp_analisa WHERE kd_gejala = ?";
        $stmt = $koneksi->prepare($sql_rule);
        $stmt->bind_param("s", $TxtKdGejala);
        $stmt->execute();
        $qry_rule = $stmt->get_result();
        while ($hsl_rule = $qry_rule->fetch_assoc()) {
            // Hapus daftar rule yang sudah tidak mungkin dari tabel tmp
            $sql_deltmp = "DELETE FROM tmp_analisa 
                           WHERE kd_solusi = ? AND noip = ?";
            $stmt_deltmp = $koneksi->prepare($sql_deltmp);
            $stmt_deltmp->bind_param("ss", $hsl_rule['kd_solusi'], $NOIP);
            $stmt_deltmp->execute();

            // Hapus daftar solusi gizi yang sudah tidak ada kemungkinan
            $sql_deltmp2 = "DELETE FROM tmp_solusi 
                            WHERE kd_solusi = ? AND noip = ?";
            $stmt_deltmp2 = $koneksi->prepare($sql_deltmp2);
            $stmt_deltmp2->bind_param("ss", $hsl_rule['kd_solusi'], $NOIP);
            $stmt_deltmp2->execute();
        }       
    } else {
        // Pindahkan data relasi ke tmp_analisa
        $sql_rule = "SELECT * FROM rule ORDER BY kd_solusi, kd_gejala";
        $stmt = $koneksi->prepare($sql_rule);
        $stmt->execute();
        $qry_rule = $stmt->get_result();
        while ($hsl_rule = $qry_rule->fetch_assoc()) {
            $sql_intmp = "INSERT INTO tmp_analisa (noip, kd_solusi, kd_gejala)
                          VALUES (?, ?, ?)";
            $stmt_intmp = $koneksi->prepare($sql_intmp);
            $stmt_intmp->bind_param("sss", $NOIP, $hsl_rule['kd_solusi'], $hsl_rule['kd_gejala']);
            $stmt_intmp->execute();

            // Masukkan data solusi gizi yang mungkin terjangkit
            $sql_intmp2 = "INSERT INTO tmp_solusi (noip, kd_solusi)
                           VALUES (?, ?)";
            $stmt_intmp2 = $koneksi->prepare($sql_intmp2);
            $stmt_intmp2->bind_param("ss", $NOIP, $hsl_rule['kd_solusi']);
            $stmt_intmp2->execute();              
        }

        // Hapus tmp_analisa yang tidak sesuai
        $sql_rule2 = "SELECT * FROM rule WHERE kd_gejala = ?";
        $stmt_rule2 = $koneksi->prepare($sql_rule2);
        $stmt_rule2->bind_param("s", $TxtKdGejala);
        $stmt_rule2->execute();
        $qry_rule2 = $stmt_rule2->get_result();
        while ($hsl_rule2 = $qry_rule2->fetch_assoc()) {
            $sql_deltmp = "DELETE FROM tmp_analisa 
                           WHERE kd_solusi = ? AND noip = ?";
            $stmt_deltmp = $koneksi->prepare($sql_deltmp);
            $stmt_deltmp->bind_param("ss", $hsl_rule2['kd_solusi'], $NOIP);
            $stmt_deltmp->execute();

            // Hapus solusi gizi yang sudah tidak mungkin
            $sql_deltmp2 = "DELETE FROM tmp_solusi 
                            WHERE kd_solusi = ? AND noip = ?";
            $stmt_deltmp2 = $koneksi->prepare($sql_deltmp2);
            $stmt_deltmp2->bind_param("ss", $hsl_rule2['kd_solusi'], $NOIP);
            $stmt_deltmp2->execute();
        }
    }
    echo "<meta http-equiv='refresh' content='0; url=index.php?page=start'>";
}
?>
