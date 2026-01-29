<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "hitung_gaji_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu Hitung Gaji Outsource";
 	const  LABELFOLDER				= "payroll_outsource"; // module folder
 	const  LABELPATH				= "hitung_gaji_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "payroll_outsource"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	


	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Bulan Penggajian","Tahun Penggajian","Karyawan","Project"];

	
	/* Export */
	public $colnames 				= ["ID","Bulan Penggajian","Tahun Penggajian","Karyawan","Project"];
	public $colfields 				= ["id","periode_bulan_name","periode_tahun","full_name","project_name"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1){ //bukan super user
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}



		$field = [];

		$field['is_all_project'] = $this->self_model->return_build_radio('', [['Semua','Semua'],['Sebagian','Sebagian']], 'is_all_project_fcast', '', 'inline');
		$msproject 				= $this->db->query('select id,
								(case when jenis_pekerjaan != "" and lokasi != "" then concat(code," (",lokasi," - ",jenis_pekerjaan,")")
								when jenis_pekerjaan != "" and lokasi = "" then concat(code," (",jenis_pekerjaan,")")
								when lokasi != "" and jenis_pekerjaan = "" then concat(code," (",lokasi,")")
								else code end
								) as project_desc, project_name
								from project_outsource order by code asc')->result(); 
		$field['selprojectids'] 	= $this->self_model->return_build_select2me($msproject,'multiple','','','projectIds_fcast[]','projectIds_fcast','','','id','project_name',' ','','','',3,'-');


		$msmonth 				= $this->db->query("select * from master_month order by id asc")->result(); 
		$field['sel_penggajian_bulan'] 	= $this->self_model->return_build_select2me($msmonth,'','','','penggajian_month_fcast','penggajian_month_fcast','','','id','name_indo',' ','','','',3,'-');
		$field['txt_penggajian_tahun']	= $this->self_model->return_build_txt('','penggajian_year_fcast','penggajian_year_fcast');
		$field['txt_jml_ttlmasuk_nominal']	= $this->self_model->return_build_txt('','jml_ttlmasuk_nominal','jml_ttlmasuk_nominal','','','readonly');
		$field['txt_jml_ttllembur_nominal']	= $this->self_model->return_build_txt('','jml_ttllembur_nominal','jml_ttllembur_nominal','','','readonly');

		
		
		
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


 	public function genfcastrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$penggajian_month 	= $post['penggajian_month'];
			$penggajian_year 	= $post['penggajian_year'];
			$project 			= $post['project'];

			if(isset($post['count']))
			{   
				$row = trim($post['count']); 
				echo $this->self_model->getNewFcastRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewFcastRow($row,$id,$penggajian_month,$penggajian_year,$project,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}




	public function getPayrollReport_pdf()
	{
		$this->load->library('html_pdf');
		$this->load->helper('global');


		
		//if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			
			

		    $sql = "
		        select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
		    ";

		    $data = $this->db->query($sql)->result();

		    if(!empty($data)){
		    	$pdfData = [
				    'periode_bulan'      		=> $data[0]->periode_bulan_name,
				    'periode_tahun'      		=> $data[0]->periode_tahun,
				    'nik'    					=> $data[0]->emp_code,
				    'emp_name'       			=> $data[0]->full_name,
				    'project_name'    			=> $data[0]->project_name,
				    'jabatan' 		  			=> $data[0]->job_title_name,
				    'tanggal_pembayaran_lembur'	=> $data[0]->tanggal_pembayaran_lembur
				];



				$pdfBinary = $this->html_pdf->render_to_string_portrait(
			        'pdf/gaji_os',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=gaji_os.pdf");
			    echo $pdfBinary;
			    exit;

		    }else{
		    	echo "Slip Gaji tidak ditemukan"; 
		    }

		/*}else{
			echo "Invoice tidak ditemukan"; 
		}*/
	    
	    
	}



	public function getOvertimeReport_pdf()
	{
		$this->load->library('html_pdf');
		$this->load->helper('global');


		
		//if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			
			

		    $sql = "
		        select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
		    ";

		    $data = $this->db->query($sql)->result();

		    if(!empty($data)){
		    	$pdfData = [
				    'periode_bulan'      		=> $data[0]->periode_bulan_name,
				    'periode_tahun'      		=> $data[0]->periode_tahun,
				    'nik'    					=> $data[0]->emp_code,
				    'emp_name'       			=> $data[0]->full_name,
				    'project_name'    			=> $data[0]->project_name,
				    'jabatan' 		  			=> $data[0]->job_title_name,
				    'tanggal_pembayaran_lembur'	=> $data[0]->tanggal_pembayaran_lembur
				];



				$pdfBinary = $this->html_pdf->render_to_string_portrait(
			        'pdf/lembur_os',
			        $pdfData
			    );

			    if (ob_get_level()) ob_end_clean();

			    header("Content-Type: application/pdf");
			    header("Content-Disposition: attachment; filename=lembur_os.pdf");
			    echo $pdfBinary;
			    exit;

		    }else{
		    	echo "Report Lembur tidak ditemukan"; 
		    }

		/*}else{
			echo "Invoice tidak ditemukan"; 
		}*/
	    
	    
	}


}
