<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #000;
        }

        .header {
            text-align: center;
            line-height: 1.4;
        }

        .header h2 {
            margin: 0;
            font-size: 14px;
        }

        .line {
            border-top: 2px solid #000;
            margin: 5px 0 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 4px;
            vertical-align: top;
        }

        .table-border th,
        .table-border td {
            border: 1px solid #000;
        }

        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }

        .no-border td {
            border: none;
            padding: 2px;
        }

        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }

    </style>
</head>
<body>

<!-- HEADER -->
<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <!-- LOGO -->
        <td width="15%" valign="top" align="left">
            <?php
            $path = FCPATH . 'public/assets/images/logo/mas_logo_tsp.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            ?>
            <img src="<?= $base64 ?>" height="80" width="80">
        </td>

        <!-- HEADER TEXT -->
        <td width="85%" valign="top" align="left" style="padding-top:15px; padding-left:200px;">
            <div style="font-size:14px; font-weight:bold; line-height:1.3;">
                PT. MANDIRI AGANGTA SEJAHTERA
            </div>
            <div style="font-size:10px; line-height:1.3;">
                Jl. Kemang Raya No. 1A - RT.01/RW.09, Sukmajaya - Depok, Jawa Barat
            </div>
            <div style="font-size:10px; line-height:1.3;">
                Telp : 021-77844672 &nbsp;&nbsp; Email : info@mandirias.co.id
            </div>
        </td>
    </tr>
</table>


<hr style="border:2px solid #000; margin:8px 0;">


<div class="line"></div>

<!-- <h2 style="text-align: center;"><u>Rincian Biaya</u></h2>
<h3 style="text-align: center;"><?= $no_invoice ?></h3> -->

<div style="text-align: center;">
    <span style="font-size:16px; font-weight:bold;"><u>RINCIAN BIAYA</u></span><br>
    <?= $no_invoice ?>
</div>

<!-- INFO -->
<table class="no-border">
    <tr>
        <td width="60%">
            <table class="no-border">
                <tr>
                    <td width="10%">Nama</td>
                    <td width="1%">:</td>
                    <td><?= $nama_customer ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?= $alamat_customer ?></td>
                </tr>
                <tr>
                    <td>Project</td>
                    <td>:</td>
                    <td><?= $project ?></td>
                </tr>
            </table>
        </td>
        <!-- <td width="40%">
            <table class="no-border">
                <tr>
                    <td class="bold center" colspan="3">RINCIAN BIAYA</td>
                </tr>
                <tr>
                    <td width="30%">No</td>
                    <td width="5%">:</td>
                    <td><?= $no_invoice ?></td>
                </tr>
            </table>
        </td> -->
    </tr>
</table>

<!-- JUDUL -->
<table class="table-border mt-10">
    <tr>
        <th class="center" width="5%">No</th>
        <th class="center" width="20%">Uraian</th>
        <th class="center" width="10%">Qty</th>
        <th class="center" width="10%">Satuan</th>
        <th class="center" width="15%">Harga Satuan</th>
        <th class="center" width="20%">Keterangan Perhitungan</th>
        <th class="center" width="20%">Total Harga</th>
    </tr>

    <?php $no=1; foreach($items as $row): ?>
    <tr>
        <td class="center"><?= $no++ ?></td>
        <td><?= $row->project_outsource_id ?></td>
        <td class="center"><?= $row->customer_id ?></td>
        <td class="center"><?= $row->jumlah ?></td>
        <td class="right"><?= number_format($row->management_fee_percen,0,',','.') ?></td>
        <td><?= $row->management_fee_harga ?></td>
        <td class="right"><?= number_format($row->jumlah_total,0,',','.') ?></td>
    </tr>
    <?php endforeach; ?>

    <!-- SUBTOTAL -->
    <tr>
        <td colspan="6" class="right bold">Sub Total</td>
        <td class="right bold"><!-- <?= number_format($sub_total,0,',','.') ?> --></td>
    </tr>
    <tr>
        <td colspan="6" class="right bold">Management Fee (8%)</td>
        <td class="right bold"><!-- <?= number_format($management_fee,0,',','.') ?> --></td>
    </tr>
    <tr>
        <td colspan="6" class="right bold">Sub Total 2</td>
        <td class="right bold"><!-- <?= number_format($sub_total,0,',','.') ?> --></td>
    </tr>
    <tr>
        <td colspan="6" class="right bold">Total</td>
        <td class="right bold"><!-- <?= number_format($total,0,',','.') ?> --></td>
    </tr>
    <tr>
        <td colspan="6" class="right bold">PPN 11%</td>
        <td class="right bold"><!-- <?= number_format($ppn,0,',','.') ?> --></td>
    </tr>
    <tr>
        <td colspan="6" class="right bold">Grand Total Include Pajak</td>
        <td class="right bold"><!-- <?= number_format($grand_total,0,',','.') ?> --></td>
    </tr>
</table>

<!-- FOOTER -->
<div class="mt-20">
    Depok, <?= $tanggal ?>
</div>
<div class="mt-20" style="margin-top: 100px;">
    <u>Tri Ubaya Adri, M</u><br>
    Direktur
</div>

</body>
</html>
