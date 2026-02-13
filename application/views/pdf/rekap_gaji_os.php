<!DOCTYPE html>
<html>
<head>
<style>
    body { font-family: DejaVu Sans; font-size: 8px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #000; padding:4px; text-align:center; }
    th { background:#e0f2fe; }
    .page-break { page-break-after: always; }


    .header {
        width: 100%;
        margin-bottom: 10px;
    }
    .company {
        font-size: 10px;
    }
    .no-border td {
        border: none;
        padding: 2px;
        vertical-align: top;
    }

</style>
</head>
<body>

<!-- ================= HEADER ================= -->
<table class="no-border header" style="margin-bottom:5px;">
    <tr>
        <!-- LOGO -->
        <td valign="top" style="width:80px; padding:0;">
            <?php
            $path = FCPATH . 'public/assets/images/logo/mas_logo_tsp.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            ?>
            <img src="<?= $base64 ?>" height="75" style="display:block;">
        </td>

        <!-- TEXT -->
        <td valign="top" style="padding-left:6px; text-align:left;">
            <div style="font-size:10px; line-height:1.3;">
                PT. MANDIRI AGANGTA SEJAHTERA<br>
                Jl. Kemang Raya No.1A RT.001/RW009<br>
                Tlp/Fax (021) 77844672<br>
                Sukmajaya, Depok – Jawa Barat
            </div>
        </td>
    </tr>
</table>




<h1 align="center" style="margin-bottom:8px;">
    Rekapitulasi Gaji Periode <?= $periode_penggajian ?>
</h1>

<p align="center" style="font-size:12px; margin:0;">
    <span style="
        background-color:#96d3fc;
        padding:4px 10px;
        border-radius:4px;
        display:inline-block;
        /*font-weight:bold;*/
    ">
        Project : <?= $project_name ?>
    </span>
</p>

<p align="center" style="font-size:12px; margin-top:4px; margin-bottom:10px;">
    <!-- Periode : xx -->
</p>

<!-- <hr> -->


<?php foreach ($projects as $project): ?>

    <!-- ================= SUMMARY ================= -->
    <!-- <h3>Project : <?= $project['project_name'] ?></h3> -->

    <table>
        <thead>

            <!-- ====== GROUP HEADER ====== -->
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIK</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Rekening</th>
                <th rowspan="2">Jabatan</th>
                <th rowspan="2">Tgl Masuk</th>

                <th colspan="3">PENDAPATAN</th>

                <th rowspan="2">Gaji Kotor</th>

                <th colspan="9">POTONGAN</th>

                <th rowspan="2">Gaji Bersih</th>
            </tr>

            <!-- ====== DETAIL HEADER ====== -->
            <tr>
                <th>Gaji Bulanan</th>
                <th>Gaji Harian</th>
                <th>Tunjangan</th>

                <th>BPJS Kesehatan</th>
                <th>BPJS TK</th>
                <!-- <th>Absen</th> -->
                <th>Seragam</th>
                <th>Pelatihan</th>
                <th>Lain-Lain</th>
                <th>Hutang</th>
                <th>Sosial</th>
                <th>Payroll</th>
                <th>PPH 120</th>
            </tr>

        </thead>

        <tbody>

            <?php
            $total_gaji_bulanan = 0;
            $total_gaji_harian  = 0;
            $total_tunjangan    = 0;
            $total_gaji_kotor   = 0;
            $total_bpjs_kes     = 0;
            $total_bpjs_tk      = 0;
            // $total_absen        = 0;
            $total_seragam      = 0;
            $total_pelatihan    = 0;
            $total_lain         = 0;
            $total_hutang       = 0;
            $total_sosial       = 0;
            $total_payroll      = 0;
            $total_pph          = 0;
            $total_gaji_bersih  = 0;
            ?>

            <?php foreach ($project['summary'] as $row): ?>

                <?php
                $total_gaji_bulanan += (int)$row[6];
                $total_gaji_harian  += (int)$row[7];
                $total_tunjangan    += (int)$row[8];
                $total_gaji_kotor   += (int)$row[9];
                $total_bpjs_kes     += (int)$row[10];
                $total_bpjs_tk      += (int)$row[11];
                /*$total_absen        += (int)$row[12];*/
                $total_seragam      += (int)$row[12];
                $total_pelatihan    += (int)$row[13];
                $total_lain         += (int)$row[14];
                $total_hutang       += (int)$row[15];
                $total_sosial       += (int)$row[16];
                $total_payroll      += (int)$row[17];
                $total_pph          += (int)$row[18];
                $total_gaji_bersih  += (int)$row[19];
                ?>

                <tr>
                    <?php foreach ($row as $i => $cell): ?>
                        <td>
                            <?php
                            // Kolom rekening (index 3) jangan diformat
                            if ($i == 3) {
                                echo $cell;
                            }
                            // Kolom numeric mulai dari index 6
                            else if ($i >= 6 && is_numeric($cell)) {
                                echo number_format($cell);
                            } 
                            else {
                                echo $cell;
                            }
                            ?>
                        </td>
                    <?php endforeach ?>

                </tr>

            <?php endforeach ?>

            <!-- ================= TOTAL ROW ================= -->
            <tr style="font-weight:bold; background:#f1f5f9;">
                <td colspan="6">TOTAL</td>
                <td><?= number_format($total_gaji_bulanan) ?></td>
                <td><?= number_format($total_gaji_harian) ?></td>
                <td><?= number_format($total_tunjangan) ?></td>
                <td><?= number_format($total_gaji_kotor) ?></td>
                <td><?= number_format($total_bpjs_kes) ?></td>
                <td><?= number_format($total_bpjs_tk) ?></td>
               
                <td><?= number_format($total_seragam) ?></td>
                <td><?= number_format($total_pelatihan) ?></td>
                <td><?= number_format($total_lain) ?></td>
                <td><?= number_format($total_hutang) ?></td>
                <td><?= number_format($total_sosial) ?></td>
                <td><?= number_format($total_payroll) ?></td>
                <td><?= number_format($total_pph) ?></td>
                <td><?= number_format($total_gaji_bersih) ?></td>
            </tr>

        </tbody>

    </table>

   
<?php endforeach; ?>

</body>
</html>
