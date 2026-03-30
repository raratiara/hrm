<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background: #e0f2fe;
        }
        td.left {
            text-align: left;
        }
    </style>
</head>
<body>


<h2 align="center" style="margin-bottom:8px;">
    <?= $title ?>
</h2>

<p align="center" style="font-size:10px; margin:0;">
    <span style="
        /*background-color:#96d3fc;*/
        padding:4px 10px;
        border-radius:4px;
        display:inline-block;
        /*font-weight:bold;*/
    ">
        Project : <?= $project ?> <br>
Periode Penggajian : <?= $periode_penggajian ?>  <br>
Periode Absensi : <?= $periode_absensi ?> <br>
    </span>
</p>

<br>


<table style="margin: top:10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Karyawan</th>
            <th>Hari Kerja</th>
            <th>Masuk</th>
            <th>Ijin</th>
            <th>Cuti</th>
            <th>Alfa</th>
            <th>Total Jam Kerja</th>
            <th>Total Jam Lembur</th>
            <th>Total Nominal Lembur</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($data as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td class="left"><?= $row->full_name ?></td>
            <td><?= $row->total_hari_kerja ?></td>
            <td><?= $row->total_masuk ?></td>
            <td><?= $row->total_ijin ?></td>
            <td><?= $row->total_cuti ?></td>
            <td><?= $row->total_alfa ?></td>
            <td><?= $row->total_jam_kerja ?></td>
            <td><?= $row->total_jam_lembur ?></td>
            <td><?= $row->total_lembur ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
