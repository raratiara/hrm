<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_recruitment_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dash_recruitment_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Recruitment";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dash_recruitment_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "dashboard"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Dashboard"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Floating Crane","CCTV Code","CCTV Name"];
	
	/* Export */
	public $colnames 				= ["Date","Order No","Order Name","Floating Crane","Mother Vessel","Activity","Datetime Start","Datetime End","Total Time","Degree","Degree 2","PIC","Status"];
	public $colfields 				= ["date","order_no","order_name","floating_crane_name","mother_vessel_name","activity_name","datetime_start","datetime_end","total_time","degree","degree_2","pic","status_name"];




	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];


		$msemp 				= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

		$msdiv 				= $this->db->query("select * from divisions order by name asc")->result(); 
		$field['seldiv'] 	= $this->self_model->return_build_select2me($msdiv,'','','','fldiv','fldiv','','','id','name',' ','','','',1,'-');

		$field['master_emp'] = $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct(); 
        
        
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]); 
		/*define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);*/
    }

	/* Module */
 	public $folder_name				= self::LABELFOLDER."/".self::LABELPATH; // module path
 	public $module_name				= self::LABELMODULE;
 	public $model_name				= self::LABELPATH."_model";

	/* Navigation */
 	public $parent_menu				= self::LABELFOLDER;
 	public $subparent_menu			= self::LABELNAVSEG1;
 	public $subparentitem_menu		= self::LABELNAVSEG2;
 	public $sub_menu 				= self::LABELMODULE;

	/* Label */
 	public $label_parent_modul		= self::LABELFOLDER;
 	public $label_subparent_modul	= self::LABELSUBPARENTSEG1;
 	public $label_subparentitem_modul	= self::LABELSUBPARENTSEG2;
 	public $label_modul				= self::LABELMASTER;
 	public $label_list_data			= "Daftar Data ".self::LABELMASTER;
 	public $label_add_data			= "Tambah Data ".self::LABELMASTER;
 	public $label_update_data		= "Edit Data ".self::LABELMASTER;
 	public $label_sukses_disimpan 	= "Data berhasil disimpan";
 	public $label_gagal_disimpan 	= "Data gagal disimpan";
 	public $label_delete_data		= "Hapus Data ".self::LABELMASTER;
 	public $label_sukses_dihapus 	= "Data berhasil dihapus";
 	public $label_gagal_dihapus 	= "Data gagal dihapus";
 	public $label_detail_data		= "Datail Data ".self::LABELMASTER;
 	public $label_import_data		= "Import Data ".self::LABELMASTER;
 	public $label_sukses_diimport 	= "Data berhasil diimport";
 	public $label_gagal_diimport 	= "Import data di baris : ";
 	public $label_export_data		= "Export";
 	public $label_gagal_eksekusi 	= "Eksekusi gagal karena ketiadaan data";

	//============================== Additional Method ==============================//


 	public function get_data_total(){
 		$post = $this->input->post(null, true);
		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" where b.division_id = '".$fldiv."'";
		}

		
		$request = $this->db->query("select count(a.id) as ttl from request_recruitment a 
									left join sections b ON b.id = a.section_id ".$whereDiv."")->result(); 
		


		$rs = array(
			'ttl_request' 	=> $request[0]->ttl
		);


		
		echo json_encode($rs);
 	}


 	public function get_data_openByDiv(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" where b.division_id = '".$fldiv."'";
		}


		$divisions = $this->db->query("select id, name from divisions")->result();
		$division_counts = [];
		foreach ($divisions as $div) {
		    $division_counts[$div->name] = 0;
		}
		$division_counts['Unknown'] = 0; // fallback untuk data tanpa divisi

		$data = $this->db->query("
		    select c.name as division_name
		    FROM request_recruitment a
		    LEFT JOIN sections b ON b.id = a.section_id
		    LEFT JOIN divisions c ON c.id = b.division_id ".$whereDiv."
		")->result();

		foreach ($data as $row) {
		    $div_name = $row->division_name ?? 'Unknown';

		    if (!isset($division_counts[$div_name])) {
		        $division_counts[$div_name] = 0; // in case master tidak ada
		    }

		    $division_counts[$div_name]++;
		}


		$result = [
		    'labels' => array_keys($division_counts),
		    'values' => array_values($division_counts)
		];

		echo json_encode($result);


 	}

 	public function get_data_byStatusPengajuan(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" where b.division_id = '".$fldiv."'";
		}


		
		$masterStatus = ['draft','waiting_approval','approved','rejected','cancelled'];
		
		$divisions = $this->db->query("select id, name from divisions")->result();
		$status_counts = [];
		foreach ($masterStatus as $rowStatus) {
		    $status_counts[$rowStatus] = 0;
		}
		$status_counts['Unknown'] = 0; // fallback untuk data tanpa divisi

		$data = $this->db->query("
		    select a.status as status_name
			FROM request_recruitment a left join sections b ON b.id = a.section_id ".$whereDiv."
		")->result();

		foreach ($data as $row) {
		    $name = $row->status_name ?? 'Unknown';

		    if (!isset($status_counts[$name])) {
		        $status_counts[$name] = 0; // in case master tidak ada
		    }

		    $status_counts[$name]++;
		}


		$result = [
		    'labels' => array_keys($status_counts),
		    'values' => array_values($status_counts)
		];

		echo json_encode($result);

 	}


 	public function get_data_byStatusEmployee(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" where b.division_id = '".$fldiv."'";
		}


		
		$masterStatus = ['freelance','contract','permanent'];
		$status_counts = [];
		foreach ($masterStatus as $rowStatus) {
		    $status_counts[$rowStatus] = 0;
		}
		$status_counts['Unknown'] = 0; // fallback untuk data tanpa divisi

		$data = $this->db->query("
		    select a.status_emp as status_name
			FROM request_recruitment a left join sections b ON b.id = a.section_id ".$whereDiv."
		")->result();

		foreach ($data as $row) {
		    $name = $row->status_name ?? 'Unknown';

		    if (!isset($status_counts[$name])) {
		        $status_counts[$name] = 0; // in case master tidak ada
		    }

		    $status_counts[$name]++;
		}


		$result = [
		    'labels' => array_keys($status_counts),
		    'values' => array_values($status_counts)
		];

		echo json_encode($result);

 	}


 	public function get_data_byJobLevel(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" where b.division_id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
								  a.status AS status_name,
								  COUNT(a.id) AS total
								from request_recruitment a left join sections b ON b.id = a.section_id ".$whereDiv."
								GROUP BY status
								ORDER BY status")->result(); 

		$status=[]; $total=[]; 
		foreach($rs as $row){
			$status[] 	= $row->status_name;
			$total[] 	= $row->total;
			
		}


		$data = array(
			'status' 	=> $status,
			'total'		=> $total
		);


		echo json_encode($data);


 	}



}
