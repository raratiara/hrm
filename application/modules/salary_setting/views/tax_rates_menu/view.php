<?php 
if  (_USER_ACCESS_LEVEL_VIEW == "1") { $this->load->view(_TEMPLATE_PATH . "module_datatable_list_view"); }
if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_DETAIL == "1") { $this->load->view(_TEMPLATE_PATH . "modal/detail"); }
if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1")) { $this->load->view(_TEMPLATE_PATH . "modal/form_field_custom"); }
if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_IMPORT == "1") { $this->load->view(_TEMPLATE_PATH . "modal/import"); }
if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_EKSPORT == "1") { $this->load->view(_TEMPLATE_PATH . "modal/eksport"); }
if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_DELETE == "1") { $this->load->view(_TEMPLATE_PATH . "modal/delete"); $this->load->view(_TEMPLATE_PATH . "modal/delete_bulk"); }
?>
