
<!-- <!DOCTYPE html>
<html>
<head>
    <title>Import Excel CI</title>
</head>
<body>

<h2>Import Excel ke Database</h2>

<?php if ($this->session->flashdata('sukses')): ?>
    <p style="color:green"><?= $this->session->flashdata('sukses') ?></p>
<?php elseif ($this->session->flashdata('gagal')): ?>
    <p style="color:red"><?= $this->session->flashdata('gagal') ?></p>
<?php endif; ?>

<form action="<?= base_url('emp_management/data_karyawan_menu/upload_excel') ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="fileexcel" accept=".xls,.xlsx" required>
    <button type="submit">Upload</button>
</form>

</body>
</html> -->








<?php 
if  (_USER_ACCESS_LEVEL_VIEW == "1") {
	$this->load->view(_TEMPLATE_PATH . "module_datatable_list_view"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_DETAIL == "1") {
	$this->load->view(_TEMPLATE_PATH . "modal/detail"); // standard
}

if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1")) { 
	$this->load->view(_TEMPLATE_PATH . "modal/form_field"); // standard
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
