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
                            if ($this->module_name != 'absensi_menu') {
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
