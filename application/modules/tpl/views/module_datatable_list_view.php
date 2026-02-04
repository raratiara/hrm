<style>
    /* Pastikan tabel tidak pecah layout */


    .table-container {
        width: 100%;


    }

    .portlet-body {
        width: 100% !important;
    }

    .table-scroll-wrapper {
        width: 100% !important;

    }



    /* Responsif untuk mobile */
    @media screen and (max-width: 767px) {

        .pagination>li>a,
        .pagination>li>span {
            font-size: 10px !important;
            padding: 3px 6px !important;
        }

        .pagination {
            margin: 5px 0 !important;
        }




        #dynamic-table {

            /* Atau sesuai kebutuhan kolom */
            overflow-x: auto !important;
            white-space: nowrap !important;
            display: block !important;


        }


        #dynamic-table th,
        #dynamic-table td {
            display: table-cell !important;
            font-size: 12px !important;
            padding: 6px !important;
            white-space: nowrap !important;
            vertical-align: middle !important;
        }

    }

    #dynamic-table th,
    #dynamic-table td {
        display: table-cell !important;
        font-size: 12px !important;
        padding: 6px !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }


    #dynamic-table thead {
        display: table-header-group !important;
    }

    #dynamic-table tbody {
        display: table-row-group !important;
    }

    #dynamic-table tr {
        display: table-row !important;
    }

    .btn.btn-default {
        font-size: 12px !important;
        padding: 4px 6px !important;
    }

    .dataTables_filter {

        margin-left: -10px !important;
    }





    #dynamic-table th:nth-child(2),
    #dynamic-table td:nth-child(2) {
        min-width: none !important;
        max-width: none !important;
        white-space: nowrap !important;
        /* Agar konten tidak turun baris */
    }

    #dynamic-table th:nth-child(1),
    #dynamic-table td:nth-child(1) {
        min-width: 60px !important;
        max-width: none !important;
        white-space: nowrap !important;
        /* Agar konten tidak turun baris */
    }



    .box {
        background-color: #343851 !important;
        border: 1px solid #6B6B6B !important;
    }


    .portlet.box .portlet-title .caption i {
        color: #ffffff !important;
    }



    .btn.btn-default {
        border: none !important;
        background-color: #dde0f3ff;
    }

    .btn.btn-default:hover {
        background-color: #979797ff !important;
        /* warna hover lebih gelap sedikit */
        border-color: #979797ff !important;
        color: #000 !important;
    }



    .pagination>li.active>a {
        background-color: #343851 !important;
        border-color: #343851 !important;
        color: #fff !important;
    }

    .pagination>li>a,
    .pagination>li>span {
        color: #343851 !important;
        font-size: 12px !important;
    }
</style>


