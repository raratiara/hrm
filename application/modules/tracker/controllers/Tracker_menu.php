<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "tracker_menu"; // identify menu
 	const  LABELMASTER				= "Menu Tracker";
 	const  LABELFOLDER				= "tracker"; // module folder
 	const  LABELPATH				= "tracker_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "tracker"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Tracker"; // 
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


		$msemp 				= $this->db->query("select * from employees")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

		$field['master_emp'] = $this->db->query("select * from employees order by full_name asc")->result(); 
		
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


 	public function get_maps()
	{ 
		$post 	= $this->input->post(null, true);
		$empid 	= $post['empid'];
		$period = $post['period'];



		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
		
			/*if($empid != '' && $empid != 'all'){

				$empid = "'" . implode("','", $empid) . "'";

				$rs =  $this->db->query("select a.*, b.full_name as nama
						, if(lat_checkout is null or lat_checkout='',lat_checkin,lat_checkout) as lat
						, if(long_checkout is null or long_checkout='',long_checkin,long_checkout) as lng
						
						from time_attendances a 
						left join employees b on b.id = a.employee_id
						where a.employee_id in (".$empid.") and 
						(((lat_checkin is not null or lat_checkin != '') and (long_checkin is not null or long_checkin != '')) or ((lat_checkout is not null or lat_checkout != '') and (long_checkout is not null or long_checkout != ''))) ".$whr_period." ")->result();

			}else{
				$rs =  $this->db->query("select id, full_name as nama, last_lat as lat, last_long as lng from employees where (last_lat is not null or last_lat != '') and (last_long is not null or last_long != '')")->result();
			}*/



			if($empid == '' && $period == ''){
				$rs =  $this->db->query("select id, full_name as nama, last_lat as lat, last_long as lng from employees where (last_lat is not null or last_lat != '') and (last_long is not null or last_long != '')")->result();
			}else{
				$whr_emp=""; $whr_period="";
				if($empid != ''){
					$empid = "'" . implode("','", $empid) . "'";
					$whr_emp = " and (a.employee_id in (".$empid."))";
				}
				if($period != ''){
					$exp = explode(" - ",$period);
					$start = $exp[0];
					$end = $exp[1];

					$whr_period = " and (a.date_attendance between '".$start."' and '".$end."')";
				}

				$rs =  $this->db->query("select a.*, b.full_name as nama
						, if(lat_checkout is null or lat_checkout='',lat_checkin,lat_checkout) as lat
						, if(long_checkout is null or long_checkout='',long_checkin,long_checkout) as lng
						
						from time_attendances a 
						left join employees b on b.id = a.employee_id
						where 
						(((lat_checkin is not null or lat_checkin != '') and (long_checkin is not null or long_checkin != '')) or ((lat_checkout is not null or lat_checkout != '') and (long_checkout is not null or long_checkout != ''))) ".$whr_emp.$whr_period." ")->result();

			}

			

			echo json_encode($rs);
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}



}
