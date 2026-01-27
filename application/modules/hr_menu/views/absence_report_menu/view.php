


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- <title>Date Range Picker</title> -->

  <style type="text/css">
  
		#submitFilter{
		    background-color: #3832d2; /* #3490dc; */
		    color: white;

		    padding: 8px 14px;        /* kecilin tinggi & lebar */
		    font-size: 12px;          /* kecilin teks */
		    line-height: 1.2;         /* biar tinggi pas & teks center */

		    border: none;
		    border-radius: 6px;       /* radius ikut dikecilin */
		    box-shadow: 0 3px 4px rgba(0, 0, 0, 0.2);

		    transition: all 0.2s ease-in-out;

		    display: inline-flex;    /* center horizontal & vertical */
		    align-items: center;
		    justify-content: center;
		}

  </style>
  
</head>
<body>

  	

    <div class="row filter-wrap" style="margin-top:15px">

		
		  <!-- Employee -->
		  <div class="col-md-4 col-sm-12">
		    <div class="form-group row form-row">
		      <label class="col-sm-4 col-form-label">Employee Name</label>
		      <div class="col-sm-8">
		        <?=$selflemployee;?>
		      </div>
		    </div>
		  </div>

		  

		  <!-- Date Period -->
		  <div class="col-md-4 col-sm-12">
		    <div class="form-group row form-row">
		      <label class="col-sm-4 col-form-label">Date Period</label>
		      <div class="col-sm-8">
		        <input type="text" class="form-control" id="perioddate" name="perioddate">
		      </div>
		    </div>
		  </div>

		  <!-- Button -->
		  <!-- <div class="col-md-12"> -->
		  <div class="col-md-4 col-sm-12">
		    <div class="text-left filter-actions">
		      <button type="button" class="btn btn-success" id="submitFilter" onclick="subFilter()">
		        Submit Filter
		      </button>
		    </div>
		  </div>

		</div>

 
</body>
</html>






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
