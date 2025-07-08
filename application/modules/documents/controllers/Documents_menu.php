<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "documents_menu"; // identify menu
 	const  LABELMASTER				= "Menu Documents";
 	const  LABELFOLDER				= "documents"; // module folder
 	const  LABELPATH				= "documents_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "documents"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Documents"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Name","File"];
	
	/* Export */
	public $colnames 				= ["ID","Name","File"];
	public $colfields 				= ["id","name","file"];




	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];


		$field['txtname']	= $this->self_model->return_build_txt('','name','name'); 
		$field['txtfile'] 	= $this->self_model->return_build_fileinput('file','file');


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
