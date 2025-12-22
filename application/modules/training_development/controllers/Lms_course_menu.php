<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lms_course_menu extends MY_Controller
{ 
	/* Module */
 	const  LABELMODULE				= "lms_course_menu"; // identify menu
 	const  LABELMASTER				= "Menu Training";
 	const  LABELFOLDER				= "training_development"; // module folder
 	const  LABELPATH				= "lms_course_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "training_development"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Course Name","Category","Departments","Description","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Course Name","Category","Departments","Description","Status"];
	public $colfields 				= ["id","course_name","category","department_names","description","is_active_desc"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		
		$field = [];


		$field['txtxcoursename'] 	= $this->self_model->return_build_txt('','course_name','course_name');
		$field['txtdesc'] 			= $this->self_model->return_build_txtarea('','desc','desc');

		$msdept 					= $this->db->query("select * from departments order by name asc")->result(); 
		$field['seldept'] 			= $this->self_model->return_build_select2me($msdept,'multiple','','','departments','departments','','','id','name',' ','','','',3,'-');
		$field['txtisactive'] 		= $this->self_model->return_build_radio('1', [['1','Active'],['0','Not Active']], 'is_active', '', 'inline');
		$field['txtcategory'] 		= $this->self_model->return_build_radio('', [['Hardskill','Hardskill'],['Softskill','Softskill']], 'category', '', 'inline');


		
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


 	
}
