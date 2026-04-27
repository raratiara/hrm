<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spt_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "spt_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu SPT OS";
 	const  LABELFOLDER				= "payroll_outsource"; // module folder
 	const  LABELPATH				= "spt_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "payroll_outsource"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Project", "Tahun Pajak","Tgl Generate","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Project", "Tahun Pajak","Tgl Generate","Status"];
	public $colfields 				= ["id","project_name","tahun","created_at","status_name"];


	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		$field['txtprojectview']	= $this->self_model->return_build_txt('','projectview','projectview','','','readonly');
		$field['txtyear']			= $this->self_model->return_build_txt('','tahun_pajak','tahun_pajak','','','required');

		$msstatus 				= $this->db->query("select * from master_status_spt order by id asc")->result(); 
		$field['selstatus'] 	= $this->self_model->return_build_select2me($msstatus,'','','','status','status','','','id','name',' ','','','',3,'-');
		
	
		$msemp 						= $this->db->query("select * from employees where emp_source = 'outsource' and status_id = 1 and is_special_payroll != 1 order by full_name asc")->result(); 
		$field['selemployeeids'] 	= $this->self_model->return_build_select2me($msemp,'multiple','','','employeeIds[]','employeeIds','','','id','full_name',' ','','','',3,'-');
		
		$field['selflemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','flemployee','flemployee','','','id','full_name',' ','','','',3,'-');

		$field['is_all_project'] 	= $this->self_model->return_build_radio('Semua', [['Semua','Semua'],['Sebagian','Sebagian'],['Karyawan','Per Karyawan']], 'is_all_project', '', 'inline');
		$msproject 				= $this->db->query('select * from project_outsource order by code asc')->result(); 
		$field['selprojectids'] 	= $this->self_model->return_build_select2me($msproject,'multiple','','','projectIds[]','projectIds','','','id','project_name',' ','','','',3,'-');
		
		$field['selflproject'] = $this->self_model->return_build_select2me(
			$msproject,
			'',
			'',
			'',
			'flproject',
			'flproject',
			'',
			'',
			'id',
			'project_name',
			' ',
			'',
			'',
			'',
			3,
			'-'
		);

		
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


 	

	public function gensptosrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$project = trim($post['project']);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewSptOSRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewSptOSRow($row,$id,$project,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function getFormSptOS_pdf()
	{
	    $sql = "
	        select a.*, b.tahun, b.project_id, c.project_name, b.status_id as status_header_id, d.name as status_header, e.full_name, e.no_npwp, e.no_ktp, e.address_ktp, if(e.gender = 'M', 'Laki-Laki','Perempuan') as gender_name, f.name as status_marital_name, g.name as job_title_name, e.nationality, h.name as company_name, h.npwp as company_npwp,
		        (case when e.nationality = '' then '-' 
			    when e.nationality like '%indonesia%' then 'no' 
			    else 'yes' end) as is_karyawan_asing, b.created_at
			from spt_pph21_detail a 
			left join spt_pph21 b on b.id = a.spt_pph21_id
			left join project_outsource c on c.id = b.project_id
			left join master_status_spt d on d.id = b.status_id
			left join employees e on e.id = a.employee_id
			left join master_marital_status f on f.id = e.marital_status_id
			left join master_job_title_os g on g.id = e.job_title_id
			left join companies h on h.id = e.company_id
			where e.emp_source = 'outsource' and e.is_special_payroll != 1 and a.id = ".$_GET['form_id']."
	    ";

	    /*$sql = "
	        select * from employees limit 1
	    ";*/


	    $data = $this->db->query($sql)->result();

	    $this->load->library('html_pdf');

	    $pdfData = [
	        'title' 		=> 'FORM 1721 OUTSOURCE',
	        'emp_name' 		=> $data[0]->full_name,
	        'tahun_pajak' 	=> $data[0]->tahun,
	        'periode' 		=> $data[0]->periode_start.' s/d '.$data[0]->periode_end,
	        'data'  		=> $data
	    ];

	    $pdfBinary = $this->html_pdf->render_to_string(
	        'pdf/form_spt_os',
	        $pdfData
	    );

	    if (ob_get_level()) ob_end_clean();

	    $safeName = preg_replace('/[^A-Za-z0-9 _-]/', '', $data[0]->full_name);

	    header("Content-Type: application/pdf");
	    header("Content-Disposition: attachment; filename=FORM 1721 - ".$safeName.".pdf");
	    echo $pdfBinary;
	    exit;
	}



}
