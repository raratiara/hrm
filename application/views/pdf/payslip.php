<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>

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
            <img src="<?= base_url('public/assets/images/logo/gerbangdata.jpg') ?>" style="height:40px;">
            
        </td>
        <td width="50%" align="right" style="font-weight:bold;">
            <?= $slip->month_period_name ?> <?= $slip->year_period ?>
        </td>
    </tr>
</table>

<h2>PAY SLIP</h2>
<br>
<!-- ===== EMPLOYEE INFO ===== -->
<table class="info">
    <tr>
        <td width="20%">Employee Name</td>
        <td width="30%">: <?= $slip->full_name ?></td>
        <td width="20%">Position</td>
        <td width="30%">: <?= $slip->job_level_name ?></td>
    </tr>
    <tr>
        <td>NIK</td>
        <td>: <?= $slip->emp_code ?></td>
        <td>Work Location</td>
        <td>: <?= $slip->work_location_name ?></td>
    </tr>
    <tr>
        <td>Tax Marital / Ter / Tarif</td>
        <td>: <?= $slip->marital_status_name ?></td>
        <td>Grade</td>
        <td>: <?= $slip->grade_name ?></td>
    </tr>
    <tr>
        <td>NPWP</td>
        <td>: <?= $slip->no_npwp ?></td>
        <td></td>
        <td></td>
    </tr>
</table>

<hr>

<?php
$earnings = [];
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

<!-- ===== INCOME & DEDUCTION ===== -->
<table style="margin-top:12px;">
    <tr>
        <!-- ===== INCOME ===== -->
        <td width="47%" valign="top">
            <div class="section-title">Income</div>
            <table class="block">
                <?php $no=1; foreach ($earnings as $row): ?>
                <tr>
                    <td width="6%"><?= $no++ ?></td>
                    <td width="54%"><?= $row->component_name ?></td>
                    <td width="5%">:</td>
                    <td width="10%">IDR</td>
                    <td width="25%" class="amount"><?= number_format($row->amount,0) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </td>

        <td width="6%"></td>

        <!-- ===== DEDUCTION ===== -->
        <td width="47%" valign="top">
            <div class="section-title">Deduction</div>
            <table class="block">
                <?php $no=1; foreach ($deductions as $row): ?>
                <tr>
                    <td width="6%"><?= $no++ ?></td>
                    <td width="54%"><?= $row->component_name ?></td>
                    <td width="5%">:</td>
                    <td width="10%">IDR</td>
                    <td width="25%" class="amount"><?= number_format($row->amount,0) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </td>
    </tr>
</table>


<!-- ===== TOTAL ===== -->
<table class="summary-table section-gap">
    <tr>
        <!-- LEFT SUMMARY -->
        <td width="47%" valign="top">
            <div class="hr-total"></div>

            <table class="summary-table">
                <tr>
                    <!-- <td width="6%"></td> -->
                    <td width="54%"><strong>Total Income</strong></td>
                    <td width="5%">:</td>
                    <td width="10%">IDR</td>
                    <td width="25%" class="value"><?= number_format($totalEarning,0) ?></td>
                </tr>

                <tr>
                    <!-- <td width="6%"></td> -->
                    <td width="54%">Gross Income Taxable</td>
                    <td width="5%">:</td>
                    <td width="10%">IDR</td>
                    <td width="25%" class="value"><?= number_format($slip->gross_taxable ?? 0,0) ?></td>
                </tr>
            </table>

            <!-- JARAK -->
            <div class="mt-12"></div>

            <div class="small-text" style="font-size:9px; margin-top: 10px;"><u>Non THP (Paid by Company)</u></div>

            <table class="summary-table small-text mt-8">
                <tr>
                    <!-- <td width="6%"></td> -->
                    <td width="54%" style="font-size:9px">BPJS TK Company</td>
                    <td width="5%" style="font-size:9px">:</td>
                    <td width="10%" style="font-size:9px">IDR</td>
                    <td width="25%" class="value" style="font-size:9px"><?= number_format($slip->bpjs_tk_company ?? 0,0) ?></td>
                </tr>
                <tr>
                    <!-- <td></td> -->
                    <td style="font-size:9px">BPJS JP Company</td>
                    <td style="font-size:9px">:</td>
                    <td style="font-size:9px">IDR</td>
                    <td class="value" style="font-size:9px"><?= number_format($slip->bpjs_jp_company ?? 0,0) ?></td>
                </tr>
                <tr>
                    <!-- <td></td> -->
                    <td style="font-size:9px">BPJS KES Company</td>
                    <td style="font-size:9px">:</td>
                    <td style="font-size:9px">IDR</td>
                    <td class="value" style="font-size:9px"><?= number_format($slip->bpjs_kes_company ?? 0,0) ?></td>
                </tr>
            </table>
        </td>


        <td width="6%"></td>

        <!-- RIGHT SUMMARY -->
        <td width="47%" valign="top">
            <div class="hr-total"></div>

            <table class="summary-table">
                <tr>
                    <!-- <td width="6%"></td> -->
                    <td width="54%"><strong>Total Deduction</strong></td>
                    <td width="5%">:</td>
                    <td width="10%">IDR</td>
                    <td width="25%" class="value"><?= number_format($totalDeduction,0) ?></td>
                </tr>

                <tr>
                    <!-- <td></td> -->
                    <td><strong>Take Home Pay</strong></td>
                    <td>:</td>
                    <td>IDR</td>
                    <td class="value"><strong><?= number_format($slip->take_home_pay,0) ?></strong></td>
                </tr>
            </table>

            <div class="mt-12"></div>

            <table class="summary-table small-text" style="margin-top: 15px">
                <tr>
                    <!-- <td width="6%"></td> -->
                    <td width="54%;" style="font-size:9px">Bank</td>
                    <td width="5%" style="font-size:9px">:</td>
                    <td colspan="2" style="font-size:9px"><?= $slip->bank_name ?></td>
                </tr>
                <tr>
                    <!-- <td></td> -->
                    <td style="font-size:9px">Salary Account No</td>
                    <td style="font-size:9px">:</td>
                    <td colspan="2" style="font-size:9px"><?= $slip->bank_account ?></td>
                </tr>
            </table>
        </td>

    </tr>
</table>


<div class="footer">
    This payslip is generated automatically by the system.
</div>

</body>
</html>
