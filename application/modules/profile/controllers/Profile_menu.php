<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Profile_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "profile_menu"; // identify menu
 	const  LABELMASTER				= "Menu Profile";
 	const  LABELFOLDER				= "profile"; // module folder
 	const  LABELPATH				= "profile_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "profile"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Profile"; // 
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


		/*$msemp 				= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

		$field['master_emp'] = $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); */
		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct(); 
        
        
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]); 
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan; 
		define('_USER_EMPLOYEE_ID',$karyawan_id); 

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



 	public function get_data_monthlyAttendanceSumm(){ 
 		$post = $this->input->post(null, true);
 		$year = date("Y");
		$employee 	= $post['employee'];

		


    	$rs = $this->db->query("select
			    DATE_FORMAT(date_attendance, '%m') as bln,
				SUM(CASE WHEN date_attendance_in is not null and is_late != 'Y' and leave_absences_id is null THEN 1 ELSE 0 END) AS total_ontime,
				SUM(CASE WHEN is_late = 'Y' THEN 1 ELSE 0 END) AS total_late,
				SUM(CASE WHEN is_leaving_office_early = 'Y' THEN 1 ELSE 0 END) AS total_leaving_early,
				SUM(CASE WHEN date_attendance_in is null and date_attendance_out is null and leave_absences_id is null  THEN 1 ELSE 0 END) as total_noattendance,
				count(id) as total_absensi
			FROM
				time_attendances
			where employee_id = '".$employee."' and (DATE_FORMAT(date_attendance, '%Y') = '".$year."')
			GROUP BY
			DATE_FORMAT(date_attendance, '%m')
			ORDER BY
				bln")->result(); 

		
		// Inisialisasi semua bulan 1 sampai 12 dengan nilai 0
		$bln = range(1, 12);
		$total_ontime = array_fill(0, 12, 0);
		$total_late = array_fill(0, 12, 0);
		$total_leaving_early = array_fill(0, 12, 0);
		$total_noattendance = array_fill(0, 12, 0);

		// Masukkan data dari hasil query ke posisi bulan yang sesuai
		foreach ($rs as $row) {
		    $index = $row->bln - 1; // index array mulai dari 0

		    $total_ontime[$index] = $row->total_ontime;
		    $total_late[$index] = $row->total_late;
		    $total_leaving_early[$index] = $row->total_leaving_early;
		    $total_noattendance[$index] = $row->total_noattendance;
		}

		$bln = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];


		$data = array(
			'thn' 					=> $year,
			'bln' 					=> $bln,
			'total_ontime'			=> $total_ontime,
			'total_late' 			=> $total_late,
			'total_overtime' 		=> $total_overtime,
			'total_leaving_early' 	=> $total_leaving_early,
			'total_noattendance' 	=> $total_noattendance
		);


		echo json_encode($data);

 	}


 	public function downloadFile(){ 

		$filename = $_GET['file']; // e.g., "example.pdf"

		// Set the full file path
		/*$filePath = 'documents/' . basename($filename);*/ // folder 'documents'
		$filePath = "./uploads/documents/" . basename($filename);


		if (file_exists($filePath)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
		    header('Content-Length: ' . filesize($filePath));
		    readfile($filePath);
		    exit;
		} else {
		    http_response_code(404);
		    echo "File not found.";
		}

 	}



}
