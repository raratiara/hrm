<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 7px;
    color: #000;
    margin: 0;
    padding: 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    line-height: 1.1;
}

td, th {
    padding: 2px 3px;
    vertical-align: middle;
    font-size: 7px;
}

.table-border th,
.table-border td {
    border: 0.5px solid #000;
}

.center { text-align: center; }
.right  { text-align: right; }
.bold   { font-weight: bold; }

.no-border td {
    border: none;
    padding: 1px 2px;
}

.mt-10 { margin-top: 4px; }
.mt-20 { margin-top: 6px; }

hr {
    margin: 4px 0;
    border: 1px solid #000;
}

tr { page-break-inside: avoid; }

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
        <td width="85%" valign="top" align="left" style="padding-top:15px;">
            <div style="font-size:12px; font-weight:bold; line-height:1.1; padding-left:250px;">
                PT. MANDIRI AGANGTA SEJAHTERA
            </div>
            <div style="font-size:8px; line-height:1.1; padding-left:210px;">
                Jl. Kemang Raya No. 1A - RT.01/RW.09, Sukmajaya - Depok, Jawa Barat
            </div>
            <div style="font-size:8px; line-height:1.1; padding-left:260px;">
                Telp : 021-77844672 &nbsp;&nbsp; Email : info@mandirias.co.id
            </div>
        </td>
    </tr>
</table>


<hr style="border:1px solid #000; margin:8px 0;">



<!-- <h2 style="text-align: center;"><u>Rincian Biaya</u></h2>
<h3 style="text-align: center;"><?= $no_invoice ?></h3> -->

<div style="text-align: center;">
    <span style="font-size:9px; font-weight:bold;"><u>RINCIAN BIAYA</u></span><br>
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
                <tr>
                    <td>Periode Absensi</td>
                    <td>:</td>
                    <td><?= $tgl_start_absen ?> s/d <?= $tgl_end_absen ?></td>
                </tr>
            </table>
        </td>
       
    </tr>
</table>


<?php

$total_row = 0;

// 1 row jenis pekerjaan
$total_row++;

// 1 row header rincian
$total_row++;

// 1 row GAPOK
$total_row++;

// looping gaji
$total_row += count($items);

// looping BPJS Kesehatan
$total_row += count($items);

// looping BPJS TK
$total_row += count($items);

// subtotal
$total_row++;

// equipment
$total_row += 1;

// total + management + grand + ppn + grand include
$total_row += 5;

?>


<?php
$job_count = count($items);

// 2 kolom awal (No + Rincian)
// + job title dinamis
// + 6 kolom tetap belakang
$total_colspan = 2 + $job_count + 6;

$ttl_personil = 0; $total_seragam=0; $nominal_management_fee=0;
foreach ($items as $it) {
    $ttl_personil += (int) $it->jumlah_personil;
    $total_seragam += (int) $it->total_seragam;
    $nominal_management_fee += (int) $it->nominal_management_fee;
}



$ttl_personil = 0;

$subtotal = 0;
$total_seragam = 0;
$total_tunjangan_jabatan = 0;

foreach ($items as $it) {

    $ttl_personil += (int) $it->jumlah_personil;

    // Total manpower cost per jabatan
    $subtotal +=
        (int)$it->total_gaji +
        (int)$it->total_bpjs_kesehatan +
        (int)$it->total_bpjs_tk;

    $total_seragam += (int)$it->total_seragam;
    $total_tunjangan_jabatan += (int)$it->total_tunjangan_jabatan;
}



// TOTAL sebelum management fee
$total = $subtotal + $total_seragam + $total_tunjangan_jabatan;

// Nominal management fee
$nominal_management_fee = $total * ($management_fee / 100);

// Grand total sebelum pajak
$grand_total = $total + $nominal_management_fee;

// PPN 11%
$ppn = $grand_total * 0.11;

// Grand total include pajak
$grand_total_include = $grand_total + $ppn;

?>





