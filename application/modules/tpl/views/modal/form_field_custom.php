<style>
    @media (max-width: 768px) {

        /*.modal-dialog {
            width: 25% !important;
            margin-left: 308px !important;
        }*/

        .modal-content {
            max-height: 80vh;
            overflow-y: auto;
            /*width: fit-content !important;*/
            width: 90% !important;
        }

        
        .vertical-alignment-helper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vertical-align-center {
            width: 100%;
        }



    }


    @media (min-width: 320px) and (max-width: 400px) {


        .modal-dialog {
            width: 100% !important;
            margin-left: auto !important;
        }

        .modal-content {
            max-height: 80vh;
            overflow-y: auto;
            width: 80% !important;
        }

    }

    .modal-body {
        overflow-y: auto !important;
        max-height: 70vh !important;
    }



    .modal-header {
        background-color: #343851 !important;
        color: #fff !important;
    }



    /* scroll horizontal aman di modal */
    .table-scroll-x {
        width: 100% !important;
        overflow-x: auto !important;
        overflow-y: visible !important;
        padding-bottom: 10px !important; /* biar scrollbar gak nutup tabel */
    }

    .table-scroll-x table {
        min-width: 2000px !important; /* paksa tabel panjang */
    }

    /* pastikan modal gak motong isi */
    .modal-body {
        overflow-x: visible !important;
    }

    /* hilangkan overflow hidden bawaan bootstrap */
    .modal-content {
        overflow: visible !important;
    }

    .table-scroll-x thead th {
        position: sticky !important;
        top: 0 !important;
        background: #fff !important;
        z-index: 10 !important;
    }

    .modal-dialog.modal-full {
        width: 98vw !important;
        max-width: 98vw !important;
        margin: 10px auto;
    }

    .modal-content {
        width: 80% !important;
    }



    /* ============================= */
    /* SET WIDTH KOLOM (WAJIB) */
    /* ============================= */
    #tblDetailAbsenOSEditGaji th:nth-child(1),
    #tblDetailAbsenOSEditGaji td:nth-child(1) {
        width: 150px;
        min-width: 150px;
    }

    #tblDetailAbsenOSEditGaji th:nth-child(2),
    #tblDetailAbsenOSEditGaji td:nth-child(2) {
        width: 200px;
        min-width: 200px;
    }

    /* ============================= */
    /* FREEZE BODY */
    /* ============================= */
    #tblDetailAbsenOSEditGaji td:nth-child(1) {
        position: sticky !important;
        left: 0 !important;
        background: #fff;
        z-index: 20;
    }

    #tblDetailAbsenOSEditGaji td:nth-child(2) {
        position: sticky !important;
        left: 150px !important;
        background: #fff;
        z-index: 19;
    }

    /* ============================= */
    /* FREEZE TITLE (HEADER) */
    /* ============================= */
    #tblDetailAbsenOSEditGaji thead th:nth-child(1) {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        background: #f0f0f0;
        z-index: 50 !important;
    }

    #tblDetailAbsenOSEditGaji thead th:nth-child(2) {
        position: sticky !important;
        top: 0 !important;
        left: 150px !important;
        background: #f0f0f0;
        z-index: 49 !important;
    }


    #tblDetailAbsenOSEditGaji {
        table-layout: fixed !important;
        width: 100% !important;
    }


    #tblDetailAbsenOSEditGaji th,
    #tblDetailAbsenOSEditGaji td {
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






</style>



