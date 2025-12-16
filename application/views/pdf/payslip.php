<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>
    <?=$slip->period_name?>
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
        hr {
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .info td {
            padding: 3px;
        }

        /* ===== Salary Layout ===== */
        .salary-wrapper {
            width: 100%;
            margin-top: 10px;
        }
        .salary-box {
            width: 48%;
            vertical-align: top;
            display: inline-block;
        }

        .salary {
            width: 100%;
            border: 1px solid #000;
        }
        .salary th, .salary td {
            padding: 5px;
            border-bottom: 1px solid #000;
        }
        .salary th {
            background: #f2f2f2;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 10px;
        }
        .summary td {
            padding: 4px;
        }

        .footer {
            margin-top: 15px;
            font-size: 10px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<h2>PAYSLIP</h2>
<hr>

<table class="info">
    <tr>
        <td width="20%">Employee Name</td>
        <td width="30%">: <?= htmlspecialchars($slip->full_name) ?></td>
        <td width="20%">Position</td>
        <td width="30%">: <?=$slip->job_level_name?></td>
    </tr>
    <tr>
        <td>NIK</td>
        <td>: <?= htmlspecialchars($slip->emp_code) ?></td>
        <td>Work Location</td>
        <td>: <?=$slip->work_location_name?></td>
    </tr>
    <tr>
        <td>Tax Marital / Ter / Tarif</td>
        <td>: <?=$slip->marital_status_name?></td>
        <td>Grade</td>
        <td>: <?=$slip->grade_name?></td>
    </tr>
    <tr>
        <td>NPWP</td>
        <td>: <?=$slip->no_npwp?></td>
       
    </tr>
</table>

<?php
$earnings   = [];
$deductions = [];
$totalEarning = 0;
$totalDeduction = 0;

foreach ($details as $row) {
    if ($row->component_type === 'earning') {
        $earnings[] = $row;
        $totalEarning += $row->amount;
    } else {
        $deductions[] = $row;
        $totalDeduction += $row->amount;
    }
}
?>

<div class="salary-wrapper">

    <!-- ===== INCOME ===== -->
    <div class="salary-box">
        <table class="salary">
            <tr>
                <th colspan="2">INCOME</th>
            </tr>
            <?php foreach ($earnings as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row->component_name) ?></td>
                <td class="text-right"><?= number_format($row->amount, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th>Total Income</th>
                <th class="text-right"><?= number_format($totalEarning, 2) ?></th>
            </tr>
        </table>
    </div>

    <!-- ===== DEDUCTION ===== -->
    <div class="salary-box" style="float:right;">
        <table class="salary">
            <tr>
                <th colspan="2">DEDUCTION</th>
            </tr>
            <?php foreach ($deductions as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row->component_name) ?></td>
                <td class="text-right"><?= number_format($row->amount, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th>Total Deduction</th>
                <th class="text-right"><?= number_format($totalDeduction, 2) ?></th>
            </tr>
        </table>
    </div>

</div>

<!-- ===== SUMMARY ===== -->
<table class="summary">
    <tr>
        <td width="70%"><strong>Take Home Pay</strong></td>
        <td width="30%" class="text-right">
            <strong><?= number_format($slip->take_home_pay, 2) ?></strong>
        </td>
    </tr>
</table>

<div class="footer">
    This payslip is generated automatically by the system.
</div>

</body>
</html>
