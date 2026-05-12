<div class="row filter-wrap" style="margin-top:15px; margin-bottom:15px;">
	<div class="col-md-4 col-sm-12">
		<div class="form-group row form-row">
			<label class="col-sm-3 col-form-label">Date Range</label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="filter_perioddate" name="filter_perioddate" autocomplete="off">
			</div>
		</div>
	</div>

	<div class="col-md-4 col-sm-12">
		<button type="button" class="btn btn-success" id="submitFilter" onclick="subFilter()">
			Submit Filter
		</button>
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