<!-- Modal Form Data -->
<div id="modal-form-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form-data"
    aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form class="form-horizontal" id="frmInputData" enctype="multipart/form-data">
                    <div class="modal-header no-padding">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <div class="table-header">
                            <span id="mfdata"></span> <!-- <?php echo $smodul; ?>  -->
                            <?php
                            if ($this->module_name != 'absensi_menu' && $this->module_name != 'absensi_os_menu') {
                                echo $smodul;
                            }
                            if ($this->module_name == 'ijin_menu' || $this->module_name == 'fpu_menu' || $this->module_name == 'fpp_menu' || $this->module_name == 'settlement_menu' || $this->module_name == 'reimbursement_menu' || $this->module_name == 'lembur_menu' || $this->module_name == 'perjalanan_dinas_menu' || $this->module_name == 'training_menu' || $this->module_name == 'loan' || $this->module_name == 'hr_employee_loans' || $this->module_name == 'request_recruitment_menu') {
                                ?>
                                <button type="button" style="display:none" id="btnApprovalLog" class="btn-sm btn-circle btn-approvalLog" onclick="approvalLog()">
                                    <i class="fa fa-history"></i> Approval Log
                                </button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" value="">
                        <?php $this->load->view("_field"); ?>
                    </div>
                </form>

                <!-- <div class="modal-footer no-margin-top" id="mdlFooter">
                                    <span class="act-container-btn">
                                        <?php
                                        if ($this->module_name == 'request_recruitment_menu') {
                                            ?>
                                            <a class="btn btn-warning" id="btnDraft" onclick="save('draft')">
                                                Save as Draft
                                            </a> 

                                            <button class="btn btn-info" id="submit-data" onclick="save('waiting_approval')">
                                                <i class="fa fa-check"></i>
                                                Submit
                                            </button>
                                            <?php
                                        } else {
                                            ?>
                                            <button class="btn btn-info" id="submit-data" onclick="save()">
                                                <i class="fa fa-check"></i>
                                                Save
                                            </button>
                                            <?php
                                        }
                                        ?> 
                                        
                                        <button class="btn" onclick="reset()" id="btnReset">
                                            <i class="fa fa-undo"></i>
                                            Reset
                                        </button>
                                    </span>
                                    <button class="btn" data-dismiss="modal">
                                        <i class="fa fa-times"></i>
                                        Close
                                    </button>
                                </div> -->

                <div class="modal-footer d-flex justify-content-between flex-wrap no-margin-top" id="mdlFooter">
                    <span class="act-container-btn d-flex flex-wrap gap-2">
                        <?php if ($this->module_name == 'request_recruitment_menu'): ?>
                            <a class="btn btn-warning" id="btnDraft" onclick="save('draft')">Save as Draft</a>
                            <button class="btn btn-info" id="submit-data" onclick="save('waiting_approval')">
                                <i class="fa fa-check"></i> Submit
                            </button>
                        <?php else: ?>
                            <button class="btn btn-info"
                                style="background-color: #343851; color: white; border-radius: 4px !important; margin-right: 5px;"
                                id="submit-data" onclick="save()">
                                <i class="fa fa-check"></i> Save
                            </button>
                        <?php endif; ?>
                    </span>

                    <!-- Container untuk tombol tetap di kanan -->
                    <span class="d-flex gap-2 ms-auto">
                        <button class="btn"
                            style="background-color: #FED24B; color: #343851; border-radius: 4px !important;"
                            onclick="reset()" id="btnReset">
                            <i class="fa fa-undo"></i> Reset
                        </button>
                        <button class="btn"
                            style="background-color: #A01818; color: white; border-radius: 4px !important;"
                            data-dismiss="modal">
                            <i class="fa fa-times"></i> Close
                        </button>
                    </span>
                </div>



            </div>
        </div>
    </div>
</div>




<!-- Modal Form Data -->
<div id="modal-form-editperproject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form-data" aria-hidden="true">
    <div class="vertical-alignment-helper">
    <div class="modal-dialog modal-full vertical-align-center">
        <div class="modal-content">
            <form class="form-horizontal" id="frmInputDataEditProject" enctype="multipart/form-data">
            <div class="modal-header bg-blue bg-font-blue no-padding">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <div class="table-header">
                    <span id="mfdata"></span> Edit Perhitungan Absen per Project
                </div>
            </div>

            <div class="modal-body" style="min-height:100px; margin:10px">
                <input type="hidden" name="id" value="">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label no-padding-right">Project</label>
                            <div class="col-md-4">
                                <?=$selproject_edit;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">Penggajian Bulan</label>
                            <div class="col-md-8">
                                <?=$selmonth_edit;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">Periode Absen Start</label>
                            <div class="col-md-8">
                                <?=$txtperiodstart_edit;?>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">Tahun</label>
                            <div class="col-md-8">
                                <?=$txtyear_edit;?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">End </label>
                            <div class="col-md-8">
                                <?=$txtperiodend_edit;?>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>




                <div class="row absenos_edit" id="inpAbsenOS_edit" style="display:none;">
                    <div class="col-md-12">
                        <div class="portlet box">
                            <div class="portlet-title">
                                <div class="caption">Details </div>
                                <div class="tools">
                                   
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable tablesaw-cont">
                                <table class="table table-striped table-bordered table-hover absenos_edit-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailAbsenOSEdit">
                                    <thead>
                                        <tr>
                                            <th scope="col">NIK</th>
                                            <th scope="col">Karyawan</th>
                                            <th scope="col">Total Hari Kerja</th>
                                            <th scope="col">Total Masuk</th>
                                            <th scope="col">Total Ijin</th>
                                            <th scope="col">Total Cuti</th>
                                            <th scope="col">Total Alfa</th>
                                            <th scope="col">Total Lembur</th>
                                            <th scope="col">Total Jam Kerja</th>
                                            <th scope="col">Total Jam Lembur</th>
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
            </form>

            <div class="modal-footer no-margin-top">
                <span class="act-container-btn">
                    <button class="btn btn-info" id="submit-data" onclick="edit_per_project()">
                        <i class="fa fa-check"></i>
                        Update
                    </button>
                    <button class="btn" onclick="reset()">
                        <i class="fa fa-undo"></i>
                        Reset
                    </button>
                </span>
                <button class="btn red" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
    </div>
</div>




<div id="modal-form-editgajiperproject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form-data" aria-hidden="true">
    <div class="vertical-alignment-helper">
    <div class="modal-dialog modal-full vertical-align-center">
        <div class="modal-content">
            <form class="form-horizontal" id="frmInputDataEditGajiProject" enctype="multipart/form-data">
            <div class="modal-header bg-blue bg-font-blue no-padding">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <div class="table-header">
                    <span id="mfdata"></span> Edit Gaji per Project
                </div>
            </div>

            <div class="modal-body" style="min-height:100px; margin:10px">
                <input type="hidden" name="id" value="">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label no-padding-right">Project</label>
                            <div class="col-md-4">
                                <?=$selproject_edit_gaji;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">Penggajian Bulan</label>
                            <div class="col-md-8">
                                <?=$selmonth_edit_gaji;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">Periode Absen Start</label>
                            <div class="col-md-8">
                                <?=$txtperiodstart_edit_gaji;?>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">Tahun</label>
                            <div class="col-md-8">
                                <?=$txtyear_edit_gaji;?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label no-padding-right">End </label>
                            <div class="col-md-8">
                                <?=$txtperiodend_edit_gaji;?>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>




                <div class="row absenos_edit_gaji" id="inpAbsenOS_edit_gaji" style="display:none;">
                    <div class="col-md-12">
                        <div class="portlet box">
                            <div class="portlet-title">
                                <div class="caption">Details </div>
                                <div class="tools">
                                   
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scroll-x">
                                    <div class="table-scrollable tablesaw-cont">
                                        <table class="table table-striped table-bordered table-hover absenos_edit_gaji-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailAbsenOSEditGaji">
                                            <thead>
                                                <tr>
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
                                                    <th scope="col">Absen</th>
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


                                
            </div>
            </form>

            <div class="modal-footer no-margin-top">
                <span class="act-container-btn">
                    <button class="btn btn-info" id="submit-data" onclick="edit_gaji_per_project()">
                        <i class="fa fa-check"></i>
                        Update
                    </button>
                    <button class="btn" onclick="reset()">
                        <i class="fa fa-undo"></i>
                        Reset
                    </button>
                </span>
                <button class="btn red" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
    </div>
</div>
