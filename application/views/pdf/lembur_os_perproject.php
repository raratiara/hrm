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
.border-bottom { border-bottom: 1px solid #000; }

.right { text-align: right; }
.center { text-align: center; }
.bold { font-weight: bold; }

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


<?php
if (!function_exists('rupiah')) {
    function rupiah($angka){
        if (empty($angka) || !is_numeric($angka)) {
            return '-';
        }
        return 'Rp ' . number_format((float)$angka, 0, ',', '.');
    }
}
?>



<?php foreach ($employees as $i => $row): ?>

<!-- ================= HEADER ================= -->
<table>
<tr>
    <td width="15%">
        <img src="<?= $base64 ?>" width="70">
    </td>
    <td width="60%" class="bold">
        PT. MANDIRI AGANGTA SEJAHTERA<br>
        Jl. Kemang Raya No.1A RT.001/RW009<br>
        Tlp/Fax (021) 77844672<br>
        Sukmajaya Depok, Jawa Barat
    </td>
    <td width="25%" class="right bold">
        REPORT LEMBUR<br>
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
            <tr><td>Nama</td><td>: <?= $row->full_name ?></td></tr>
        </table>
    </td>
    <td width="50%">
        <table>
            <tr><td width="30%">Project</td><td>: <?= $row->project_name ?></td></tr>
            <tr><td>Jabatan</td><td>: <?= $row->job_title_name ?></td></tr>
        </table>
    </td>
</tr>
</table>

<hr>

<!-- ================= TABLE LEMBUR ================= -->
<table class="border">
<tr class="bold center border-bottom">
    <td>Tanggal</td>
    <td>Mulai</td>
    <td>Selesai</td>
    <td>Total Jam</td>
    <td>Nominal</td>
</tr>


<?php 
$total_lembur = 0;
if (!empty($row->lembur_detail)):
    foreach ($row->lembur_detail as $l): 
        $total_lembur += $l->amount;
?>
<tr>
    <td class="center"><?= date('Y-m-d', strtotime($l->datetime_start)) ?></td>
    <td class="center"><?= $l->datetime_start ?></td>
    <td class="center"><?= $l->datetime_end ?></td>
    <td class="center"><?= $l->num_of_hour ?> jam</td>
    <td class="right"><?= rupiah($l->amount) ?></td>
</tr>
<?php 
    endforeach;
else:
?>
<tr>
    <td colspan="5" class="center">Tidak ada lembur</td>
</tr>
<?php endif; ?>



</table>

<br>

<!-- ================= TOTAL ================= -->
<table class="border">
<tr class="bold">
    <td width="70%">TOTAL LEMBUR</td>
    <td width="30%" class="right"><?= rupiah($total_lembur) ?></td>
</tr>
</table>

<br>

<table>
<tr>
    <td width="50%" class="bold">
        Terbilang:<br>
        <i><?= ucfirst(terbilang($total_lembur)) ?> </i>
    </td>
    <td width="50%" class="right">
        BENDAHARA<br><br><br><br>
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
