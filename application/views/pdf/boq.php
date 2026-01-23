<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BOQ</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 3px;
        }

        .company-logo {
            height: 40px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .block td {
            padding: 4px 2px;
        }

        .summary td {
            padding: 4px;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            font-size: 10px;
            text-align: center;
            color: #555;
        }

        .summary-table td {
            padding: 4px 2px;
            vertical-align: top;
        }

        .label {
            width: 65%;
        }

        .colon {
            width: 5%;
            text-align: center;
        }

        .currency {
            width: 10%;
        }

        .value {
            width: 20%;
            text-align: right;
        }

        .section-gap {
            margin-top: 10px;
        }

        .hr-total {
            border-top: 1px solid #000;
            margin: 8px 0 6px 0;
        }

        .small-text {
            font-size: 10px;
            font-weight: normal;
        }

        .mt-8 {
            margin-top: 8px;
        }

        .mt-12 {
            margin-top: 12px;
        }



    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<table>
    <tr>
        <td width="50%">
            <img src="<?= base_url('public/assets/images/logo/mas_logo.png') ?>" style="height:40px;">
            
        </td>
        <td width="50%" align="right" style="font-weight:bold;">
            <!-- gen no -->
        </td>
    </tr>
</table>

<h2>BOQ</h2>
<br>
<!-- ===== EMPLOYEE INFO ===== -->
<table class="info">
    <tr>
        <td>Customer</td>
        <td>: <?= $header->customer_name ?></td>
        
    </tr>
    <tr>
        <td>Project</td>
        <td>: <?= $header->project_name ?></td>
        
    </tr>
    <tr>
        <td>Periode</td>
        <?php
            $periode='';
            if($header->periode_start != '' && $header->periode_end != ''){
                $periode = $header->periode_start.' s/d '.$header->periode_end;
            }

        ?>
        <td>: <?= $periode ?></td>
       
    </tr>
  
</table>

<hr>

<!-- TABLE DETAIL BOQ -->
<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr style="background:#eee;font-weight:bold;">
            <th width="5%">No</th>
            <th width="45%">Jenis Pekerjaan</th>
            <th width="15%">Jumlah</th>
            <th width="15%">Harga Satuan</th>
            <th width="20%">Jumlah Harga</th>
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
            <tr style="background:#f5e965;font-weight:bold;">
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
        <td colspan="4" align="right"><b>Jumlah sebelum Management Fee</b></td>
        <td align="right"><?= nf($jumlah_before_management_fee) ?></td>
    </tr>

    <tr>
        <td colspan="4" align="right">
            <b>Management Fee (<?= nf($management_fee) ?> %)</b>
        </td>
        <td align="right"><?= nf($management_fee_value) ?></td>
    </tr>

    <tr style="background:#ffb3b3;font-weight:bold;">
        <td colspan="4" align="right">
            JUMLAH TOTAL (Jumlah + Management Fee)
        </td>
        <td align="right"><?= nf($jumlah_total) ?></td>
    </tr>

    <tr>
        <td colspan="4" align="right">
            PPN (<?= nf($ppn_percen) ?> %)
        </td>
        <td align="right"><?= nf($ppn_value) ?></td>
    </tr>

    <tr>
        <td colspan="4" align="right">
            PPH 23 (<?= nf($pph_percen) ?> %)
        </td>
        <td align="right"><?= nf($pph_value) ?></td>
    </tr>

    <tr style="background:#d3d3d3;font-weight:bold;">
        <td colspan="4" align="right">GRAND TOTAL</td>
        <td align="right"><?= nf($grand_total_final) ?></td>
    </tr>


    </tbody>
</table>






<!-- <div class="footer">
    This BOQ is generated automatically by the system.
</div> -->

</body>
</html>
