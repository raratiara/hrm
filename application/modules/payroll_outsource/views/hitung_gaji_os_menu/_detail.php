

<style type="text/css">
    #tblDetailGajiOSView {
        min-width: 2000px;
    }

    .table-scroll-x {
        padding-bottom: 25px !important;  /* jarak ke scrollbar */
    }

    .table-scrollable {
        margin-bottom: 15px !important;
    }


    /* ============================= */
    /* SET WIDTH KOLOM (WAJIB) */
    /* ============================= */
    #tblDetailGajiOSView th:nth-child(1),
    #tblDetailGajiOSView td:nth-child(1) {
        width: 50px;
        min-width: 50px;
    }

    #tblDetailGajiOSView th:nth-child(2),
    #tblDetailGajiOSView td:nth-child(2) {
        width: 150px;
        min-width: 150px;
    }

    #tblDetailGajiOSView th:nth-child(3),
    #tblDetailGajiOSView td:nth-child(3) {
        width: 200px;
        min-width: 200px;
    }

    /* ============================= */
    /* FREEZE BODY */
    /* ============================= */
    #tblDetailGajiOSView td:nth-child(1) {
        position: sticky !important;
        left: 0 !important;
        background: #fff;
        z-index: 20;
    }

    #tblDetailGajiOSView td:nth-child(2) {
        position: sticky !important;
        left: 0 !important;
        background: #fff;
        z-index: 19;
    }

    #tblDetailGajiOSView td:nth-child(3) {
        position: sticky !important;
        left: 200px !important;
        background: #fff;
        z-index: 18;
    }

    /* ============================= */
    /* FREEZE TITLE (HEADER) */
    /* ============================= */
    #tblDetailGajiOSView thead th:nth-child(1) {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        background: #f0f0f0;
        z-index: 50 !important;
    }

    #tblDetailGajiOSView thead th:nth-child(2) {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        background: #f0f0f0;
        z-index: 49 !important;
    }

    #tblDetailGajiOSView thead th:nth-child(3) {
        position: sticky !important;
        top: 0 !important;
        left: 200px !important;
        background: #f0f0f0;
        z-index: 48 !important;
    }


    #tblDetailGajiOSView {
        table-layout: fixed !important;
        width: 100% !important;
    }


    #tblDetailGajiOSView th,
    #tblDetailGajiOSView td {
        width: 150px !important;
        min-width: 150px !important;
        max-width: 150px !important;

        height: 30px !important;
        line-height: 30px !important;
        padding: 0 6px !important;

        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    /* JARAK ANTAR ROW (TR) */
    #tblDetailGajiOSView tbody tr {
        height: 42px !important;          /* jarak vertikal antar row */
    }


    #tblDetailGajiOSView td {
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        line-height: 1.4 !important;      /* jangan terlalu rapet */
    }


    .table-scroll-x {
        padding-bottom: 25px !important;  /* jarak ke scrollbar */
    }


    .table-scrollable {
        margin-bottom: 15px !important;
    }

</style>



<div class="row">
    
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="row-flex">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Penggajian Bulan</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="penggajian_month"></span>
            </div>
        </div>
        <div class="row-flex">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Period Start</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="period_start"></span>
            </div>
        </div>
        <div class="row-flex">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Project</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="project"></span>
            </div>
        </div>
    </div>



    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="row-flex">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Tahun</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="penggajian_year"></span>
            </div>
        </div>
        <div class="row-flex">
            <label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">End</label>
            <div class="col-md-8 col-sm-8 col-xs-8">
                : <span class="period_end"></span>
            </div>
        </div>
        
        
    </div>
</div>



<div class="row gajios_view" id="inpGajiOS_view">
    <div class="col-md-12">
        <div class="portlet box">
            <div class="portlet-title">
                <div class="caption"> </div>
                <div class="tools">
                   
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scroll-x">
                    <div class="table-scrollable tablesaw-cont">
                        <table class="table table-striped table-bordered table-hover gajios-view-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailGajiOSView">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 80px !important;"></th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Karyawan</th>
                                    <th scope="col">Jumlah Jam Kerja</th>
                                    <th scope="col">Jumlah Hadir</th>
                                    <th scope="col">Jumlah Tdk Hadir</th>
                                    <th scope="col">Gaji Bulanan</th>
                                    <th scope="col">Gaji Harian</th>
                                    <th scope="col">Gaji</th>
                                    <th scope="col">Tunj. Jabatan</th>
                                    <th scope="col">Tunj. Transport</th>
                                    <th scope="col">Tunj. Konsumsi</th>
                                    <th scope="col">Tunj. Komunikasi</th>
                                    <th scope="col">Lembur per jam</th>
                                    <th scope="col">OT</th>
                                    <th scope="col">Jam Lembur</th>
                                    <th scope="col">Total Pendapatan</th>
                                    <th scope="col">BPJS Kesehatan</th>
                                    <th scope="col">BPJS TK</th>
                                    
                                    <th scope="col">TP JKK</th>
                                    <th scope="col">TP JKM</th>
                                    <th scope="col">TP JHT</th>
                                    <th scope="col">TP JP</th>
                                    <th scope="col">PGK JHT</th>
                                    <th scope="col">PGK JP</th>
                                    <th scope="col">TP Jkes</th>
                                    <th scope="col">PGK Jkes</th>


                                    <!-- <th scope="col">Absen</th> -->
                                    <th scope="col">Seragam</th>
                                    <th scope="col">Pelatihan</th>
                                    <th scope="col">Lain-Lain</th>
                                    <th scope="col">Hutang</th>
                                    <th scope="col">Sosial</th>
                                    <th scope="col">Payroll</th>
                                    <th scope="col">PPH 120</th>
                                    <th scope="col">Sub Total</th>
                                    <th scope="col">Gaji Bersih</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>