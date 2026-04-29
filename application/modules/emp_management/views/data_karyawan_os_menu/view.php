

<style type="text/css">
	.employee-filter-wrap {
		margin: 0;
	}

	.employee-advance-toggle {
		margin: 15px 15px 10px;
	}

	.employee-advance-box {
		display: none;
		margin: 0 15px 18px;
		padding: 18px 20px;
		border: 0;
		border-radius: 14px;
		background: #ffffff;
		box-shadow: 0 10px 26px rgba(17, 45, 128, 0.10);
	}

	.employee-advance-box .box-title {
		margin: 0 0 16px;
		font-weight: 600;
		color: #112D80;
	}

	.employee-advance-box .form-group {
		margin-bottom: 0;
	}

	.employee-filter-wrap .filter-actions {
		padding-top: 25px;
	}
</style>

<div class="employee-advance-toggle">
	<button type="button" class="btn btn-default" id="btnToggleAdvanceSearch">
		<i class="fa fa-search"></i> Advance Search
	</button>
</div>

<div class="employee-advance-box" id="advanceSearchBox">
	<div class="box-title">Advance Search</div>
	<div class="row employee-filter-wrap">
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<label>Status Karyawan</label>
				<select id="flstatus" class="form-control">
					<option value="">All Status</option>
					<option value="1">Active</option>
					<option value="0">Not Active</option>
				</select>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="filter-actions">
				<button type="button" class="btn btn-default" onclick="resetFilter()">
					Reset
				</button>
			</div>
		</div>
	</div>
</div>

<?php 
if  (_USER_ACCESS_LEVEL_VIEW == "1") {
	$this->load->view(_TEMPLATE_PATH . "module_datatable_list_view"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_DETAIL == "1") {
	$this->load->view(_TEMPLATE_PATH . "modal/detail"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1")) { 
	$this->load->view(_TEMPLATE_PATH . "modal/form_field_custom"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_IMPORT == "1") {					
	$this->load->view(_TEMPLATE_PATH . "modal/import"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_EKSPORT == "1") {
	$this->load->view(_TEMPLATE_PATH . "modal/eksport"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_DELETE == "1") {
	$this->load->view(_TEMPLATE_PATH . "modal/delete"); // standard
	$this->load->view(_TEMPLATE_PATH . "modal/delete_bulk"); // standard
}
?>
