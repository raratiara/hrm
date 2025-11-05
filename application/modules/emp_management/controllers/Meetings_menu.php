<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meetings_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "meetings_menu"; // identify menu
 	const  LABELMASTER				= "Menu Meeting";
 	const  LABELFOLDER				= "emp_management"; // module folder
 	const  LABELPATH				= "meetings_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "emp_management"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Meeting Name","Meeting Date","Meeting Time","Meeting Room","Status","Booking Date","Booking By","Description","Participants"];

	
	/* Export */
	public $colnames 				= ["ID","Meeting Name","Meeting Date","Meeting Time","Meeting Room","Status","Booking Date","Booking By","Description","Participants"];
	public $colfields 				= ["id","meeting_name","meeting_date","meeting_times","room_name","status","booking_date","created_by_name","description","participants_name"];



	/* Form Field Asset */
	public function form_field_asset()
	{
		
		$field = [];
	
		
		$msroom 					= $this->db->query("select * from master_meeting_room order by room_name asc")->result(); 
		$field['selmeetingroom'] 	= $this->self_model->return_build_select2me($msroom,'','','','meeting_room','meeting_room','','','id','room_name',' ','','','',3,'-');

		$msemp 						= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$field['selparticipants'] 	= $this->self_model->return_build_select2me($msemp,'multiple','','','participants[]','participants','','','id','full_name',' ','','','',3,'-');

		$field['txtdesc'] 			= $this->self_model->return_build_txtarea('','description','description');
		$field['txtmeetingname'] 	= $this->self_model->return_build_txt('','meeting_name','meeting_name');
		$field['txtmeetingdate'] 	= $this->self_model->return_build_txt('','meeting_date','meeting_date');
		$field['seltimes'] 			= $this->self_model->return_build_radio('', [['full day','Full Day'],['custom','Custom']], 'type', '', 'inline');
		$field['txtstarttime'] 		= $this->self_model->return_build_txt('','start_time','start_time');
		$field['txtendtime'] 		= $this->self_model->return_build_txt('','end_time','end_time');
		$field['txtcode'] 			= $this->self_model->return_build_txt('','code','code','','','readonly');
		$field['txtcode_checkin'] 	= $this->self_model->return_build_txt('','code_checkin','code_checkin');
		


		
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


 	public function cancel(){
 		
		$post = $this->input->post(null, true);
		$id = $post['id'];
		

		if($id != ''){

			$data = [
				'status' 		=> 'cancelled',
				'cancel_time'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('meetings', $data, "id = '".$id."'");
			
		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}


	public function checkin(){
		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;
 		
		$post = $this->input->post(null, true);
		$id = $post['id'];
		$code_checkin = $post['code_checkin'];
		

		if($id != '' && $code_checkin != ''){ 
			$cekdata = $this->db->query("select * from meetings where id = '".$id."'")->result();
			if(date("Y-m-d H:i:s") <= $cekdata[0]->expired_time){
				if($cekdata[0]->code == $code_checkin){
					if($cekdata[0]->status == 'booked'){
						$data = [
							'status' 		=> 'check in',
							'check_in_time'	=> date("Y-m-d H:i:s"),
							'check_in_by'	=> $karyawan_id
						];
						$this->db->update('meetings', $data, "id = '".$id."'");
					}
					
					// add absensi kehadiran meeting
					$data_ins = [
						'meetings_id' 	=> $id,
						'checkin_time'	=> date("Y-m-d H:i:s"),
						'employee_id'	=> $karyawan_id
					];
					$rs = $this->db->insert('presensi_meeting', $data_ins);
					
				}else{
					$rs='code not valid';
				}
			}else{
				$rs='the check-in time has expired';
			}
			
		}else{
			$rs='id / code not found';
		}

		echo json_encode($rs);

	}


	public function checkout(){
		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;
 		
		$post = $this->input->post(null, true);
		$id = $post['id'];
		
		

		if($id != ''){ 
			$data = [
				'status' 		=> 'completed',
				'check_out_time'=> date("Y-m-d H:i:s"),
				'check_out_by'	=> $karyawan_id
			];
			$rs = $this->db->update('meetings', $data, "id = '".$id."'");
			
		}else{
			$rs='id not found';
		}

		echo json_encode($rs);

	}




}
