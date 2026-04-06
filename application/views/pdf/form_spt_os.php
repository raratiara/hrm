<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .container {
            border: 2px solid #000;
            padding: 10px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            table-layout: fixed; 
        }

        td, th {
            border: 1px solid #000;
            padding: 4px;
            font-size: 9px;
        }

        .no-border td {
            border: none;
        }

        .left { text-align: left; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

       
        .col-left { width: 70% !important; }
        .col-right { width: 30% !important; }


        .tbl-pph {
            table-layout: fixed;
        }

        .tbl-pph td:first-child {
            width: 70%;
        }

        .tbl-pph td:last-child {
            width: 30%;
        }


        .tbl-identitas {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tbl-identitas td {
            border: none; 
            /*border: 1px solid #000;*/
            padding: 4px;
            font-size: 9px;
            box-sizing: border-box;
        }

        .label {
            width: 20%;
            font-weight: bold;
        }

        .value {
            width: 30%;
        }

    </style>
</head>
<body>

<?php
if (!function_exists('rupiah')) {
    function rupiah($angka){
        if (empty($angka) || !is_numeric($angka)) {
            return '-';
        }
        ///return 'Rp ' . number_format((float)$angka, 0, ',', '.');
        return number_format((float)$angka, 0, ',', '.');
    }
}
?>

<div class="container">

    <!-- HEADER -->
    <div class="title">BUKTI PEMOTONGAN PAJAK PENGHASILAN PASAL 21</div>
    <div class="subtitle">FORMULIR 1721-A1</div>

    <!-- IDENTITAS PEMOTONG -->
    <table class="tbl-identitas" style="border: 1px solid #000;">
        <tr>
            <td class="label">NPWP PEMOTONG</td>
            <td class="value" colspan="3"><?=$data[0]->company_npwp?></td>
        </tr>
        <tr>
            <td class="label">NAMA PEMOTONG</td>
            <td class="value" colspan="3"><?=$data[0]->company_name?></td>
        </tr>
    </table>
    <br>

    <!-- IDENTITAS KARYAWAN -->
    A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG
    <table class="tbl-identitas" style="border: 1px solid #000;">
        <tr>
            <td class="label">NPWP</td>
            <td class="value"><?=$data[0]->no_npwp?></td>
            <td class="label">JENIS KELAMIN</td>
            <td class="value"><?=$data[0]->gender_name?></td>
        </tr>
        <tr>
            <td class="label">NO KTP</td>
            <td class="value"><?=$data[0]->no_ktp?></td>
            <td class="label">STATUS / PTKP</td>
            <td class="value"><?=$data[0]->status_marital_name?></td>
        </tr>
        <tr>
            <td class="label">NAMA</td>
            <td class="value"><?=$data[0]->full_name?></td>
            <td class="label">NAMA JABATAN</td>
            <td class="value"><?=$data[0]->job_title_name?></td>
        </tr>
        <tr>
            <td class="label">ALAMAT</td>
            <td class="value"><?=$data[0]->address_ktp?></td>
            <td class="label">KARYAWAN ASING</td>
            <td class="value"><?=$data[0]->is_karyawan_asing?></td>
        </tr>
    </table>
    <br>

    <!-- PENGHASILAN -->
    B. RINCIAN PENGHASILAN DAN PENGHITUNGAN PPH PASAL 21 <br>
    PENGHASILAN BRUTO
    <table class="tbl-pph">
        <colgroup>
            <col class="col-left">
            <col class="col-right">
        </colgroup>
        
        <tr class="bold center">
            <td>URAIAN</td>
            <td>JUMLAH (Rp)</td>
        </tr>
      
        <tr>
            <td>Gaji / Pensiun ATAU THT / JHT</td>
            <td class="right"><?= rupiah($data[0]->total_gaji) ?></td>
        </tr>
        <tr>
            <td>Tunjangan PPH</td>
            <td class="right">0</td>
        </tr>
        <tr>
            <td>Tunjangan lainnya</td>
            <td class="right"><?= rupiah($data[0]->total_tunjangan)?></td>
        </tr>
        <tr class="bold">
            <td>TOTAL BRUTO</td>
            <td class="right"><?= rupiah($data[0]->bruto_tahunan)?></td>
        </tr>
    </table>

    <!-- PENGURANG -->
    PENGURANGAN
    <table class="tbl-pph">
        <colgroup>
            <col class="col-left">
            <col class="col-right">
        </colgroup>
        
        <tr>
            <td>Biaya Jabatan</td>
            <td class="right"><?= rupiah($data[0]->biaya_jabatan)?></td>
        </tr>
        <tr>
            <td>Iuran</td>
            <td class="right"><?= rupiah($data[0]->iuran)?></td>
        </tr>
        <tr class="bold">
            <td>NETO</td>
            <td class="right"><?= rupiah($data[0]->neto_tahunan)?></td>
        </tr>
    </table>

    <!-- PAJAK -->
    PENGHITUNGAN PPH PASAL 21
    <table class="tbl-pph">
        <colgroup>
            <col class="col-left">
            <col class="col-right">
        </colgroup>
        
        <tr>
            <td>PTKP</td>
            <td class="right"><?= rupiah($data[0]->ptkp)?></td>
        </tr>
        <tr>
            <td>PKP</td>
            <td class="right"><?= rupiah($data[0]->pkp)?></td>
        </tr>
        <tr>
            <td>PPh21 Terutang</td>
            <td class="right"><?= rupiah($data[0]->pph21_tahunan)?></td>
        </tr>
        <tr>
            <td>PPh21 Dipotong</td>
            <td class="right"><?= rupiah($data[0]->pph21_ter_total)?></td>
        </tr>
        <tr class="bold">
            <td>Kurang / Lebih Bayar</td>
            <td class="right"><?= rupiah($data[0]->kurang_lebih_bayar)?></td>
        </tr>
    </table>

    <!-- FOOTER -->
    <table class="no-border">
        <colgroup>
            <col class="col-left">
            <col class="col-right">
        </colgroup>
        <tr>
            <td>Generated at <?=$data[0]->created_at?></td>
            <td class="center">TTD</td>
        </tr>
    </table>

</div>

</body>
</html>