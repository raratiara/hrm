<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        hr {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .info td {
            padding: 4px;
        }
        .salary th, .salary td {
            border: 1px solid #000;
            padding: 6px;
        }
        .salary th {
            background: #f2f2f2;
            text-align: left;
        }
        .text-right {
            text-align: right;
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
        <td width="20%">Period</td>
        <td width="30%">: <?= htmlspecialchars($slip->period_name) ?></td>
    </tr>
    <tr>
        <td>Employee ID</td>
        <td>: <?= htmlspecialchars($slip->emp_code) ?></td>
        <td>Payslip Number</td>
        <td>: <?= htmlspecialchars($slip->payslip_number) ?></td>
    </tr>
    <tr>
        <td>Department</td>
        <td>: <?= htmlspecialchars($slip->dept_name) ?></td>
        <td>Print Date</td>
        <td>: <?= date('d M Y', strtotime($slip->payslip_print_date)) ?></td>
    </tr>
</table>

<br>

<table class="salary">
    <tr>
        <th>Description</th>
        <th class="text-right">Amount</th>
    </tr>

    <?php 
    $totalEarning   = 0;
    $totalDeduction = 0;
    foreach ($details as $row): 
    ?>
    <tr>
        <td><?= htmlspecialchars($row->component_name) ?></td>
        <td class="text-right"><?= number_format($row->amount, 2) ?></td>
    </tr>

    <?php 
        if ($row->component_type === 'earning') {
            $totalEarning += $row->amount;
        } else {
            $totalDeduction += $row->amount;
        }
    endforeach; 
    ?>

    <tr>
        <td><strong>Total Earnings</strong></td>
        <td class="text-right"><strong><?= number_format($totalEarning, 2) ?></strong></td>
    </tr>
    <tr>
        <td><strong>Total Deductions</strong></td>
        <td class="text-right"><strong><?= number_format($totalDeduction, 2) ?></strong></td>
    </tr>
    <tr>
        <td><strong>Net Pay</strong></td>
        <td class="text-right">
            <strong><?= number_format($slip->take_home_pay, 2) ?></strong>
        </td>
    </tr>
</table>

<div class="footer">
    This payslip is generated automatically by the system.
</div>

</body>
</html>
