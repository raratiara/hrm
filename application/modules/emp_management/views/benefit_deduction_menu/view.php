<?php
if(_USER_ACCESS_LEVEL_VIEW == "1"){
	$this->load->view(_TEMPLATE_PATH . "module_datatable_list_view");
}

if(_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1")){
	$this->load->view(_TEMPLATE_PATH . "modal/form_field_custom");
}
?>
