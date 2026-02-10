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

.page-break {
    page-break-after: always;
}
</style>
</head>
<body>

<?php
$path = FCPATH . 'public/assets/images/logo/mas_logo_tsp.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$dataLogo = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataLogo);
?>

<?php foreach ($employees as $i => $row): ?>

<!-- ================= HEADER ================= -->
<table class="header">
<tr>
    <td width="15%" style="position:relative;">
        <img src="<?= $base64 ?>" width="75">
    </td>
    <td width="60%" class="bold">
        PT. MANDIRI AGANGTA SEJAHTERA<br>
        Jl. Kemang Raya No.1A RT.001/RW009<br>
        Tlp/Fax (021) 77844672<br>
        Sukmajaya Depok, Jawa Barat
    </td>
    <td width="25%" class="right bold">
        SLIP GAJI<br>
        <?= $row->periode_bulan_name ?> <?= $row->tahun_penggajian ?>
    </td>
</tr>
</table>

<hr>

<!-- ================= INFO ================= -->
<table>
<tr>
    <td width="50%">
        <table>
            <tr><td width="30%">NIK</td><td>: <?= $row->emp_code ?></td></tr>
            <tr><td>Nama Karyawan</td><td>: <?= $row->full_name ?></td></tr>
        </table>
    </td>
    <td width="50%">
        <table>
            <tr><td width="30%">Nama Proyek</td><td>: <?= $row->project_name ?></td></tr>
            <tr><td>Jabatan</td><td>: <?= $row->job_title_name ?></td></tr>
        </table>
    </td>
</tr>
</table>

<hr>

<!-- ================= PENDAPATAN & POTONGAN ================= -->
<table>
<tr>
    <td width="50%" class="border">
        <table width="100%">
            <tr><th colspan="2" class="border-bottom">PENDAPATAN</th></tr>
            <tr><td>Gaji Pokok</td><td class="right">1.500.000</td></tr>
            <tr><td>Tunjangan Jabatan</td><td class="right">0</td></tr>
            <tr><td>Lembur</td><td class="right">0</td></tr>
            <tr>
                <td colspan="2" style="font-size:9px;">
                    (lembur akan dibayarkan tgl <?= $row->tanggal_pembayaran_lembur ?>)
                </td>
            </tr>
        </table>
    </td>

    <td width="50%" class="border">
        <table width="100%">
            <tr><th colspan="2" class="border-bottom">POTONGAN</th></tr>
            <tr><td>BPJS Kesehatan</td><td class="right">0</td></tr>
            <tr><td>BPJS Tenagakerja</td><td class="right">0</td></tr>
            <tr><td>Seragam</td><td class="right">0</td></tr>
            <tr><td>Hutang</td><td class="right">0</td></tr>
            <tr><td>Payroll</td><td class="right">0</td></tr>
            <tr><td>Sosial</td><td class="right">0</td></tr>
            <tr><td>Absensi</td><td class="right">0</td></tr>
        </table>
    </td>
</tr>
</table>

<!-- ================= TOTAL ================= -->
<table>
<tr>
    <td width="50%" class="border">
        <table width="100%">
            <tr class="bold">
                <td>Jumlah Pendapatan</td>
                <td class="right">1.500.000</td>
            </tr>
        </table>
    </td>

    <td width="50%" class="border">
        <table width="100%">
            <tr class="bold">
                <td>Jumlah Potongan</td>
                <td class="right">0</td>
            </tr>
        </table>
    </td>
</tr>
</table>

<hr>

<table style="margin-top:5px">
<tr>
    <td width="50%">
        <table>
            <tr>
                <td width="30%" class="bold">GAJI BERSIH</td>
                <td class="right bold" style="font-size:12px">1.500.000</td>
            </tr>
            <tr class="border">
                <td colspan="2" style="font-style:italic;">
                    satu juta lima ratus ribu rupiah
                </td>
            </tr>
        </table>
    </td>
    <td width="50%" style="text-align:right;">
        BENDAHARA<br><br><br><br><br>
        <u>( PT MAS )</u>
    </td>
</tr>
</table>

<?php if ($i < count($employees) - 1): ?>
<div class="page-break"></div>
<?php endif; ?>

<?php endforeach; ?>

</body>
</html>
