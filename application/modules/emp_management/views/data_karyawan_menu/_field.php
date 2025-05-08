
<div class="row">
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Employee Code </label>
			<div class="col-md-8">
				<?=$txtempcode;?>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Gender </label>
			<div class="col-md-8">
				<?=$txtgender;?>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">FullName </label>
			<div class="col-md-8">
				<?=$txtfullname;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Email</label>
			<div class="col-md-8">
				<?=$txtemail;?>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">NickName </label>
			<div class="col-md-8">
				<?=$txtnickname;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Phone </label>
			<div class="col-md-8">
				<?=$txtphone;?>
			</div>
		</div>
	</div>
</div>



<ul class="nav nav-tabs" id="tabContent">
    <li class="active"><a href="#personal" data-toggle="tab">Personal</a></li>
    <li><a href="#office" data-toggle="tab">Office</a></li>
    <li><a href="#education" data-toggle="tab">Education</a></li>
    <li><a href="#address" data-toggle="tab">Address</a></li>
    <li><a href="#emergency_contact" data-toggle="tab">Emergency Contact</a></li>
    <li><a href="#bank_account" data-toggle="tab">Bank Account</a></li>
    <li><a href="#training" data-toggle="tab">Training</a></li>
    <li><a href="#organization" data-toggle="tab">Organization</a></li>
    <li><a href="#work_experience" data-toggle="tab">Work Experience</a></li>
    <li><a href="#exit_clearance" data-toggle="tab">Exit Clearance</a></li>
</ul>



