


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
<!-- <body>

  	<div style="margin-top: 10px;">
  		<div class="col-md-6 col-sm-12" style="margin-left:-30px" >
  			<div class="form-group">
				<label class="col-md-4 control-label no-padding-right">Employee Name</label>
				<div class="col-md-8">
					<?=$selflemployee;?>
				</div>
			</div>
  		</div>
  		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label class="col-md-3 control-label no-padding-right">Date Period</label>
				<div class="col-md-8">
					<input type="text" class="form-control" id="perioddate" name="perioddate">
				</div> 
			</div>
		</div>
  	</div>
   

    
    <button type="button" style="background-color: green; color: white; border-radius: 4px !important;" id="submitFilter" onclick="subFilter()">
      Submit Filter
    </button>
 
</body> -->
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
