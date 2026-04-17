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
        Meeting Name : <?= $data[0]->meeting_name ?> <br>
        Meeting Type : <?= $data[0]->meeting_type_name ?> <br>
        Date : <?= $data[0]->meeting_date ?>  <br>
        Time : <?= $data[0]->meeting_times ?> <br>
        <?php
        if($data[0]->meeting_type_id == 1 || $data[0]->meeting_type_id == 3){
            ?>
            Room : <?= $data[0]->room_name ?> <br>
            <?php
        }
        if($data[0]->meeting_type_id == 2 || $data[0]->meeting_type_id == 3){
            ?>
            Platform : <?= $data[0]->meeting_platform_name ?> <br>
            <?php
        }
        ?>
        
        Description : <?= $data[0]->description ?> <br>
    </span>
</p>

<br>


<table style="margin: top:10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Employee Name</th>
            <th>Check-in Time</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($absen as $rows): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td class="left"><?= $rows->full_name ?></td>
            <td><?= $rows->checkin_time ?></td>
          
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
