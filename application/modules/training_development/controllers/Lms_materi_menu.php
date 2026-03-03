<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lms_materi_menu extends MY_Controller
{
	/* Module */
	const  LABELMODULE				= "lms_materi_menu"; // identify menu
	const  LABELMASTER				= "Menu Training";
	const  LABELFOLDER				= "training_development"; // module folder
	const  LABELPATH				= "lms_materi_menu"; // controller file (lowercase)
	const  LABELNAVSEG1				= "training_development"; // adjusted 1st sub parent segment
	const  LABELSUBPARENTSEG1		= "Master"; // 
	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
	const  LABELSUBPARENTSEG2		= ""; // 

	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID", "Title", "Type", "File PDF/ Youtube Url", "Departments", "Course"];


	/* Export */
	public $colnames 				= ["ID", "Title", "Type", "File PDF/ Youtube Url", "Departments", "Course"];
	public $colfields 				= ["id", "id", "id", "id", "id", "id"];



	public function get_cards()
	{
		$page   = (int) $this->input->get('page', true);
		$length = (int) $this->input->get('length', true);
		$search = $this->input->get('search', true);
		$type   = $this->input->get('type', true);

		if ($page < 1) $page = 1;
		if ($length < 1) $length = 9;

		$result = $this->self_model->get_cards_list($page, $length, $search, $type);

		header('Content-Type: application/json');
		echo json_encode($result);
	}


	/* Form Field Asset */
	public function form_field_asset()
	{

		$field = [];


		$mscourse 					= $this->db->query("select * from lms_course where is_active = 1 order by course_name")->result();
		$field['selcourse'] 		= $this->self_model->return_build_select2me($mscourse, '', '', '', 'course', 'course', '', '', 'id', 'course_name', ' ', '', '', '', '3', '-');
		$msdept 					= $this->db->query("select * from departments order by name asc")->result();
		$field['seldept'] 			= $this->self_model->return_build_select2me($msdept, 'multiple', '', '', 'departments[]', 'departments', '', '', 'id', 'name', ' ', '', '', '', 3, '-');
		$mstype 					= $this->db->query("select * from master_lms_materi_type order by name asc")->result();
		$field['seltype'] 			= $this->self_model->return_build_select2me($mstype, '', '', '', 'type', 'type', '', '', 'id', 'name', ' ', '', '', '', 3, '-');
		$field['txttitle'] 			= $this->self_model->return_build_txt('', 'title_materi', 'title_materi');
		$field['txtyoutubeurl'] 	= $this->self_model->return_build_txtarea('', 'youtube_url', 'youtube_url');
		$field['txtfilepdf'] 		= $this->self_model->return_build_fileinput('file_pdf', 'file_pdf');




		return $field;
	}

	//========================== Considering Already Fixed =======================//
	/* Construct */
	public function __construct()
	{
		parent::__construct();
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW', $akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD', $akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE', $akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE', $akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL', $akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT', $akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT', $akses["eksport"]);
	}

	/* Module */
	public $folder_name				= self::LABELFOLDER . "/" . self::LABELPATH; // module path
	public $module_name				= self::LABELMODULE;
	public $model_name				= self::LABELPATH . "_model";

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
	public $label_list_data			= "Daftar Data " . self::LABELMASTER;
	public $label_add_data			= "Tambah Data " . self::LABELMASTER;
	public $label_update_data		= "Edit Data " . self::LABELMASTER;
	public $label_sukses_disimpan 	= "Data berhasil disimpan";
	public $label_gagal_disimpan 	= "Data gagal disimpan";
	public $label_delete_data		= "Hapus Data " . self::LABELMASTER;
	public $label_sukses_dihapus 	= "Data berhasil dihapus";
	public $label_gagal_dihapus 	= "Data gagal dihapus";
	public $label_detail_data		= "Datail Data " . self::LABELMASTER;
	public $label_import_data		= "Import Data " . self::LABELMASTER;
	public $label_sukses_diimport 	= "Data berhasil diimport";
	public $label_gagal_diimport 	= "Import data di baris : ";
	public $label_export_data		= "Export";
	public $label_gagal_eksekusi 	= "Eksekusi gagal karena ketiadaan data";

	//============================== Additional Method ==============================//



	public function downloadFile()
	{

		$filename = $_GET['file']; // e.g., "example.pdf"

		// Set the full file path
		/*$filePath = 'documents/' . basename($filename);*/ // folder 'documents'
		$filePath = "./uploads/lms_materi/" . basename($filename);


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
