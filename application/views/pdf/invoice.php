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

        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        .company {
            font-size: 10px;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .no-border td {
            border: none;
            padding: 2px;
            vertical-align: top;
        }

        .border th, .border td {
            border: 1px solid #000;
            padding: 4px;
        }

        .border th {
            text-align: center;
            background: #f2f2f2;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- ================= HEADER ================= -->
<table class="no-border header">
    <tr>
        <td width="60%" class="company">
            <strong>PT. MANDIRI AGANGTA SEJAHTERA</strong><br>
            Jl. Kemang Raya No.1A RT.001/RW009<br>
            Sukmajaya, Depok – Jawa Barat<br>
            <b>NPWP : 42.036.630.4-412.000</b>
        </td>
        <td width="40%"></td>
    </tr>
</table>

<div class="title"><u>INVOICE</u></div>

<!-- ================= INFO INVOICE ================= -->
<table class="no-border mt-10">
    <tr>
        <td width="25%"></td>
        <td width="75%">
            <table class="no-border">
                <tr><td width="20%">No</td><td>: <?= $invoice_no ?></td></tr>
                <tr><td width="20%">Invoice Date</td><td>: <?= $invoice_date ?></td></tr>
                <tr><td width="20%">No. PO</td><td>: <?= $po_number ?></td></tr>
            </table>
        </td>
        <!-- <td width="50%">
            <table class="no-border">
                <tr><td width="35%">Halaman</td><td>: 1</td></tr>
                <tr><td>Terms</td><td>: 14 Days</td></tr>
                <tr><td>Jatuh Tempo</td><td>: <?= $due_date ?></td></tr>
            </table>
        </td> -->
    </tr>
</table>

<!-- ================= CUSTOMER ================= -->
<table class="no-border mt-10">
    <tr>
        <td width="70%">
            <table class="no-border">
                <tr><td width="20%">Dikirim Kepada</td><td>: <b><?= $customer_name ?></b></td></tr>
                <tr><td>Alamat</td><td>: <?= $customer_address ?></td></tr>
                <tr><td>NPWP</td><td>: <?= $customer_npwp ?></td></tr>
            </table>
        </td>
        <td width="30%">
            <table class="no-border">
                <tr><td width="40%">Halaman</td><td>: 1</td></tr>
                <tr><td width="40%">Terms</td><td>: <?= $terms?></td></tr>
                <tr><td width="40%">Jatuh Tempo</td><td>: <?= $due_date ?></td></tr>
            </table>
        </td>
    </tr>
</table>

<!-- <table class="no-border mt-10">
    <tr>
        <td width="15%">Dikirim Kepada</td>
        <td width="85%">: <strong><?= $customer_name ?></strong></td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: <?= $customer_address ?></td>
    </tr>
    <tr>
        <td>NPWP</td>
        <td>: <?= $customer_npwp ?></td>
    </tr>
</table> -->

<!-- ================= TABLE ITEM ================= -->
<table class="border mt-10">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="75%">Jenis</th>
            <th width="20%">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="center">1</td>
            <td>
                <strong><?= $item_title ?></strong><br><br>
                <?= $customer_name ?><br>
                Project : <?= $project_name ?><br>
                Periode : <?= $periode_start ?> s/d <?= $periode_end ?><br><br>
                Management Fee <?=$management_fee?> %
            </td>
            <td class="right">
                

                <br><br><br>
                <?= number_format($subtotal,0,',','.') ?> <br><br><br>
                <?= number_format($management_fee_nominal,0,',','.') ?>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="right bold">Jumlah Harga Jual</td>
            <td class="right bold"><?= number_format($jumlah_harga_jual,0,',','.') ?></td>
        </tr>
        <tr>
            <td colspan="2" class="right">PPN <?=$ppn?>%</td>
            <td class="right"><?= number_format($ppn_nominal,0,',','.') ?></td>
        </tr>
        <tr>
            <td colspan="2" class="right bold">Jumlah Sesudah Pajak</td>
            <td class="right bold"><?= number_format($jumlah_sesudah_pajak,0,',','.') ?></td>
        </tr>
    </tbody>
</table>

<!-- ================= TERBILANG ================= -->
<table class="no-border mt-10">
    <tr>
        <td width="15%">Terbilang</td>
        <td width="85%">: <strong># <?= $terbilang ?> Rupiah #</strong></td>
    </tr>
</table>

<!-- ================= FOOTER ================= -->
<table class="no-border mt-20">
    <tr>
        <td width="60%">
            Depok, <?= $invoice_date ?><br><br><br><br><br><br>
            <strong><u>Tri Ubaya Adri, M</u></strong><br>
            <strong>Direktur</strong>
        </td>
        <td width="40%">
            <strong>Catatan:</strong><br>
            1. Pembayaran dengan Cheque/Giro dianggap lunas setelah dapat diuangkan<br>
            2. Pembayaran di Transfer ke:<br>
            No. Rek : <?= $bank_account ?><br>
            A/N : <?= $bank_name ?><br>
            <?= $bank_branch ?>
            Jawa Barat
        </td>
    </tr>
</table>

</body>
</html>