<h3 class="page-title"></h3>
<div>
    <div>
        <!-- BEGIN TABLE PORTLET-->
        <div class="portlet box">
            <div class="portlet-title">
                <div class="caption">
                    <?php if (isset($icon) && $icon <> "")
                        echo '<i class="fa ' . $icon . '"></i>'; ?><?php if (isset($title) && $title <> "")
                                  echo $title; ?>
                </div>
                <div class="actions">

                    <?php if (defined('_REPORT') && _REPORT == "1") { ?>
                        <a class="btn btn-default btn-sm btn-circle" id="btnReportData">
                            <i class="fa fa-file"></i>
                            Report
                        </a>
                    <?php } ?>
                    <?php if (_USER_ACCESS_LEVEL_EKSPORT == "1") {
                        if ($this->module_name == 'absence_report_menu') {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getReport()">
                                <i class="fa fa-download"></i>
                                Report
                            </a>
                            <?php
                        }
                        else if ($this->module_name == 'absence_report_os_menu') {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getReportOS_absen()">
                                <i class="fa fa-download"></i>
                                Report
                            </a>
                            <?php
                        }
                        else if ($this->module_name == 'hitung_summary_absen_os_menu') {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getReport_summ_absen_os()">
                                <i class="fa fa-download"></i>
                                Report
                            </a>
                            <?php
                        }
                        else if ($this->module_name == 'invoice_menu') {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getInvoice()">
                                <i class="fa fa-download"></i>
                                Invoice
                            </a>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getRincianBiaya()">
                                <i class="fa fa-download"></i>
                                Rincian Biaya
                            </a>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getBeritaAcaraPekerjaan()">
                                <i class="fa fa-download"></i>
                                Berita Acara Pekerjaan
                            </a>
                            <?php
                        }
                        else if ($this->module_name == 'hitung_gaji_os_menu') {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getReportGaji()">
                                <i class="fa fa-download"></i>
                                Report Gaji
                            </a>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getReportLembur()">
                                <i class="fa fa-download"></i>
                                Report Lembur
                            </a>
                            <a class="btn btn-default btn-sm btn-circle" onclick="getReportAbsenOS_gaji()">
                                <i class="fa fa-download"></i>
                                Report Absen
                            </a>
                          
                            <?php
                        }
                        else {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" id="btnEksportData">
                                <i class="fa fa-download"></i>
                                Eksport
                            </a>
                            <?php
                        }

                        if ($this->module_name == 'tidakabsenmasuk') {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" onclick="gosendWAReminder()">
                                <i class="fa fa-whatsapp"></i>
                                Send WA Reminder
                            </a>
                            <?php
                        }


                    } ?>
                    <?php if (_USER_ACCESS_LEVEL_IMPORT == "1") { ?>
                        <a class="btn btn-default btn-sm btn-circle" id="btnImportData">
                            <i class="fa fa-upload"></i>
                            Import
                        </a>
                    <?php } ?>
                    <?php if (_USER_ACCESS_LEVEL_ADD == "1") { ?>

                        <a class="btn btn-default btn-sm btn-circle" id="btnAddData">
                            <i class="fa fa-floppy-o"></i>
                            <?php
                            if ($this->module_name == 'absensi_menu' || $this->module_name == 'absensi_os_menu') {
                                ?>Check-IN<?php
                            }
                            else if ($this->module_name == 'hitung_summary_absen_os_menu') {
                                ?>Hitung Absen<?php
                            }
                            else if ($this->module_name == 'hitung_gaji_os_menu') {
                                ?>Hitung Gaji<?php
                            }
                            else {
                                ?>Add Data<?php
                            }
                            ?>

                        </a>
                    <?php } ?>

                    <?php if (_USER_ACCESS_LEVEL_UPDATE == "1") { ?>

                            <?php
                            if ($this->module_name == 'hitung_summary_absen_os_menu') {
                                ?>   
                                <a class="btn btn-default btn-sm btn-circle" id="btnEditPerProject">
                                    <i class="fa fa-pencil"></i>     
                                    Edit Perhitungan per Project
                                </a>
                                <?php
                            }
                            else if ($this->module_name == 'hitung_gaji_os_menu') {
                                ?>   
                                <a class="btn btn-default btn-sm btn-circle" id="btnEditGajiPerProject">
                                    <i class="fa fa-pencil"></i>     
                                    Edit Gaji per Project
                                </a>
                                <?php
                            }
                            ?>

                        
                    <?php } ?>

                    <?php if (_USER_ACCESS_LEVEL_DELETE == "1" && $this->module_name != "absensi_menu" && $this->module_name != "absensi_os_menu") { ?>

                        <a class="btn btn-default btn-sm btn-circle" id="btnBulkData">
                            <i class="fa fa-times"></i>
                            Delete Bulk
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="portlet-body">
                <form name="frmListData" id="frmListData">
                    <div class="table-container">
                        <div class="table-scroll-wrapper">
                            <table id="dynamic-table"
                                class="table table-striped table-bordered table-hover table-header-fixed"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <?php if (_USER_ACCESS_LEVEL_DELETE == "1" && $this->module_name != "absensi_menu" && $this->module_name != "absensi_os_menu") { ?>
                                            <!-- <th width="15px"><input type="checkbox" id="check-all"></th> -->
                                            <th><input type="checkbox" id="check-all"></th>
                                        <?php } ?>
                                        <?php
                                        if($this->module_name != 'summaryabsen' && $this->module_name != 'health_sync' && $this->module_name != 'health_hr' && $this->module_name != 'health_spo2' && $this->module_name != 'health_daily'){
                                            ?>
                                            <!-- <th width="120px">Action</th> -->
                                            <th style="min-width:120px !important;">Action</th>
                                            
                                            <?php
                                        }
                                        ?>
                                        
                                        <?php
                                        if (isset($thData) && $thData <> "") {
                                            foreach ($thData as $th) {
                                                if (!is_array($th)) {
                                                    echo '<th>' . $th . '</th>';
                                                } else {
                                                    echo '<th ' . $th[1] . '>' . $th[0] . '</th>';
                                                }
                                            }
                                        }
                                        ?>
                                    </tr>
                                </thead>

                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END TABLE PORTLET-->
    </div>
</div>