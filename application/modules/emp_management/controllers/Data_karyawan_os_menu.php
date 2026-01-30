<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Data_karyawan_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "data_karyawan_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu Data Karyawan OS";
 	const  LABELFOLDER				= "emp_management"; // module folder
 	const  LABELPATH				= "data_karyawan_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "emp_management"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Emp Code","FullName","NickName","Email","Phone","Gender","Date of Birth","Job Title","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Emp Code","FullName","NickName","Email","Phone","Gender","Date of Birth","Job Title","Status"];
	public $colfields 				= ["id","emp_code","full_name","nick_name","personal_email","personal_phone","gender_name", "date_of_birth","job_title_name","status_name"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		$field['txtempcode'] 			= $this->self_model->return_build_txt('','emp_code','emp_code','','','readonly');
		$field['txtfullname'] 			= $this->self_model->return_build_txt('','full_name','full_name','','','required');
		$field['txtemail'] 				= $this->self_model->return_build_txt('','email','email');
		$field['txtnationality'] 		= $this->self_model->return_build_txt('','nationality','nationality');
		$field['txttanggungan'] 		= $this->self_model->return_build_txt('','tanggungan','tanggungan');
		$field['txtsima'] 				= $this->self_model->return_build_txt('','sim_a','sim_a');
		$field['txtnonpwp'] 			= $this->self_model->return_build_txt('','no_npwp','no_npwp');
		$field['txtplaceofbirth'] 		= $this->self_model->return_build_txt('','place_of_birth','place_of_birth');
		$field['txtaddress1'] 			= $this->self_model->return_build_txtarea('','address1','address1');
		$field['txtpostalcode1'] 		= $this->self_model->return_build_txt('','postal_code1','postal_code1');
		$field['txtpostalcode2'] 		= $this->self_model->return_build_txt('','postal_code2','postal_code2');
		$field['txtdateendprob'] 		= $this->self_model->return_build_txtdate('','date_end_prob','date_end_prob');
		$field['txtworklocation'] 		= $this->self_model->return_build_txt('','work_loc','work_loc');
		$field['selindirect'] 			= $this->self_model->return_build_txt('','indirect','indirect');
		$field['txtemergencyphone'] 	= $this->self_model->return_build_txt('','emergency_phone','emergency_phone');
		$field['txtemergencyrelation'] 	= $this->self_model->return_build_txt('','emergency_relation','emergency_relation');
		$field['txtbankaddress'] 		= $this->self_model->return_build_txt('','bank_address','bank_address');
		$field['txtbankaccno'] 			= $this->self_model->return_build_txt('','bank_acc_no','bank_acc_no');
		$field['txtdateresignletter']	= $this->self_model->return_build_txtdate('','date_resign_letter','date_resign_letter');
		$field['txtdateresignactive'] 	= $this->self_model->return_build_txtdate('','date_resign_active','date_resign_active');
		$field['txtresigncategory'] 	= $this->self_model->return_build_txt('','resign_category','resign_category');
		$field['txtnickname'] 			= $this->self_model->return_build_txt('','nick_name','nick_name');
		$field['txtphone'] 				= $this->self_model->return_build_txt('','phone','phone');
		$field['txtethnic'] 			= $this->self_model->return_build_txt('','ethnic','ethnic');
		$field['txtnoktp'] 				= $this->self_model->return_build_txt('','no_ktp','no_ktp','','','required');
		$field['txtsimc'] 				= $this->self_model->return_build_txt('','sim_c','sim_c');
		$field['txtnobpjs'] 			= $this->self_model->return_build_txt('','no_bpjs','no_bpjs','','','required');
		$field['txtdateofbirth'] 		= $this->self_model->return_build_txtdate('','date_of_birth','date_of_birth');
		$field['txtaddress2'] 			= $this->self_model->return_build_txtarea('','address2','address2');
		$field['txtdateofhire'] 		= $this->self_model->return_build_txtdate('','date_of_hire','date_of_hire');
		$field['txtdatepermanent'] 		= $this->self_model->return_build_txtdate('','date_permanent','date_permanent');
		
		$field['seldirect'] 			= $this->self_model->return_build_txt('','direct','direct');
		$field['txtemergencyname'] 		= $this->self_model->return_build_txt('','emergency_name','emergency_name');
		$field['txtemergencyemail'] 	= $this->self_model->return_build_txt('','emergency_email','emergency_email');
		$field['txtbankname'] 			= $this->self_model->return_build_txt('','bank_name','bank_name');
		$field['txtbankaccname'] 		= $this->self_model->return_build_txt('','bank_acc_name','bank_acc_name');
		$field['txtresignreason'] 		= $this->self_model->return_build_txt('','resign_reason','resign_reason');
		$field['txtresignexitfeedback'] = $this->self_model->return_build_txt('','resign_exit_feedback','resign_exit_feedback');
		$field['txtempphoto'] 			= $this->self_model->return_build_fileinput('emp_photo','emp_photo');
		$field['txtempsignature'] 		= $this->self_model->return_build_fileinput('emp_signature','emp_signature');
		$field['txtgajibulanan'] 		= $this->self_model->return_build_txt('','gaji_bulanan','gaji_bulanan');
		$field['txtgajiharian'] 		= $this->self_model->return_build_txt('','gaji_harian','gaji_harian');
		$field['txtusername'] 			= $this->self_model->return_build_txt('','username','username','','','readonly');
		

		$msmaritalstatus 				= $this->db->query("select * from master_marital_status")->result(); 
		$field['selmaritalstatus'] 		= $this->self_model->return_build_select2me($msmaritalstatus,'','','','marital_status','marital_status','','','id','name',' ','','','required',3,'-');

		$msworkloc 						= array(); /*$this->db->query("select * from master_work_location_outsource")->result();*/ 
		$field['selworkloc'] 			= $this->self_model->return_build_select2me($msworkloc,'','','','work_loc','work_loc','','','id','name',' ','','','required',3,'-');

		$mseducation 					= $this->db->query("select * from master_education")->result(); 
		$field['seleducation'] 			= $this->self_model->return_build_select2me($mseducation,'','','','last_education','last_education','','','id','name',' ','','','',3,'-');

		$msprovince 					= $this->db->query("select * from provinces order by name asc")->result(); 
		$field['selprovince1'] 			= $this->self_model->return_build_select2me($msprovince,'','','','province1','province1','','','id','name',' ','','','',3,'-');
		$field['selprovince2'] 			= $this->self_model->return_build_select2me($msprovince,'','','','province2','province2','','','id','name',' ','','','',3,'-');

		/*$msregency 				= $this->db->query("select * from regencies order by name asc")->result();*/ 
		$msregency 						= array();
		$field['selregency1'] 			= $this->self_model->return_build_select2me($msregency,'','','','regency1','regency1','regency1','','id','name',' ','','','',3,'-');
		$field['selregency2'] 			= $this->self_model->return_build_select2me($msregency,'','','','regency2','regency2','regency2','','id','name',' ','','','',3,'-');

		/*$msdistrict 					= $this->db->query("select * from districts order by name asc")->result(); */
		$msdistrict 					= array();
		$field['seldistrict1'] 			= $this->self_model->return_build_select2me($msdistrict,'','','','district1','district1','district1','','id','name',' ','','','',3,'-');
		$field['seldistrict2'] 			= $this->self_model->return_build_select2me($msdistrict,'','','','district2','district2','district2','','id','name',' ','','','',3,'-');

		/*$msvillage 			= $this->db->query("select * from villages order by name asc")->result();*/
		$msvillage 						= array(); 
		$field['selvillage1'] 			= $this->self_model->return_build_select2me($msvillage,'','','','village1','village1','village1','','id','name',' ','','','',3,'-');
		$field['selvillage2'] 			= $this->self_model->return_build_select2me($msvillage,'','','','village2','village2','village2','','id','name',' ','','','',3,'-');

		$msjobtitle 					= $this->db->query("select * from master_job_title_os")->result(); 
		$field['seljobtitle'] 			= $this->self_model->return_build_select2me($msjobtitle,'','','','job_title','job_title','','','id','name',' ','','','',3,'-');

		$msdept 						= $this->db->query("select * from departments")->result(); 
		$field['seldepartment'] 		= $this->self_model->return_build_select2me($msdept,'','','','department','department','','','id','name',' ','','','',3,'-');

		$msempstatus 					= $this->db->query("select * from master_emp_status")->result(); 
		$field['selempstatus'] 			= $this->self_model->return_build_select2me($msempstatus,'','','','emp_status','emp_status','','','id','name',' ','','','',3,'-');

		$msdirect 						= $this->db->query("select * from employees")->result(); 
		$field['seldirect'] 			= $this->self_model->return_build_select2me($msdirect,'','','','direct','direct','','','id','full_name',' ','','','',3,'-');

		$msindirect 					= $this->db->query("select * from employees")->result(); 
		$field['selindirect'] 			= $this->self_model->return_build_select2me($msindirect,'','','','indirect','indirect','','','id','full_name',' ','','','',3,'-');

		$mscompany 						= $this->db->query("select * from companies")->result(); 
		$field['selcompany'] 			= $this->self_model->return_build_select2me($mscompany,'','','','company','company','','','id','name',' ','','','',3,'-');

		$msdivision 					= $this->db->query("select * from divisions")->result(); 
		$field['seldivision'] 			= $this->self_model->return_build_select2me($msdivision,'','','','division','division','','','id','name',' ','','','',1,'-');

		$msbranch 						= $this->db->query("select * from branches")->result(); 
		$field['selbranch'] 			= $this->self_model->return_build_select2me($msbranch,'','','','branch','branch','','','id','name',' ','','','',1,'-');	

		$mssection 						= $this->db->query("select * from sections")->result(); 
		$field['selsection'] 			= $this->self_model->return_build_select2me($mssection,'','','','section','section','','','id','name',' ','','','',3,'-');	

		$field['txtgender'] 			= $this->self_model->return_build_radio('', [['M','Male'],['F','Female']], 'gender', '', 'inline');

		$field['chksameaddress'] 		= $this->self_model->return_build_radio('', [['Y','Yes'],['N','No']], 'is_same_address', 'is_same_address', 'inline');

		$field['txtstatus'] 			= $this->self_model->return_build_radio('', [['1','Active'],['0','Not Active']], 'status', '', 'inline');

		$msjoblevel 					= $this->db->query("select * from master_job_level")->result(); 
		$field['seljoblevel'] 			= $this->self_model->return_build_select2me($msjoblevel,'','','','job_level','job_level','','','id','name',' ','','','',1,'-');

		$msgrade 					= $this->db->query("select * from master_grade")->result(); 
		$field['selgrade'] 			= $this->self_model->return_build_select2me($msgrade,'','','','grade','grade','','','id','name',' ','','','',1,'-');

		$field['txtshifttype'] 			= $this->self_model->return_build_radio('', [['Reguler','Reguler'],['Shift','Shift']], 'shift_type', '', 'inline');

		$field['txtfotoktp'] 			= $this->self_model->return_build_fileinput('foto_ktp','foto_ktp');
		$field['txtfotonpwp'] 			= $this->self_model->return_build_fileinput('foto_npwp','foto_npwp');
		$field['txtfotobpjs'] 			= $this->self_model->return_build_fileinput('foto_bpjs','foto_bpjs');
		$field['txtfotosima'] 			= $this->self_model->return_build_fileinput('foto_sima','foto_sima');
		$field['txtfotosimc'] 			= $this->self_model->return_build_fileinput('foto_simc','foto_simc');
		
		$field['txtistracking'] 		= $this->self_model->return_build_radio('', [['1','Always'],['2','Working hours'],['0','No']], 'is_tracking', '', 'inline');


		$field['txtempsource'] 			= $this->self_model->return_build_radio('outsource', [['internal','Internal','disabled'],['outsource','Outsource','disabled']], 'emp_source', '', 'inline');
		$field['txtstartpkwt'] 			= $this->self_model->return_build_txtdate('','start_pkwt','start_pkwt');
		$field['txtendpkwt'] 			= $this->self_model->return_build_txtdate('','end_pkwt','end_pkwt');
		$mscust 						= $this->db->query("select * from data_customer order by name asc")->result(); 
		$field['selcustomer'] 			= $this->self_model->return_build_select2me($mscust,'','','','customer','customer','','','id','name',' ','','','required',1,'-');
		$msproject 						= array();
		$field['selproject'] 			= $this->self_model->return_build_select2me($msproject,'','','','project','project','project','','id','project_name',' ','','','required',1,'-');
		$field['txtttlharikerja'] 		= $this->self_model->return_build_txt('','ttl_hari_kerja','ttl_hari_kerja');
		$field['txtstatusbpjskes'] 		= $this->self_model->return_build_radio('', [['ditanggung_pribadi','Tidak'],['ditanggung_perusahaan','Ya']], 'status_bpjs_kes', '', 'inline');
		$field['txtstatusbpjsket'] 		= $this->self_model->return_build_radio('', [['ditanggung_pribadi','Tidak'],['ditanggung_perusahaan','Ya']], 'status_bpjs_ket', '', 'inline');
		$field['txtnobpjs_ketenagakerjaan'] = $this->self_model->return_build_txt('','no_bpjs_ketenagakerjaan','no_bpjs_ketenagakerjaan','','','required');
		$field['txtfotobpjs_ketenagakerjaan'] = $this->self_model->return_build_fileinput('foto_bpjs_ketenagakerjaan','foto_bpjs_ketenagakerjaan');
		
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


 	public function genexpensesrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewExpensesRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewExpensesRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function gentrainingrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewTrainingRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewTrainingRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function genorgrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewOrgRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewOrgRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function genworkexprow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewWorkexpRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewWorkexpRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function delrowDetailEdu(){ 
		$post = $this->input->post(); 
		$id = trim($post['id']); 
		
		if($id != ''){
			$rs = $this->db->delete('cctv',"id = '".$id."'");
		}
		
	}


	public function getDataDistrict(){
		$post 		= $this->input->post(null, true);
		$province 	= $post['province'];
		$regency 	= $post['regency'];

		$rs =  $this->self_model->getDataDistrict($province,$regency);
		

		echo json_encode($rs);
	}

	public function getDataRegency(){
		$post 		= $this->input->post(null, true);
		$province 	= $post['province'];

		$rs =  $this->self_model->getDataRegency($province);
		

		echo json_encode($rs);
	}

	public function getDataVillage(){
		$post 		= $this->input->post(null, true);
		$province 	= $post['province'];
		$regency 	= $post['regency'];
		$district 	= $post['district'];

		$rs =  $this->self_model->getDataVillage($province,$regency,$district);
		

		echo json_encode($rs);
	}




	 public function fungsi_upload_excel() {
	 	

        if ($_FILES['fileexcel']['name']) { 
            $file = $_FILES['fileexcel'];
            $path = FCPATH . 'uploads/' . $file['name']; 
            move_uploaded_file($file['tmp_name'], $path);
            

            try { 
            	/*error_reporting(E_ALL);
				ini_set('display_errors', 1);
				echo 'Path: ' . $path . '<br>';
				echo 'File exists? ' . (file_exists($path) ? 'yes' : 'no') . '<br>';
				echo 'Size: ' . filesize($path) . ' bytes<br>';*/


                $spreadsheet = IOFactory::load($path); 
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                // Lewati header (baris ke-0)
                for ($i = 1; $i < count($sheetData); $i++) {
                    $row = $sheetData[$i];
                    $data[] = [
                        'employee_id' => $row[0],
                        'task' => $row[1],
                        /*'task' => (int)$row[1]*/
                    ];
                }

                // Insert ke database
                if (!empty($data)) { 
                	/*$rs = $this->db->insert($this->table_name, $data);*/
                    $this->db->insert_batch('tasklist', $data);
                    $this->session->set_flashdata('sukses', 'Data berhasil diimport!');
                }

            } catch (Exception $e) {
                $this->session->set_flashdata('gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            }

            redirect('emp_management/data_karyawan_os_menu');
        }
    }


    public function getDataProject(){
		$post 		= $this->input->post(null, true);
		$customer 	= $post['customer'];

		$rs =  $this->self_model->getDataProject($customer);
		

		echo json_encode($rs);
	}



	public function getDataWorkLocation(){
		$post 		= $this->input->post(null, true);
		$project 	= $post['project'];

		$rs =  $this->self_model->getDataWorkLocation($project);
		

		echo json_encode($rs);
	}



}
