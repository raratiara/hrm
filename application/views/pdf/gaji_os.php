<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10px;
    color: #000;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td, th {
    padding: 4px;
    vertical-align: top;
}

.border { border: 1px solid #000; }
.border-top { border-top: 1px solid #000; }
.border-bottom { border-bottom: 1px solid #000; }

.center { text-align: center; }
.right { text-align: right; }
.bold { font-weight: bold; }

.header td {
    padding: 2px;
}
</style>
</head>
<body>

<!-- HEADER -->
<table class="header">
<tr>
    
    <td width="15%" style="position:relative; padding-top:0;">
        <?php
        $path = FCPATH . 'public/assets/images/logo/mas_logo_tsp.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        ?>
        <div style="position:absolute; left:0; margin-bottom: 30px !important;">
            <img src="<?= $base64 ?>" width="75">
        </div>
    </td>
    <td width="60%" class="bold">
        PT. MANDIRI AGANGTA SEJAHTERA<br>
        Jl. Kemang Raya No.1A RT.001/RW009<br>
        Tlp/Fax (021) 77844672<br>
        Sukmajaya Depok, Jawa Barat
    </td>
    <td width="25%" class="right bold">
        SLIP GAJI<br>
        <?=$periode_bulan?> <?=$periode_tahun?>
    </td>
</tr>
</table>

<hr>

<!-- INFO -->

<?php
function rupiah($angka){
    if (empty($angka) || !is_numeric($angka)) {
        return '-';
    }
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}
?>



<table>
<tr>
    <td width="50%">
        <table>
            <tr><td width="30%">NIK</td><td>: <?=$nik?></td></tr>
            <tr><td>Nama Karyawan</td><td>: <?=$emp_name?></td></tr>
        </table>
    </td>
    <td width="50%">
        <table>
            <tr><td width="30%">Nama Proyek</td><td>: <?=$project_name?></td></tr>
            <tr><td>Jabatan</td><td>: <?=$jabatan?></td></tr>
        </table>
    </td>
</tr>
</table>

<hr>

<!-- PENDAPATAN & POTONGAN -->
<table>
<tr>
    <td width="50%" class="border">
        <table width="100%">
            <tr><th colspan="2" class="border-bottom">PENDAPATAN</th></tr>
            <tr><td>Gaji Pokok</td><td class="right"><?= rupiah($gaji_pokok) ?></td></tr>
            <tr><td>Tunjangan Jabatan</td><td class="right"><?= rupiah($tunjangan_jabatan) ?></td></tr>
            <tr><td>Tunjangan Transport</td><td class="right"><?=rupiah($tunjangan_transport)?></td></tr>
            <tr><td>Tunjangan Konsumsi</td><td class="right"><?=rupiah($tunjangan_konsumsi)?></td></tr>
            <tr><td>Tunjangan Komunikasi</td><td class="right"><?=rupiah($tunjangan_komunikasi)?></td></tr>
            <tr><td>Lembur</td><td class="right"></td></tr>
            <tr>
                <td colspan="2" style="font-size:9px;">
                    (lembur <?= rupiah($total_nominal_lembur) ?> akan dibayarkan tgl <?=$tanggal_pembayaran_lembur?>)
                </td>
            </tr>
        </table>
    </td>

    <td width="50%" class="border">
        <table width="100%">
            <tr><th colspan="2" class="border-bottom">POTONGAN</th></tr>
            <tr><td>BPJS Kesehatan</td><td class="right"><?= rupiah($bpjs_kesehatan) ?></td></tr>
            <tr><td>BPJS Tenagakerja</td><td class="right"><?= rupiah($bpjs_tk) ?></td></tr>
            <tr><td>Seragam</td><td class="right"><?= rupiah($seragam) ?></td></tr>
            <tr><td>Pelatihan</td><td class="right"><?= rupiah($pelatihan) ?></td></tr>
            <tr><td>Lain-Lain</td><td class="right"><?= rupiah($lain_lain) ?></td></tr>
            <tr><td>Hutang</td><td class="right"><?= rupiah($hutang) ?></td></tr>
            <tr><td>Payroll</td><td class="right"><?= rupiah($payroll) ?></td></tr>
            <tr><td>Sosial</td><td class="right"><?= rupiah($sosial) ?></td></tr>
            <tr><td>PPH 120</td><td class="right"><?= rupiah($pph_120) ?></td></tr>
        </table>
    </td>
</tr>
</table>

<!-- TOTAL -->
<table>
<tr>
    <td width="50%" class="border">
        <table width="100%">
            
            <tr style="font-weight:bold;"><td>Jumlah Pendapatan</td><td class="right"><?= rupiah($total_pendapatan) ?></td></tr>
            
        </table>
    </td>

    <td width="50%" class="border">
        <table width="100%">
            <tr style="font-weight:bold;"><td>Jumlah Potongan</td><td class="right"><?= rupiah($total_potongan) ?></td></tr>
        </table>
    </td>
</tr>
</table>

<hr>
<table style="margin-top:5px">
<tr>
    <td width="50%">
        <table>
            <tr><td width="30%" class="bold">GAJI BERSIH</td>
                <td class="right bold" style="font-size:12px"><?= rupiah($gaji_bersih) ?></td>
            </tr>
            <tr class="border"><td colspan="2" style="font-style:italic;"><?=$terbilang?></td>
            </tr>
        </table>
    </td>
    <td width="50%" style="text-align:right;">
        BENDAHARA<br><br><br><br><br><br>
        <u>( PT MAS )</u>
    </td>
    
</tr>
</table>


<!-- <table style="margin-top:5px;">
<tr>
    <td style="font-style:italic; width: 50% !important;">
        satu juta lima ratus ribu rupiah
    </td>
</tr>
</table> -->

<br><br>

<!-- <table>
<tr>
    <td width="70%"></td>
    <td width="30%" class="center">
        BENDAHARA<br><br><br>
        <u>( PT MAS )</u>
    </td>
</tr>
</table> -->

</body>
</html>



