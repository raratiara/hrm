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
                        } else {
                            ?>
                            <a class="btn btn-default btn-sm btn-circle" id="btnEksportData">
                                <i class="fa fa-download"></i>
                                Eksport
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
                            if ($this->module_name == 'absensi_menu') {
                                ?>Check-IN<?php
                            } else {
                                ?>Add Data<?php
                            }
                            ?>

                        </a>
                    <?php } ?>
                    <?php if (_USER_ACCESS_LEVEL_DELETE == "1" && $this->module_name != "absensi_menu") { ?>

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
                                        <?php if (_USER_ACCESS_LEVEL_DELETE == "1" && $this->module_name != "absensi_menu") { ?>
                                            <!-- <th width="15px"><input type="checkbox" id="check-all"></th> -->
                                            <th><input type="checkbox" id="check-all"></th>
                                        <?php } ?>
                                        <?php
                                        if($this->module_name != 'summaryabsen' && $this->module_name != 'tidakabsenmasuk'){
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