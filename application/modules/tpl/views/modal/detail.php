					<!-- Modal View Detail Data -->
					<div id="modal-view-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-view-data" aria-hidden="true">
						<div class="vertical-alignment-helper">
						<div class="modal-dialog vertical-align-center">
							<div class="modal-content">
								<div class="modal-header bg-blue bg-font-blue no-padding">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<div class="table-header">
										Detail <?php echo $smodul; ?>

										<?php
										if ($this->module_name == 'ijin_menu' || $this->module_name == 'fpu_menu' || $this->module_name == 'fpp_menu' || $this->module_name == 'settlement_menu' || $this->module_name == 'reimbursement_menu' || $this->module_name == 'lembur_menu' || $this->module_name == 'perjalanan_dinas_menu' || $this->module_name == 'training_menu' || $this->module_name == 'loan' || $this->module_name == 'hr_employee_loans' || $this->module_name == 'request_recruitment_menu') {
			                                ?>
			                                <button type="button" style="display:none" id="btnApprovalLogView" class="btn-sm btn-circle btn-approvalLogView" onclick="approvalLog()">
			                                    <i class="fa fa-history"></i> Approval Log
			                                </button>
			                                <?php
			                            }
										?>
									</div>
								</div>

								<div class="modal-body" style="min-height:100px; margin:10px">
									<?php $this->load->view("_detail"); ?>
								</div>

								<div class="modal-footer no-margin-top">
									<center>
									<button class="btn" style="background-color: #A01818; color: white; border-radius: 2px !important" data-dismiss="modal">
										<i class="fa fa-times"></i>
										Close
									</button>
									</center>
								</div>
							</div>
						</div>
						</div>
					</div>
