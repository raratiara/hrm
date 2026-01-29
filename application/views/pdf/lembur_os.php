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
        Report Lembur<br>
        <?=$periode_bulan?> <?=$periode_tahun?>
    </td>
</tr>
</table>

<hr>

<!-- INFO -->
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
            
            <tr>
                <td>Tanggal</td>
                <td>Waktu Mulai</td>
                <td>Waktu Selesai</td>
                <td>Total Jam Lembur</td>
                <td>Total Nominal Lembur</td>
            </tr>
            <tr>
                <td>2025-12-05</td>
                <td>2025-12-05 19:30:00</td>
                <td>2025-12-05 22:41:00</td>
                <td>3 jam</td>
                <td>75000</td>
            </tr>
            <tr>
                <td>2025-12-14</td>
                <td>2025-12-14 18:55:00</td>
                <td>2025-12-14 23:00:00</td>
                <td>4.5 jam</td>
                <td>112000</td>
            </tr>
        </table>
    </td>

    
</tr>
</table>

<!-- TOTAL -->
<table>
<tr>
    <td width="50%" class="border">
        <table width="100%">
            
            <tr style="font-weight:bold;"><td>Jumlah</td><td class="right">187.000</td></tr>
            
        </table>
    </td>

    
</tr>
</table>

<hr>
<table style="margin-top:5px">
<tr>
    <td width="50%">
        <table>
            <tr><td width="30%" class="bold">Jumlah</td>
                <td class="right bold" style="font-size:12px">187.000</td>
            </tr>
            <tr class="border"><td colspan="2" style="font-style:italic;">seratus delapan puluh tujuh ribu rupiah</td>
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
