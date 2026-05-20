<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: A4 portrait;
            margin: 7mm 8mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7.2px;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .sheet {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td, th {
            padding: 2px 3px;
            vertical-align: middle;
            line-height: 1.12;
        }

        .bordered td,
        .bordered th {
            border: 1px solid #000;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .small { font-size: 5.6px; }
        .tiny { font-size: 4.8px; color: #1aa0e8; font-weight: normal; }
        .section-title { font-size: 7.1px; font-weight: bold; margin: 3px 0 1px; }
        .grey { background: #bfbfbf; }
        .input-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-height: 8px;
            vertical-align: baseline;
        }
        .line-wide { width: 170px; }
        .line-med { width: 110px; }
        .line-short { width: 52px; }
        .plain-value {
            display: inline-block;
            min-height: 8px;
            vertical-align: baseline;
        }
        .identity-line {
            border-bottom: 1px solid #000;
            display: block;
            min-height: 9px;
            padding-left: 2px;
        }
        .identity-text {
            font-weight: bold;
        }
        .identity-address {
            line-height: 1.08;
            min-height: 17px;
        }
        .identity-table {
            table-layout: auto;
        }
        .identity-label {
            width: 72px;
        }
        .npwp-group {
            display: inline-block;
            border-bottom: 1px solid #000;
            text-align: center;
            min-height: 8px;
            vertical-align: baseline;
            padding: 0 2px;
        }
        .npwp-2 { width: 17px; }
        .npwp-3 { width: 24px; }
        .npwp-1 { width: 12px; }
        .box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            vertical-align: middle;
            text-align: center;
            line-height: 11px;
            font-size: 9px;
            margin: 0 3px;
        }
        .mini-box {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            vertical-align: middle;
            text-align: center;
            line-height: 9px;
            font-size: 7px;
        }
        .tax-code-box {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 1px solid #000;
            vertical-align: middle;
            text-align: center;
            line-height: 12px;
            font-size: 9px;
            margin-right: 4px;
        }
        .corner-boxes {
            float: right;
            margin-top: -2px;
            white-space: nowrap;
        }
        .black-box {
            display: inline-block;
            width: 9px;
            height: 9px;
            background: #222;
            margin-left: 1px;
        }
        .tax-logo {
            width: 42px;
            height: auto;
            margin: 1px 0 4px;
        }
        .institution {
            font-size: 6.4px;
            font-weight: bold;
            line-height: 1.1;
        }
        .header-title {
            font-size: 8.3px;
            font-weight: bold;
            line-height: 1.13;
        }
        .form-title {
            font-size: 11px;
            font-weight: bold;
            line-height: 1.1;
        }
        .header-note {
            font-size: 5.1px;
            line-height: 1.1;
            margin-top: 4px;
        }
        .form-code {
            width: 18px;
            text-align: center;
            font-weight: bold;
        }
        .amount {
            width: 118px;
            text-align: right;
        }
        .num {
            width: 18px;
            text-align: center;
        }
        .sign-box {
            height: 72px;
            border: 1px solid #000;
        }
        .signature-date-area {
            padding-top: 34px;
        }
        .mb2 { margin-bottom: 2px; }
        .mt2 { margin-top: 2px; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
<?php
if (!function_exists('spt_rupiah')) {
    function spt_rupiah($angka) {
        if ($angka === null || $angka === '' || !is_numeric($angka)) {
            return '';
        }
        return number_format((float)$angka, 0, ',', '.');
    }
}

if (!function_exists('spt_dash_npwp')) {
    function spt_dash_npwp($value) {
        return $value ?: '';
    }
}

if (!function_exists('spt_npwp_segments')) {
    function spt_npwp_segments($value) {
        $digits = preg_replace('/\D+/', '', (string)$value);
        return [
            substr($digits, 0, 2),
            substr($digits, 2, 3),
            substr($digits, 5, 3),
            substr($digits, 8, 1),
            substr($digits, 9, 3),
            substr($digits, 12, 3),
        ];
    }
}

if (!function_exists('spt_npwp_html')) {
    function spt_npwp_html($value) {
        $s = spt_npwp_segments($value);
        return '<span class="npwp-group npwp-2">'.$s[0].'</span>.'.
            '<span class="npwp-group npwp-3">'.$s[1].'</span>.'.
            '<span class="npwp-group npwp-3">'.$s[2].'</span>.'.
            '<span class="npwp-group npwp-1">'.$s[3].'</span>-'.
            '<span class="npwp-group npwp-3">'.$s[4].'</span>.'.
            '<span class="npwp-group npwp-3">'.$s[5].'</span>';
    }
}

if (!function_exists('spt_ptkp_parts')) {
    function spt_ptkp_parts($status) {
        $status = strtoupper(trim((string)$status));
        $parts = ['K' => '', 'TK' => '', 'HB' => ''];
        if (preg_match('/\b(TK|K|HB)\s*\/\s*([0-9]+)/', $status, $m)) {
            $parts[$m[1]] = $m[2];
        }
        return $parts;
    }
}

$row = $data[0];
$taxLogoPath = FCPATH.'public/assets/img/logo_kemenkeu.png';
$taxLogoSrc = file_exists($taxLogoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($taxLogoPath)) : '';
$periodeStart = !empty($row->periode_start) ? (int) substr($row->periode_start, -2) : '';
$periodeEnd = !empty($row->periode_end) ? (int) substr($row->periode_end, -2) : '';
$sptNo = '1.1-12.'.$row->tahun.'-'.str_pad($row->id, 7, '0', STR_PAD_LEFT);
$totalBonusThr = (float)($row->total_bonus ?? 0) + (float)($row->total_thr ?? 0);
$totalTunjanganLainnya = (float)($row->total_tunjangan ?? 0) + (float)($row->total_lembur ?? 0);
$jumlahPengurangan = (float)($row->biaya_jabatan ?? 0) + (float)($row->iuran ?? 0);
$ptkpParts = spt_ptkp_parts($row->status_marital_name ?? '');
$gender = strtoupper((string)($row->gender_name ?? ''));
$isMale = strpos($gender, 'LAKI') !== false;
$isFemale = strpos($gender, 'PEREMPUAN') !== false;
$isForeign = strtolower((string)($row->is_karyawan_asing ?? '')) === 'yes';
$kodeObjekPajak = ((int)($row->employment_status_id ?? 0) === 3) ? '21-100-01' : '21-100-03';
?>

<div class="sheet">
    <table class="bordered">
        <tr>
            <td rowspan="2" style="width: 17%;" class="center">
                <?php if ($taxLogoSrc != ''): ?>
                    <img class="tax-logo" src="<?=$taxLogoSrc?>" alt="Kementerian Keuangan">
                <?php endif; ?>
                <div class="institution">KEMENTERIAN KEUANGAN RI</div>
                <div class="institution">DIREKTORAT JENDERAL PAJAK</div>
            </td>
            <td style="width: 50%;" class="center">
                <div class="header-title">BUKTI PEMOTONGAN PAJAK PENGHASILAN<br>PASAL 21 BAGI PEGAWAI TETAP ATAU<br>PENERIMA PENSIUN ATAU TUNJANGAN HARI<br>TUA/JAMINAN HARI TUA BERKALA</div>
            </td>
            <td style="width: 33%;" class="center">
                <span class="corner-boxes"><span class="black-box"></span><span class="black-box"></span><span class="black-box"></span></span>
                <div class="form-title">FORMULIR 1721 - A1</div>
                <div class="header-note">Lembar ke-1 : untuk Penerima Penghasilan</div>
            </td>
        </tr>
        <tr>
            <td class="bold">
                NOMOR : <span class="tiny">H.01</span>
                <span class="input-line line-short center">1</span> .
                <span class="input-line line-short center">1</span> -
                <span class="input-line line-short center">12</span> .
                <span class="input-line line-short center"><?=$row->tahun?></span> -
                <span class="input-line line-med center"><?=str_pad($row->id, 7, '0', STR_PAD_LEFT)?></span>
            </td>
            <td class="center bold">
                MASA PEROLEHAN<br>PENGHASILAN <span class="small">[mm - mm]</span><br>
                <span class="tiny">H.02</span>
                <span class="input-line line-short center"><?=$periodeStart?></span> -
                <span class="input-line line-short center"><?=$periodeEnd?></span>
            </td>
        </tr>
    </table>

    <table class="bordered mb2">
        <tr>
            <td style="width: 20%;" class="bold">NPWP<br>PEMOTONG <span class="tiny">H.03</span></td>
            <td><?=spt_npwp_html($row->company_npwp)?></td>
        </tr>
        <tr>
            <td class="bold">NAMA<br>PEMOTONG <span class="tiny">H.04</span></td>
            <td><span class="input-line" style="width: 96%;"><?=$row->company_name?></span></td>
        </tr>
    </table>

    <div class="section-title">A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG</div>
    <table class="bordered mb2">
        <tr>
            <td style="width: 50%;">
                <table class="no-border identity-table">
                    <tr>
                        <td class="bold identity-label">1. NPWP <span class="tiny">A.01</span></td>
                        <td><span class="identity-line"><?=spt_npwp_html($row->no_npwp)?></span></td>
                    </tr>
                    <tr>
                        <td class="bold identity-label">2. NIK / NO.<br>&nbsp;&nbsp;&nbsp;PASPOR <span class="tiny">A.02</span></td>
                        <td><span class="identity-line identity-text"><?=$row->no_ktp?></span></td>
                    </tr>
                    <tr>
                        <td class="bold identity-label">3. NAMA <span class="tiny">A.03</span></td>
                        <td><span class="identity-line identity-text"><?=$row->full_name?></span></td>
                    </tr>
                    <tr>
                        <td class="bold identity-label">4. ALAMAT <span class="tiny">A.04</span></td>
                        <td><span class="identity-line identity-address identity-text"><?=$row->address_ktp?></span></td>
                    </tr>
                </table>
                <div class="bold">5. JENIS KELAMIN : <span class="tiny">A.05</span> <span class="box"><?=$isMale ? 'X' : ''?></span> LAKI-LAKI <span class="tiny">A.06</span> <span class="box"><?=$isFemale ? 'X' : ''?></span> PEREMPUAN</div>
            </td>
            <td style="width: 50%;">
                <div class="mb2 bold">6. STATUS / JUMLAH TANGGUNGAN KELUARGA UNTUK PTKP</div>
                <div class="mb2 center bold">
                    K / <span class="input-line line-short center"><?=$ptkpParts['K']?></span> <span class="tiny">A.07</span>
                    &nbsp;&nbsp; TK / <span class="input-line line-short center"><?=$ptkpParts['TK']?></span> <span class="tiny">A.08</span>
                    &nbsp;&nbsp; HB / <span class="input-line line-short center"><?=$ptkpParts['HB']?></span> <span class="tiny">A.09</span>
                </div>
                <div class="mb2 bold">7. NAMA JABATAN : <span class="tiny">A.10</span> <span class="input-line" style="width: 72%;"><?=$row->job_title_name?></span></div>
                <div class="mb2 bold">8. KARYAWAN ASING : <span class="tiny">A.11</span> <span class="box"><?=$isForeign ? 'X' : ''?></span> YA</div>
                <div class="bold">9. KODE NEGARA DOMISILI : <span class="tiny">A.12</span> <span class="input-line line-short"></span></div>
            </td>
        </tr>
    </table>

    <div class="section-title">B. RINCIAN PENGHASILAN DAN PENGHITUNGAN PPh PASAL 21</div>
    <table class="bordered">
        <tr class="bold center">
            <td colspan="11">URAIAN</td>
            <td class="amount">JUMLAH (Rp)</td>
        </tr>
        <tr class="bold center">
            <td colspan="10" style="text-align: left;">
                KODE OBJEK PAJAK:
                &nbsp;&nbsp;
                <span class="tax-code-box"><?=$kodeObjekPajak === '21-100-01' ? 'X' : ''?></span> 21-100-01
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="tax-code-box"><?=$kodeObjekPajak === '21-100-03' ? 'X' : ''?></span> 21-100-03
            </td>
            <td></td>
            <td class="grey"></td>
        </tr>
        <tr class="bold">
            <td colspan="11">PENGHASILAN BRUTO:</td>
            <td class="grey"></td>
        </tr>
        <tr><td class="num">1.</td><td colspan="10">GAJI/PENSIUN ATAU THT/JHT</td><td class="right"><?=spt_rupiah($row->total_gaji)?></td></tr>
        <tr><td class="num">2.</td><td colspan="10">TUNJANGAN PPh</td><td class="right">0</td></tr>
        <tr><td class="num">3.</td><td colspan="10">TUNJANGAN LAINNYA, UANG LEMBUR DAN SEBAGAINYA</td><td class="right"><?=spt_rupiah($totalTunjanganLainnya)?></td></tr>
        <tr><td class="num">4.</td><td colspan="10">HONORARIUM DAN IMBALAN LAIN SEJENISNYA</td><td class="right">0</td></tr>
        <tr><td class="num">5.</td><td colspan="10">PREMI ASURANSI YANG DIBAYAR PEMBERI KERJA</td><td class="right">0</td></tr>
        <tr><td class="num">6.</td><td colspan="10">PENERIMAAN DALAM BENTUK NATURA DAN KENIKMATAN LAINNYA YANG DIKENAKAN PEMOTONGAN PPh PASAL 21</td><td class="right">0</td></tr>
        <tr><td class="num">7.</td><td colspan="10">TANTIEM, BONUS, GRATIFIKASI, JASA PRODUKSI DAN THR</td><td class="right"><?=spt_rupiah($totalBonusThr)?></td></tr>
        <tr class="bold"><td class="num">8.</td><td colspan="10">JUMLAH PENGHASILAN BRUTO (1 S.D.7)</td><td class="right"><?=spt_rupiah($row->bruto_tahunan)?></td></tr>
        <tr class="bold"><td colspan="11">PENGURANGAN:</td><td class="grey"></td></tr>
        <tr><td class="num">9.</td><td colspan="10">BIAYA JABATAN/BIAYA PENSIUN</td><td class="right"><?=spt_rupiah($row->biaya_jabatan)?></td></tr>
        <tr><td class="num">10.</td><td colspan="10">IURAN PENSIUN ATAU IURAN THT/JHT</td><td class="right"><?=spt_rupiah($row->iuran)?></td></tr>
        <tr class="bold"><td class="num">11.</td><td colspan="10">JUMLAH PENGURANGAN (9 S.D.10)</td><td class="right"><?=spt_rupiah($jumlahPengurangan)?></td></tr>
        <tr class="bold"><td colspan="11">PENGHITUNGAN PPh PASAL 21:</td><td class="grey"></td></tr>
        <tr><td class="num">12.</td><td colspan="10">JUMLAH PENGHASILAN NETO (8 - 11)</td><td class="right"><?=spt_rupiah($row->neto_tahunan)?></td></tr>
        <tr><td class="num">13.</td><td colspan="10">PENGHASILAN NETO MASA SEBELUMNYA</td><td class="right">0</td></tr>
        <tr><td class="num">14.</td><td colspan="10">JUMLAH PENGHASILAN NETO UNTUK PENGHITUNGAN PPh PASAL 21 (SETAHUN/DISETAHUNKAN)</td><td class="right"><?=spt_rupiah($row->neto_tahunan)?></td></tr>
        <tr><td class="num">15.</td><td colspan="10">PENGHASILAN TIDAK KENA PAJAK (PTKP)</td><td class="right"><?=spt_rupiah($row->ptkp)?></td></tr>
        <tr><td class="num">16.</td><td colspan="10">PENGHASILAN KENA PAJAK SETAHUN/DISETAHUNKAN (14 - 15)</td><td class="right"><?=spt_rupiah($row->pkp)?></td></tr>
        <tr><td class="num">17.</td><td colspan="10">PPh PASAL 21 ATAS PENGHASILAN KENA PAJAK SETAHUN/DISETAHUNKAN</td><td class="right"><?=spt_rupiah($row->pph21_tahunan)?></td></tr>
        <tr><td class="num">18.</td><td colspan="10">PPh PASAL 21 YANG TELAH DIPOTONG MASA SEBELUMNYA</td><td class="right">0</td></tr>
        <tr><td class="num">19.</td><td colspan="10">PPh PASAL 21 TERUTANG</td><td class="right"><?=spt_rupiah($row->pph21_tahunan)?></td></tr>
        <tr><td class="num">20.</td><td colspan="10">PPh PASAL 21 DAN PPh PASAL 26 YANG TELAH DIPOTONG DAN DILUNASI</td><td class="right"><?=spt_rupiah($row->pph21_ter_total)?></td></tr>
    </table>

    <div class="section-title">C. IDENTITAS PEMOTONG</div>
    <table class="bordered">
        <tr>
            <td style="width: 57%;">
                <div class="mb2 bold">1. NPWP : <span class="tiny">C.01</span> <span class="plain-value"><?=spt_dash_npwp($row->company_npwp)?></span></div>
                <div class="bold">2. NAMA : <span class="tiny">C.02</span> <span class="plain-value"><?=$row->company_name?></span></div>
            </td>
            <td style="width: 27%;" class="center bold">
                3. TANGGAL &amp; TANDA TANGAN<br><br>
                <div class="signature-date-area">
                    <span class="input-line line-short"></span> -
                    <span class="input-line line-short"></span> -
                    <span class="input-line line-short"></span><br>
                    <span class="small">[dd - mm - yyyy]</span>
                </div>
            </td>
            <td style="width: 16%;"><div class="sign-box"></div></td>
        </tr>
    </table>
</div>
</body>
</html>
