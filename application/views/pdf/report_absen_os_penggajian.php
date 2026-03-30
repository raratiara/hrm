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
    <!-- Periode : xx -->
</p>

<hr>


<?php foreach ($projects as $project): ?>

    <!-- ================= SUMMARY ================= -->
    <!-- <h3>Project : <?= $project['project_name'] ?></h3> -->

    <table>
        <thead>
        <tr>
            <th>No</th><th>NIK</th><th>Karyawan</th>
            <th>Total Hari Kerja</th><th>Total Masuk</th>
            <th>Total Ijin</th><th>Total Cuti</th><th>Total Alfa</th>
            <th>Total Lembur</th><th>Total Jam Kerja</th><th>Total Jam Lembur</th>
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

   
<?php endforeach; ?>

</body>
</html>
