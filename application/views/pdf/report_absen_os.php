<!DOCTYPE html>
<html>
<head>
<style>
    body { font-family: DejaVu Sans; font-size: 10px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #000; padding:4px; text-align:center; }
    th { background:#e0f2fe; }
    .page-break { page-break-after: always; }
</style>
</head>
<body>

<h1 align="center" style="margin-bottom:8px;">
    SUMMARY ABSENSI KARYAWAN
</h1>

<p align="center" style="font-size:16px; margin:0;">
    <span style="
        background-color:#96d3fc;
        padding:4px 10px;
        border-radius:4px;
        display:inline-block;
        font-weight:bold;
    ">
        Project : <?= $project_name ?>
    </span>
</p>

<p align="center" style="font-size:12px; margin-top:4px; margin-bottom:10px;">
    Periode : <?= $periode ?>
</p>

<hr>


<?php foreach ($projects as $project): ?>

    <!-- ================= SUMMARY ================= -->
    <!-- <h3>Project : <?= $project['project_name'] ?></h3> -->

    <table>
        <thead>
        <tr>
            <th>No</th><th>NIK</th><th>Nama</th>
            <th>Lokasi</th><th>Shift</th>
            <th>WFO</th><th>WFH</th><th>Onsite</th>
            <th>Sakit</th><th>Cuti</th><th>Total Jam</th>
            <th>Terlambat</th><th>Pulang Cepat</th>
            <th>Lembur (Jam)</th><th>Lembur (Rp)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($project['summary'] as $row): ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td><?= $cell ?></td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- ================= DETAIL ================= -->
    <?php
    $lastKey = array_key_last($project['details']);
    foreach ($project['details'] as $name => $sheet):
    ?>

        <h3 style="background-color: #fcf896;"><?= $name ?></h3>

        <?php foreach ($sheet['subtitle'] as $sub): ?>
            <b><?= $sub[0] ?> :</b> <?= $sub[1] ?><br>
        <?php endforeach ?>

        <br>

        <table>
            <thead>
            <tr>
                <th>No</th><th style="width: 70px;">Tanggal</th><th>Shift</th>
                <th>WFO</th><th>WFH</th><th>Onsite</th>
                <th>Sakit</th><th>Cuti</th>
                <th>Jam Masuk</th><th>Jam Pulang</th>
                <th>Total Jam</th><th>Terlambat</th>
                <th>Pulang Cepat</th>
                <th>Lembur Jam</th><th>Lembur Rp</th>
                <th>Keterangan</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($sheet['rows'] as $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <td><?= $cell ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>

        <?php if ($name !== $lastKey): ?>
            <div class="page-break"></div>
        <?php endif; ?>

    <?php endforeach; ?>

<?php endforeach; ?>

</body>
</html>