<!-- ================= ITEM 1 ================= -->
<table class="table-border mt-10" style="font-size:9px;">
    
   

    <tr>
        <th rowspan="<?= $total_row ?>" style="vertical-align: top; padding-top:2px;">1</th>
        <td colspan="<?= $total_colspan - 1 ?>" class="bold">
            <?= $jenis_pekerjaan ?>
        </td>
    </tr>

    <tr>
        
        <th >Rincian</th>

        <?php foreach($items as $it): ?>
            <th class="center"><?= $it->job_title_name ?></th>
        <?php endforeach; ?>

        <th ></th>
        <th >Hari Kerja</th>
        <th >Gaji / Hari</th>
        <th >Harga</th>
        <th >Uraian</th>
        <th >Total Harga</th>
    </tr>

    <tr>
       
        <td>GAPOK</td>

        <?php foreach($items as $it): ?>
            <td class="center">
                <?= number_format($it->total_gaji ?? 0,0,',','.') ?>
            </td>
        <?php endforeach; ?>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>


    <!--tr ini tolong looping sebanyak rownya (nama job title)-->
    <?php 
    
    foreach($items as $row): 
    ?>

    <tr>
        
        <td>
            <?= $row->job_title_name ?> 
            
        </td>
        
        <td colspan="<?=$job_count?>" class="center">
            <?=$row->jumlah_personil?>
        </td>
        
        <td class="center">MP</td>
        <td class="center"><?= $row->total_masuk ?? 0 ?></td>
        <td class="right"><?= number_format($row->gaji_harian ?? 0,0,',','.') ?></td>
        <td class="right"><?= number_format($row->total_gaji ?? 0,0,',','.') ?></td>
        <td class="center">(Total hari kerja <?= $row->job_title_name ?> x Gaji per hari)</td>
        <td class="right"><?= number_format($row->total_gaji ?? 0,0,',','.') ?></td>
    </tr>

    <?php endforeach; ?>


    <!--tr ini tolong looping sebanyak rownya (bpjs kesehatab)-->
    <?php foreach($items as $row): ?>
    <tr>
        
        <td>BPJS Kesehatan <?= $row->job_title_name ?></td>

        <td colspan="<?=$job_count?>" class="center">
            <?=$row->jumlah_personil?>
        </td>

        <td class="center">MP</td>
        <td class="center"></td>
        <td class="right"></td>
        <td class="right"></td>
        <td class="center">(4% dari Gaji bulanan <?= $row->job_title_name ?>)</td>
        <td class="right"><?= number_format($row->total_bpjs_kesehatan ?? 0,0,',','.') ?></td>
    </tr>
    <?php endforeach; ?>


    <!--tr ini tolong looping sebanyak rownya (bpjs ketenagakerjaan)-->
    <?php foreach($items as $row): ?>
    <tr>
        
        <td>BPJS TK <?= $row->job_title_name ?></td>

        <td colspan="<?=$job_count?>" class="center">
            <?=$row->jumlah_personil?>
        </td>

        <td class="center">MP</td>
        <td class="center"></td>
        <td class="right"></td>
        <td class="right"></td>
        <td class="center">(6.24% dari Gaji bulanan <?= $row->job_title_name ?>)</td>
        <td class="right"><?= number_format($row->total_bpjs_tk ?? 0,0,',','.') ?></td>
    </tr>
    <?php endforeach; ?>

    <tr>
        
        <td class="left bold">Sub Total</td>
        <td colspan="<?=$job_count?>" class="center">
            <?=$ttl_personil?>
        </td>
        <td style="text-align:center;"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="right bold">
            <?= number_format($subtotal,0,',','.') ?>
        </td>
    </tr>


    

    <tr>
        
        <td>Seragam</td>
        <td colspan="<?=$job_count?>" class="center">
            <?=$row->jumlah_personil?> orang
        </td>
        <td class="center"></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="right"></td>
        <td class="right"><?=$total_seragam?></td>
    </tr>


    <tr>
        
        <td>Tunjangan Jabatan</td>
        <td colspan="<?=$job_count?>" class="center">
            
        </td>
        <td class="center"></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="right"></td>
        <td class="right"><?=$row->total_tunjangan_jabatan?></td>
    </tr>

    <!-- ================= TOTAL ================= -->
    <tr>
        
        <td colspan="8" class="center bold">TOTAL</td>
        <td class="right bold"><?= number_format($total,0,',','.') ?></td>
    </tr>
    <tr>
       
        <td colspan="8" class="center bold">Management Fee (<?=$management_fee?>%)</td>
        <td class="right bold"><?= number_format($nominal_management_fee,0,',','.') ?></td>
    </tr>
    <tr>
       
        <td colspan="8" class="center bold">GRAND TOTAL</td>
        <td class="right bold"><?= number_format($grand_total,0,',','.') ?></td>
    </tr>
    <tr>
       
        <td colspan="8" class="center bold">PPN 11%</td>
        <td class="right bold"><?= number_format($ppn,0,',','.') ?></td>
    </tr>
    <tr>
       
        <td colspan="9" class="center bold">GRAND TOTAL INCLUDE PAJAK</td>
        <td class="right bold"><?= number_format($grand_total_include,0,',','.') ?></td>
    </tr>
</table>



<!-- FOOTER -->
<div class="mt-20">
    Depok, <?= $invoice_date ?>
</div>
<div class="mt-20" style="margin-top: 50px;">
    <u>Tri Ubaya Adri, M</u><br>
    Direktur
</div>

</body>
</html>
