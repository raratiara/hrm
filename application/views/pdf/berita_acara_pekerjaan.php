<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara Pekerjaan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
        }

        .company-info {
            font-size: 10px;
        }

        .line {
            border-top: 2px solid #000;
            margin: 6px 0 10px 0;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 15px;
        }

        .content {
            margin-top: 10px;
            line-height: 1.6;
        }

        .content table {
            width: 100%;
        }

        .content td {
            vertical-align: top;
            padding: 2px 0;
        }

        .signature {
            width: 100%;
            margin-top: 30px;
        }

        .signature td {
            width: 50%;
            /*text-align: center;*/
        }

        .sign-name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }

        .sign-title {
            font-size: 10px;
        }
    </style>
</head>
<body>

<!-- ================= HEADER ================= -->
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
            <div style="font-size:14px; font-weight:bold; line-height:1.3; padding-left:120px;">
                PT. MANDIRI AGANGTA SEJAHTERA
            </div>
            <div style="font-size:10px; line-height:1.3; padding-left:80px;">
                Jl. Kemang Raya No. 1A - RT.01/RW.09, Sukmajaya - Depok, Jawa Barat
            </div>
            <div style="font-size:10px; line-height:1.3; padding-left:130px;">
                Telp : 021-77844672 &nbsp;&nbsp; Email : info@mandirias.co.id
            </div>
        </td>
    </tr>
</table>

<div class="line"></div>

<!-- ================= TITLE ================= -->
<div class="title"><u>BERITA ACARA PEKERJAAN</u></div>
<div class="subtitle">No : <?= $no_surat ?></div>

<!-- ================= CONTENT ================= -->
<div class="content">
    Kami yang bertanda tangan di bawah ini menerangkan bahwa <b>PT. Mandiri Agangta Sejahtera</b>
    telah selesai melaksanakan pekerjaan dengan rincian sebagai berikut :
    <br><br>

    <table>
        <tr>
            <td width="3%">1.</td>
            <td width="30%">Nama Perusahaan</td>
            <td width="2%">:</td>
            <td><?= $nama_perusahaan ?></td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Alamat</td>
            <td>:</td>
            <td><?= $alamat ?></td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Periode Pekerjaan</td>
            <td>:</td>
            <td><?= $periode ?></td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Lokasi Pekerjaan</td>
            <td>:</td>
            <td><?= $lokasi ?></td>
        </tr>
        <tr>
            <td>5.</td>
            <td>Jenis Pekerjaan</td>
            <td>:</td>
            <td><?= $jenis_pekerjaan ?></td>
        </tr>
        <tr>
            <td>6.</td>
            <td>Jumlah Personil</td>
            <td>:</td>
            <td><?= $jumlah_personil ?> Personil</td>
        </tr>
    </table>

    <br>
    Demikian berita acara ini kami buat dengan sebenarnya untuk dipergunakan
    sebagaimana mestinya.
    <!-- <br><br>

    Depok, <?= $tanggal ?> -->
</div>

<!-- ================= SIGNATURE ================= -->
<table class="signature">
    <tr><td style="text-align: left;">Depok, <?= $tanggal ?></td></tr>
    <tr>
        <td style="align: left;">
            PT. Mandiri Agangta Sejahtera <br><br><br><br>
            <div class="sign-name"><?= $nama_ttd_kiri ?></div>
            <div class="sign-title"><?= $jabatan_ttd_kiri ?></div>
        </td>
        <td>
            <?= $nama_client ?> <br><br><br><br>
            <div class="sign-name"><?= $nama_ttd_kanan ?></div>
            <div class="sign-title"><?= $jabatan_ttd_kanan ?></div>
        </td>
    </tr>
</table>

</body>
</html>