<div class="tab-content">
    <div class="tab-pane active" id="personal">
      <div class="row">

      	<div class="col-md-6 col-sm-12">
	       	<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Place of Birth</label>
						<div class="col-md-8">
							<?=$txtplaceofbirth;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Nationality</label>
						<div class="col-md-8">
							<?=$txtnationality;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">No KTP </label>
						<div class="col-md-8">
							<?=$txtnoktp;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">No NPWP</label>
						<div class="col-md-8">
							<?=$txtnonpwp;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">SIM A</label>
						<div class="col-md-8">
							<?=$txtsima;?>
						</div>
					</div>
	      </div>

	      <div class="col-md-6 col-sm-12">
	      	<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Date of Birth </label>
						<div class="col-md-8">
							<?=$txtdateofbirth;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Ethnic </label>
						<div class="col-md-8">
							<?=$txtethnic;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Marital Status </label>
						<div class="col-md-8">
							<?=$selmaritalstatus;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">No BPJS </label>
						<div class="col-md-8">
							<?=$txtnobpjs;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">SIM C </label>
						<div class="col-md-8">
							<?=$txtsimc;?>
						</div>
					</div>
	      </div>

      </div>
    </div>



    <div class="tab-pane" id="office">
    	<div class="row">

				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Company</label>
						<div class="col-md-8">
							<?=$selcompany;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Division</label>
						<div class="col-md-8">
							<?=$seldivision;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Department </label>
						<div class="col-md-8">
							<?=$seldepartment;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Direct </label>
						<div class="col-md-8">
							<?=$seldirect;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Job Title </label>
						<div class="col-md-8">
							<?=$seljobtitle;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Date of Hire </label>
						<div class="col-md-8">
							<?=$txtdateofhire;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Shift Type </label>
						<div class="col-md-8">
							<?=$txtshifttype;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Emp Photo</label>
						<div class="col-md-4">
							<?=$txtempphoto;?>
							<input type="hidden" id="hdnempphoto" name="hdnempphoto"/>
						</div>
						<div class="col-md-4">
							<span class="file_emp_photo"></span>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Work Location </label>
						<div class="col-md-8">
							<?=$txtworklocation;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Section</label>
						<div class="col-md-8">
							<?=$selsection;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Branch </label>
						<div class="col-md-8">
							<?=$selbranch;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Indirect </label>
						<div class="col-md-8">
							<?=$selindirect;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Employee Status </label>
						<div class="col-md-8">
							<?=$selempstatus;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Date End Probation </label>
						<div class="col-md-8">
							<?=$txtdateendprob;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Date Permanent </label>
						<div class="col-md-8">
							<?=$txtdatepermanent;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label no-padding-right">Emp Signature </label>
						<div class="col-md-4">
							<?=$txtempsignature;?>
							<input type="hidden" id="hdnempsign" name="hdnempsign"/>
						</div>
						<div class="col-md-4">
							<span class="file_emp_sign"></span>
						</div>
					</div>
				</div>

    	</div>
    </div> 




    <div class="tab-pane" id="education">
        <div class="row">
        	<!-- <div class="col-md-6 col-sm-12">
        		<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Last Education</label>
							<div class="col-md-8">
								<?=$seleducation;?>
							</div>
						</div>
        	</div> -->

        	<div class="row ca">
			    <div class="col-md-12">
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">Detail Education </div>
							<div class="tools">
								<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addcarow" value="Add Row" />
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable tablesaw-cont">
							<table class="table table-striped table-bordered table-hover ca-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailEdu">
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Type</th>
										<th scope="col">Institusi</th>
										<th scope="col">Kota</th>
										<th scope="col">Tahun</th>
										<th scope="col"></th>
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



    <div class="tab-pane" id="address">
        <div class="row">

        	<div class="col-md-6 col-sm-12">
        		<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">KTP Address </label>
					<div class="col-md-8">
						<?=$txtaddress1;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Province </label>
					<div class="col-md-8">
						<?=$selprovince1;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Regency </label>
					<div class="col-md-8">
						<?=$selregency1;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">District </label>
					<div class="col-md-8">
						<?=$seldistrict1;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Village </label>
					<div class="col-md-8">
						<?=$selvillage1;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Postal Code </label>
					<div class="col-md-8">
						<?=$txtpostalcode1;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Is the Residential Address the same as the Address on the KTP? </label>
					<div class="col-md-8">
						<?=$chksameaddress;?>
					</div>
				</div>
        	</div>

        	<div class="col-md-6 col-sm-12">
        		<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Residential Address </label>
					<div class="col-md-8">
						<?=$txtaddress2;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Province </label>
					<div class="col-md-8">
						<?=$selprovince2;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Regency </label>
					<div class="col-md-8">
						<?=$selregency2;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">District </label>
					<div class="col-md-8">
						<?=$seldistrict2;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Village </label>
					<div class="col-md-8">
						<?=$selvillage2;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Postal Code </label>
					<div class="col-md-8">
						<?=$txtpostalcode2;?>
					</div>
				</div>
        	</div>

        </div>
    </div> 




    <div class="tab-pane" id="emergency_contact">
        	<div class="row">

        		<div class="col-md-6 col-sm-12">
        			<div class="form-group">
								<label class="col-md-4 control-label no-padding-right">Emergency Contact Name</label>
								<div class="col-md-8">
									<?=$txtemergencyname;?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label no-padding-right">Emergency Contact Phone </label>
								<div class="col-md-8">
									<?=$txtemergencyphone;?>
								</div>
							</div>
        		</div>

        		<div class="col-md-6 col-sm-12">
        			<div class="form-group">
								<label class="col-md-4 control-label no-padding-right">Emergency Contact Relation </label>
								<div class="col-md-8">
									<?=$txtemergencyrelation;?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label no-padding-right">Emergency Contact Email</label>
								<div class="col-md-8">
									<?=$txtemergencyemail;?>
								</div>
							</div>
        		</div>

        	</div>
    </div> 



    <div class="tab-pane" id="bank_account">
        <div class="row">

        	<div class="col-md-6 col-sm-12">
        		<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Bank Name</label>
							<div class="col-md-8">
								<?=$txtbankname;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Bank Account Name</label>
							<div class="col-md-8">
								<?=$txtbankaccname;?>
							</div>
						</div>
        	</div>

        	<div class="col-md-6 col-sm-12">
        		<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Bank Address </label>
							<div class="col-md-8">
								<?=$txtbankaddress;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Bank Account No </label>
							<div class="col-md-8">
								<?=$txtbankaccno;?>
							</div>
						</div>
        	</div>

        </div>
    </div>



	<div class="tab-pane" id="training">
        <div class="row">

        	<div class="row ca">
			    <div class="col-md-12">
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">Detail Training </div>
							<div class="tools">
								<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addcarow-training" value="Add Row" />
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable tablesaw-cont">
							<table class="table table-striped table-bordered table-hover ca-list-training tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailTraining">
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Training Name</th>
										<th scope="col">City</th>
										<th scope="col">Year</th>
										<th scope="col"></th>
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



    <div class="tab-pane" id="organization">
        <div class="row">

        	<div class="row ca">
			    <div class="col-md-12">
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">Detail Organization </div>
							<div class="tools">
								<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addcarow-org" value="Add Row" />
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable tablesaw-cont">
							<table class="table table-striped table-bordered table-hover ca-list-org tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailOrg">
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Organization Name</th>
										<th scope="col">Institution</th>
										<th scope="col">Position</th>
										<th scope="col">City</th>
										<th scope="col">Year</th>
										<th scope="col"></th>
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


    <div class="tab-pane" id="work_experience">
        <div class="row">

        	<div class="row ca">
			    <div class="col-md-12">
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">Detail Work Experience </div>
							<div class="tools">
								<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addcarow-workexp" value="Add Row" />
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable tablesaw-cont">
							<table class="table table-striped table-bordered table-hover ca-list-workexp tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailWorkexp">
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Company</th>
										<th scope="col">Position</th>
										<th scope="col">City</th>
										<th scope="col">Year</th>
										<th scope="col"></th>
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



    <div class="tab-pane" id="exit_clearance">
        <div class="row">

        	<div class="col-md-6 col-sm-12">
        		<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Resign Category </label>
							<div class="col-md-8">
								<?=$txtresigncategory;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Date Resign Active </label>
							<div class="col-md-8">
								<?=$txtdateresignactive;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Resign Reason</label>
							<div class="col-md-8">
								<?=$txtresignreason;?>
							</div>
						</div>
        	</div>

        	<div class="col-md-6 col-sm-12">
        		<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Date Resign Letter </label>
							<div class="col-md-8">
								<?=$txtdateresignletter;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Resign Exit Interview Feedback</label>
							<div class="col-md-8">
								<?=$txtresignexitfeedback;?>
							</div>
						</div>
        	</div>

        </div>

    </div> 



</div>





								