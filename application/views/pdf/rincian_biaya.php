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
        <td width="85%" valign="top" align="left" style="padding-top:15px;">
            <div style="font-size:14px; font-weight:bold; line-height:1.3; padding-left:250px;">
                PT. MANDIRI AGANGTA SEJAHTERA
            </div>
            <div style="font-size:10px; line-height:1.3; padding-left:210px;">
                Jl. Kemang Raya No. 1A - RT.01/RW.09, Sukmajaya - Depok, Jawa Barat
            </div>
            <div style="font-size:10px; line-height:1.3; padding-left:260px;">
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


<!-- <table class="table-border mt-10">
    <tr>
        <th rowspan="2" class="center" width="5%">No</th>
        <th colspan="9" class="center" width="20%">Uraian</th>
        <th rowspan="2" class="center" width="10%">Total Harga</th>
        
    </tr>
    <tr>
       
        <th class="center">Rincian Prorata</th>
        <th class="center">aa</th>
        <th class="center">bb</th>
        <th class="center">cc</th>
        <th class="center">dd</th>
        <th class="center">ff</th>
        <th class="center">Hari kerja</th>
        <th class="center">Description</th>
        <th class="center">vv</th>
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
        <td><?= $row->project_outsource_id ?></td>
        <td class="center"><?= $row->customer_id ?></td>
        <td class="center"><?= $row->jumlah ?></td>
        <td class="right"><?= number_format($row->management_fee_percen,0,',','.') ?></td>
        
    </tr>
    <?php endforeach; ?>

    
    <tr>
        <td colspan="10" class="right bold">Total</td>
        <td class="right bold"><?= number_format($sub_total,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan="10" class="right bold">Management Fee (<?=$management_fee?>%)</td>
        <td class="right bold"><?= number_format($management_fee_nominal,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan="10" class="right bold">Grand Total</td>
        <td class="right bold"><?= number_format($jumlah_harga_jual,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan="10" class="right bold">PPN <?=$ppn?>%</td>
        <td class="right bold"><?= number_format($ppn_nominal,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan="10" class="right bold">Grand Total Include Pajak</td>
        <td class="right bold"><?= number_format($jumlah_sesudah_pajak,0,',','.') ?></td>
    </tr>
</table> -->



<!-- ================= ITEM 1 ================= -->
<table class="table-border mt-10" style="font-size:9px;">
    <tr>
        <th>No</th>
        <th colspan="9">Uraian</th>
        <th>Total Harga</th>
    </tr>



    <tr>
        <td class="center bold">1</td>
        <td colspan="9" class="bold">TROLLEY BOY</td>
        <td></td>
    </tr>

    <tr>
        <td></td>
        <td>Rincian Prorata</td>
        <td>Foreman Trolley Boy</td>
        <td></td>
        <td>Anggota Trolley Boy</td>
        <td></td>
        <td>Foreman Trolley Boy / HK</td>
        <td>Hari Kerja</td>
        <td></td>
        <td>Anggota Trolley Boy / HK</td>
        <td></td>
    </tr>

    <tr>
        <td></td>
        <td>
            Gapok<br>
            Foreman Trolley Boy 01 Oktober s/d 31 Oktober 2025<br>
            Anggota Trolley Boy 01 Oktober s/d 31 Oktober 2025<br>
            BPJS Kesehatan Foreman Trolley Boy<br>
            THR Foreman Trolley Boy<br>
            BPJS Kesehatan Anggota Trolley Boy<br>
            THR Anggota Trolley Boy
        </td>

        <td class="center">
           
        </td>
        <td class="center">
           3.400.000
        </td>
        <td class="center">
           3.250.000<br>
           1<br><br>
            6<br><br>
            1<br>
            1<br>
            6<br>
            6
        </td>

        <td class="center">
            <br>
            MP<br><br>
            MP<br><br>
            MP<br><br>
            MP<br>
            MP<br>
            MP
        </td>

        <td class="center">
            130.769<br>
            <br>
            <br>
            <br>
            <br>
            10.24%<br>
            12<br>
            10.24% <br>
            12
        </td>

        <td class="center">
            <br>
            26<br><br>
            154<br>
            <br>
            <br>
            <br>
            
        </td>

        <td>
            <br>
            (Gaji Pokok 3.300.000 : 26 hari kerja 1 bulan) X 26 hari kehadiran (1 personil)<br>
            (Gaji Pokok 3.150.000 : 26 hari kerja 1 bulan) X 154 hari kehadiran (6 personil)<br>
            10,24% x Gaji UMP 4.416.186 (1 personil)<br>
            Gaji Pokok 3.300.000 : 12 Bulan (1 personil)<br>
            10,24% x Gaji UMP 4.416.186 (6 Personil)<br>
            Gaji Pokok 3.150.000 : 12 Bulan (6 Personil)
        </td>

        <td class="center">
            125.000<br>
            130.769<br><br>
            125.000<br><br>
            452.217<br>
            283.333<br>
            452.217<br>
            270.833
        </td>

        <td class="right">
            <br>
            3.400.000<br><br>
            19.250.000<br><br>
            452.217<br>
            283.333<br>
            2.713.305<br>
            1.625.000
        </td>
    </tr>

    <tr>
        <td></td>
        <td class="left bold">Sub Total</td>
        <td></td>
        <td></td>
        <td style="text-align:center;">7</td>
        <td style="text-align:center;">MP</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="right bold">27.723.855</td>
    </tr>


    <!-- ================= ITEM 2 ================= -->
    <tr>
        <td class="center bold">2</td>
        <td colspan="9" class="bold">STAFF FRESH</td>
        <td></td>
    </tr>

    <tr>
        <td></td>
        <td>Rincian Prorata</td>
        <td></td>
        <td>Staff Fresh</td>
        <td></td>
        <td></td>
        <td></td>
        <td>Hari Kerja</td>
        <td></td>
        <td>Staff Fresh / Hari</td>
        <td></td>
    </tr>

    <tr>
        <td></td>
        <td>
            Gapok<br>
            Staff Fresh 01 Oktober s/d 31 Oktober 2025<br>
            BPJS Kesehatan Staff Fresh<br>
            THR Staff Fresh<br>
        </td>

        <td class="center">
           
        </td>
        <td class="center">
           3.400.000
        </td>
        <td class="center">
            <br>
           11<br><br>
           11<br>
           11
        </td>

        <td class="center">
            <br>
            MP<br><br>
            MP<br>
            MP
        </td>

        <td class="center">
            <br>
            <br><br>
            10.24%<br>
            12
        </td>

        <td class="center">
            <br>
            283<br>
            <br>
           
        </td>

        <td>
            <br>
            (Gaji Pokok 3.400.000 : 25 hari kerja 1 bulan) X 283 hari kehadiran (11 personil)<br>
            10,24% x Gaji UMP 4.416.186 (11 personil)<br>
            Gaji Pokok 3.300.000 : 12 Bulan (11 personil)
        </td>

        <td class="center">
            130.769<br>
            130.769<br><br>
            452.217<br>
            283.333
        </td>

        <td class="right">
            <br>
            37.007.692<br><br>
            4.974.392<br>
            3.116.667
        </td>
    </tr>

    <tr>
        <td></td>
        <td class="left bold">Sub Total</td>
        <td></td>
        <td></td>
        <td style="text-align:center;">11</td>
        <td style="text-align:center;">MP</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="right bold">45.098.751</td>
    </tr>



    <!-- ================= EQUIPMENT ================= -->
    <tr>
        <td></td>
        <td>Equipment</td>
        <td></td>
        <td></td>
        <td class="center">1</td>
        <td class="center">LOT</td>
        <td></td>
        <td></td>
        <td></td>
        <td class="right">150.000</td>
        <td class="right">150.000</td>
    </tr>
    <tr>
        <td></td>
        <td>Seragam</td>
        <td></td>
        <td></td>
        <td class="center">18</td>
        <td class="center">MP</td>
        <td>18 orang  x 2 seragam = 36 seragam</td>
        <td></td>
        <td>36 seragam x 95.000 : 12 bulan (18 org)</td>
        <td class="right">95.000</td>
        <td class="right">285.000</td>
    </tr>

    <!-- ================= TOTAL ================= -->
    <tr>
        
        <td colspan="10" class="center bold">TOTAL</td>
        <td class="right bold">73.257.606</td>
    </tr>
    <tr>
       
        <td colspan="10" class="center bold">Management Fee (8%)</td>
        <td class="right bold">5.860.609</td>
    </tr>
    <tr>
       
        <td colspan="10" class="center bold">GRAND TOTAL</td>
        <td class="right bold">79.118.215</td>
    </tr>
    <tr>
       
        <td colspan="10" class="center bold">PPN 11%</td>
        <td class="right bold">8.703.004</td>
    </tr>
    <tr>
       
        <td colspan="10" class="center bold">GRAND TOTAL INCLUDE PAJAK</td>
        <td class="right bold">87.821.218</td>
    </tr>
</table>



<!-- FOOTER -->
<div class="mt-20">
    Depok, <?= $invoice_date ?>
</div>
<div class="mt-20" style="margin-top: 100px;">
    <u>Tri Ubaya Adri, M</u><br>
    Direktur
</div>

</body>
</html>
