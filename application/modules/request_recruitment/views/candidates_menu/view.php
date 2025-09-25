<!-- <div class="d-flex justify-content-end mb-3">
  <button id="btnListView" class="btn btn-sm btn-primary">Table View</button>
  <button id="btnKanbanView" class="btn btn-sm btn-secondary">Kanban View</button>
  <?= $seldiv ?>
</div>

<br>

<div class="row mb-2" style="padding-left: 250px;
        padding-right: 10px;">
    <div class="col-md-4">
        <?= $seldiv ?>
    </div>
    <div class="col-md-4 text-end">
        <?= $selposition ?>
    </div>
</div> -->



<div class="title-box-chart-hdr">
    <!-- <div class="d-flex justify-content-end mb-3">
      <button id="btnListView" class="btn btn-sm btn-primary">Table View</button>
      <button id="btnKanbanView" class="btn btn-sm btn-secondary">Kanban View</button>
    </div> -->
    <div class="view-toggle btn-group">
        <button id="btnListView" class="btn btn-primary active" title="List View">
            <i class="fa fa-list-ul" aria-hidden="true"></i>
        </button>
        <button id="btnKanbanView" class="btn btn-outline-secondary" title="Card View">
            <i class="fa fa-th-large" aria-hidden="true"></i>
        </button>
    </div>
    <div class="top-bar">
        <div class="employee-select-wrapper">
            <span class="employee-icon"><i class="fa fa-user"></i></span>
            <?= $seldiv ?>
        </div>
        <div class="employee-select-wrapper">
            <span class="employee-icon"><i class="fa fa-user"></i></span>
            <?= $selposition ?>
        </div>
    </div>
</div>







<!-- LIST VIEW (DataTable) -->
<div id="table-container">
    <?php $this->load->view(_TEMPLATE_PATH . "module_datatable_list_view"); ?>
</div>

<!-- KANBAN VIEW -->
<div id="card-container" style="display:none;">
   <?php $this->load->view(_TEMPLATE_PATH . "module_kanban_candidates_view"); ?>
</div>





<?php 
//if  (_USER_ACCESS_LEVEL_VIEW == "1") { 
	//$this->load->view(_TEMPLATE_PATH . "module_datatable_list_view"); // standard
	//$this->load->view(_TEMPLATE_PATH . "module_kanban_candidates_view");
//}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_DETAIL == "1") { 
	$this->load->view(_TEMPLATE_PATH . "modal/detail"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1")) { 
	/*$this->load->view(_TEMPLATE_PATH . "modal/form_field_mpp");*/ // standard
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