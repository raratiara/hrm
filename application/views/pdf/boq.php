<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BOQ</title>

    <style>
        @page {
            margin-top: 4mm;
            margin-left: 12mm;
            margin-right: 12mm;
            margin-bottom: 6mm;
        }

        body {
            font-family: Calibri, DejaVu Sans, sans-serif;
            font-size: 8px; 
            color: #000;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 1px 0;
            font-size: 9px; 
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        hr {
            margin: 2px 0;
        }

        /* HEADER */
        .company-logo {
            height: 26px;
        }

        .info {
            width: 70%;
            margin: 2px auto 3px;
            padding: 1px 2px;
            font-size: 8px;
            align: center;
        }

        .info td {
            padding: 0.5px 1px;
            font-weight: bold;

        }

        /* BOQ TABLE */
        .boq-table {
            width: 100%;
            table-layout: fixed;
            margin-top: 2px;
            border: 1px solid #000;
        }

        .boq-table th {
            padding: 2px;
            font-size: 8px;
            background: #e5e6e6;
            border-bottom: 1px solid #000;
        }

        .boq-table td {
            padding: 1px 2px;
            font-size: 8px;
            border-bottom: 1px solid #000;
        }

        .boq-table td:nth-child(2) {
            line-height: 1.05; /* 🔽 rapetin baris */
            word-break: break-word;
        }

        .boq-table th,
        .boq-table td {
            border-right: 1px solid #000;
        }

        /* SECTION */
        .section-row {
            background: #f5e965;
            font-weight: bold;
            font-size: 8px;
        }

        .total-header {
            background: #eef6ff;
            font-weight: bold;
            font-size: 8px;
        }

        .grand-total {
            background: #e0f2fe;
            font-weight: bold;
        }

        .grand-total td:last-child {
            font-size: 9px; /* masih sedikit dibesarin */
        }

        /* FOOTER */
        .footer {
            margin-top: 3px;
            font-size: 7px;
            text-align: center;
            color: #555;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

    </style>

</head>
<body>

<!-- ===== HEADER ===== -->
<table style="margin-top:-10px !important">
    <tr>
        <td width="20%">
            <?php
            $path = FCPATH . 'public/assets/images/logo/mas_logo_tsp.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            ?>
            <img src="<?= $base64 ?>" height="60" width="60">

            
            
        </td>
        <td width="80%" align="right" style="text-align: left; font-size:8px;">
            <span style="margin-left:70px">Head Office : Jl. Kemang Raya No 1A. Kec Sukmajaya, kota Depok</span><br>
            <span style="margin-left:100px">Telp : 021-77844672 &nbsp; &nbsp; Email : info@mandirias.co.id</span>

        </td>
    </tr>
</table>
<hr>
<h2><span style="color: #4489c7;">Penawaran Harga PT Mandiri Agangta Sejahtera</span></h2>

<!-- ===== EMPLOYEE INFO ===== -->

<table class="info" style="margin-left:215px">
    <!-- <tr>
        <td class="label" style="text-align:center;">Project</td>
        <td class="colon">:</td>
        <td class="value"><?= $header->project_name ?></td>
    </tr>
    <tr>
        <td class="label">Lokasi</td>
        <td class="colon">:</td>
        <td class="value"><?= $header->lokasi_name ?></td>
    </tr> -->

    <tr>
        <td style="text-align:left;">Project</td>
        <td >:</td>
        <td style="text-align:left"><?= $header->project_name ?></td>
    </tr>
    <tr>
        <td style="text-align:left;">Lokasi</td>
        <td >:</td>
        <td style="text-align:left;"><?= $header->lokasi_name ?></td>
    </tr>
   
</table>


<!-- TABLE DETAIL BOQ -->
<!-- <table border="1" cellpadding="4" cellspacing="0"> -->
<table class="boq-table">
    <thead>
        <tr>
            <th rowspan="2" style="width:4%">No</th>
            <th rowspan="2" style="width:40%">Jenis Pekerjaan</th>
            <th rowspan="2" style="width:10%">Jumlah</th>
            <th colspan="2" class="period-header" style="width:46%">Periode</th>
        </tr>
        <tr>
            <th style="width:23%">Harga Satuan</th>
            <th style="width:23%">Jumlah Harga</th>
        </tr>

    </thead>
    <tbody>
    <?php

    $management_fee = $header->management_fee;
    $ppn_percen = $header->ppn_percen;
    $ppn_harga = $header->ppn_harga;
    $pph_percen = $header->pph_percen;
    $pph_harga = $header->pph_harga;

    $rd = $detail;

    $last_header = '';
    $last_parent = '';
    $no_in_header = 0;

    $sum_parent_jumlah = 0;
    $sum_parent_harga  = 0;
    $gaji_pokok_parent_jumlah = 0;


    $sum_header_jumlah = 0;
    $sum_header_harga  = 0;

    $sum_all_jumlah = 0;
    $sum_all_harga  = 0;

    $header_parent_count = [];

    function nf($val) {
        if ($val === null || $val === '') return '0';
        $val = str_replace([',', 'Rp', ' '], '', $val);
        return number_format((float)$val);
    }

    foreach ($rd as $f) {
        if (!isset($header_parent_count[$f->header_name])) {
            $header_parent_count[$f->header_name] = [];
        }
        if (!empty($f->parent_name)) {
            $header_parent_count[$f->header_name][$f->parent_name] = true;
        }
    }

    foreach ($rd as $f):

        if ($last_header !== '' && $f->header_name !== $last_header):

            $parentCount = count($header_parent_count[$last_header]);

            if ($last_parent !== '' && $parentCount > 1): ?>
                <tr style="background:#fafafa;font-weight:bold;">
                    <td colspan="2" align="right">Total <?= $last_parent ?></td>
                    <td align="right"><?= nf($sum_parent_jumlah) ?></td>
                    <td></td>
                    <td align="right"><?= nf($sum_parent_harga) ?></td>
                </tr>
            <?php
                $sum_parent_jumlah = 0;
                $sum_parent_harga  = 0;
            endif; ?>

            <tr style="background:#91f560;font-weight:bold;">
                <td colspan="2" align="right">Total <?= $last_header ?></td>
                <td align="right"><?= nf(
                    strtoupper(trim($last_header)) === 'GAJI POKOK'
                        ? $gaji_pokok_parent_jumlah
                        : $sum_header_jumlah
                ) ?></td>
                <td></td>
                <td align="right"><?= nf($sum_header_harga) ?></td>
            </tr>

            if (strtoupper(trim($last_header)) === 'GAJI POKOK') {
                $sum_header_jumlah = $gaji_pokok_parent_jumlah;
                $gaji_pokok_parent_jumlah = 0;
            }



            <?php
            $sum_all_jumlah += $sum_header_jumlah;
            $sum_all_harga  += $sum_header_harga;

            $sum_header_jumlah = 0;
            $sum_header_harga  = 0;
            $last_parent = '';
        endif;



        
        $parentCount = isset($header_parent_count[$last_header])
            ? count($header_parent_count[$last_header])
            : 0;

        if ($last_parent !== '' && $f->parent_name !== $last_parent && $parentCount > 1): ?>
            <tr style="background:#fafafa;font-weight:bold;">
                <td colspan="2" align="right">Total <?= $last_parent ?></td>
                <td align="right"><?= nf($sum_parent_jumlah) ?></td>
                <td></td>
                <td align="right"><?= nf($sum_parent_harga) ?></td>
            </tr>
        <?php
            $sum_parent_jumlah = 0;
            $sum_parent_harga  = 0;
        endif;


        
        if ($f->header_name !== $last_header): ?>
            <!-- <tr style="background:#f5e965;font-weight:bold;"> -->
            <tr class="section-row">
                <td colspan="5"><?= strtoupper($f->header_name) ?></td>
            </tr>
        <?php
            $last_header = $f->header_name;
            $last_parent = '';
            $no_in_header = 0;
        endif;


        
        if (!empty($f->parent_name) && $f->parent_name !== $last_parent): ?>
            <tr style="background:#fafafa;font-weight:bold;">
                <td colspan="5" style="padding-left:15px;">
                    <?= $f->parent_name ?>
                </td>
            </tr>
        <?php
            $last_parent = $f->parent_name;
        endif;


        
        $no_in_header++;
        $jumlah_val = (float) $f->jumlah;
        $harga_val  = (float) $f->jumlah_harga;
        ?>

        <tr>
            <td align="center"><?= $no_in_header ?></td>
            <td><?= $f->name ?></td>
            <td align="right"><?= nf($jumlah_val) ?></td>
            <td align="right"><?= nf($f->harga_satuan) ?></td>
            <td align="right"><?= nf($harga_val) ?></td>
        </tr>

        <?php
        
        //$sum_parent_jumlah += $jumlah_val;
        //$sum_parent_harga  += $harga_val;

        //$sum_header_jumlah += $jumlah_val;
        //$sum_header_harga  += $harga_val;


        $sum_parent_jumlah += $jumlah_val;
        $sum_parent_harga  += $harga_val;

        // HEADER GAJI POKOK → ambil JUMLAH dari parent GAJI POKOK saja
        if (strtoupper(trim($last_header)) === 'GAJI POKOK') {

            if (strtoupper(trim($last_parent)) === 'GAJI POKOK') {
                $gaji_pokok_parent_jumlah += $jumlah_val;
            }

        } else {
            // HEADER LAIN NORMAL
            $sum_header_jumlah += $jumlah_val;
        }

        // harga tetap dijumlahkan normal
        $sum_header_harga += $harga_val;


    endforeach;


   
   
    $jumlah_before_management_fee = $sum_all_harga;

    
    $management_fee_value = ($management_fee / 100) * $jumlah_before_management_fee;

    
    $jumlah_total = $jumlah_before_management_fee + $management_fee_value;

    $ppn_value=0; $pph_value=0;
    if($ppn_percen > 0){
        $ppn_value = ($ppn_percen / 100) * $jumlah_total;
    }
    if($pph_percen > 0){
        $pph_value = ($pph_percen / 100) * $jumlah_total;
    }

    
    $grand_total_final = $jumlah_total + $ppn_value - $pph_value;
    



    
    if (
        $last_parent !== '' &&
        isset($header_parent_count[$last_header]) &&
        count($header_parent_count[$last_header]) > 1
    ): ?>

        <tr style="background:#fafafa;font-weight:bold;">
            <td colspan="2" align="right">Total <?= $last_parent ?></td>
            <td align="right"><?= nf($sum_parent_jumlah) ?></td>
            <td></td>
            <td align="right"><?= nf($sum_parent_harga) ?></td>
        </tr>
    <?php endif; ?>

    <tr style="background:#91f560;font-weight:bold;">
        <td colspan="2" align="right">Total <?= $last_header ?></td>
        <td align="right"><?= nf(
            strtoupper(trim($last_header)) === 'GAJI POKOK'
                ? $gaji_pokok_parent_jumlah
                : $sum_header_jumlah
        ) ?></td>
        <td></td>
        <td align="right"><?= nf($sum_header_harga) ?></td>
    </tr>

    <?php
    if (strtoupper(trim($last_header)) === 'GAJI POKOK') {
        $sum_header_jumlah = $gaji_pokok_parent_jumlah;
        $gaji_pokok_parent_jumlah = 0;
    }
    ?>


    <?php
    $sum_all_jumlah += $sum_header_jumlah;
    $sum_all_harga  += $sum_header_harga;
    ?>

   
    <tr>
        <td></td>
        <td align="right"><b>Jumlah</b></td>
        <td></td>
        <td></td>
        <td align="right" style="font-weight:bold; background-color: #e0f2fe;"><?= nf($jumlah_before_management_fee) ?></td>
    </tr>

    <tr>
        <td></td>
        <td align="right">
            Management Fee 
        </td>
        <td align="right"><?= nf($management_fee) ?> %</td>
        <td></td>
        <td align="right" style="font-weight:bold; background-color: #e0f2fe;"><?= nf($management_fee_value) ?></td>
    </tr>

    <tr style="background:#ffb3b3;font-weight:bold;">
        <td></td>
        <td align="right">
            JUMLAH TOTAL (Jumlah + Management Fee)
        </td>
        <td></td>
        <td></td>
        <td align="right"><?= nf($jumlah_total) ?></td>
    </tr>

    <tr>
        <td></td>
        <td align="right">
            PPN 
        </td>
        <td align="right"><?= nf($ppn_percen) ?> %</td>
        <td></td>
        <td align="right" style="font-weight:bold"><?= nf($ppn_value) ?></td>
    </tr>

    <tr>
        <td></td>
        <td align="right">
            PPH 23 
        </td>
        <td align="right"><?= nf($pph_percen) ?> %</td>
        <td></td>
        <td align="right" style="font-weight:bold"><?= nf($pph_value) ?></td>
    </tr>

    <tr class="grand-total">
        <td></td>
        <td align="right">GRAND TOTAL</td>
        <td></td>
        <td></td>
        <td align="right"><?= nf($grand_total_final) ?></td>
    </tr>



    </tbody>
</table>


<div style="margin-top: 25px; font-size: 8px;">
    Keterangan : <br>
    * Belum pakai Gaji UMK KABUPATEN TANGERANG 2026 <br>
    * Sistem Kerja 8 Jam / Shift <br>
    * Perhitungan Pajak di ambil dari Manajemen Fee <br>
    * Tahun 2026 Perubahan Sepatu 1 Pasang/Orang 


</div>



<!-- <div class="footer">
    This BOQ is generated automatically by the system.
</div> -->

</body>
</html>
