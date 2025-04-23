<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ijin_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "ijin_menu"; // identify menu
 	const  LABELMASTER				= "Menu Ijin Karyawan";
 	const  LABELFOLDER				= "time_attendance"; // module folder
 	const  LABELPATH				= "ijin_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "time_attendance"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Employee Name","Date Leave Start","Date Leave End","Leave Type","Description","Total Leave", "Status"];

	
	/* Export */
	public $colnames 				= ["ID","Employee Name","Date Leave Start","Date Leave End","Leave Type","Description","Total Leave", "Status"];
	public $colfields 				= ["id","id","id","id","id","id","id","id"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		
		$msemp 					= $this->db->query("select * from employees")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');
		$msleave 				= $this->db->query("select * from master_leaves")->result(); 
		$field['selleavetype'] 	= $this->self_model->return_build_select2me($msleave,'','','','leave_type','leave_type','','','id','name',' ','','','',3,'-');
		$field['txtreason']		= $this->self_model->return_build_txtarea('','reason','reason');
		$field['txtdatestart']	= $this->self_model->return_build_txt('','date_start','date_start');
		$field['txtdateend']	= $this->self_model->return_build_txt('','date_end','date_end');


		
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


 	public function getDataSisaCuti(){
		$post = $this->input->post(null, true);
		$empid = $post['employee'];

		$rs =  $this->self_model->get_data_sisa_cuti_byEmp($empid);
		

		echo json_encode($rs);
	}

	public function rejectIjin(){
		$post = $this->input->post(null, true);
		$id = $post['id'];

		if($id != ''){

			$leave = $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 

			$data = [
				'status_approval' 	=> 3,
				'date_approval'		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('leave_absences', $data, "id = '".$id."'");

			if($leave[0]->masterleave_id != 2){ // tipenya bukan unpaid leave maka jatah cuti dikembalikan
				//penambahan cuti
				$jatahcuti 			= $this->db->query("select * from total_cuti_karyawan where employee_id = '".$leave[0]->employee_id."' and status = 1 order by period_start asc")->result(); 
				$jml_tambahan_cuti 	= $leave[0]->total_leave;
				$sisa_cuti_1 		= $jatahcuti[0]->sisa_cuti+$jml_tambahan_cuti;

				$tambah_selanjutnya=0;
				if($sisa_cuti_1 > 12){
					$tambah_selanjutnya = 1;
					$slot_tambah 		= 12- $jatahcuti[0]->sisa_cuti;
					$sisa_slot_tambah 	= $jml_tambahan_cuti-$slot_tambah;
					$sisa_cuti_1 		= 12;
				}
				$data2 = [
					'sisa_cuti' 	=> $sisa_cuti_1,
					'updated_date'	=> date("Y-m-d H:i:s")
				];
				$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

				if($tambah_selanjutnya == 1){
					$sisa_cuti_2 = $jatahcuti[1]->sisa_cuti+$sisa_slot_tambah;
					if($sisa_cuti_2 > 12){
						$sisa_cuti_2 = 12;
					}

					$data3 = [
						'sisa_cuti' 	=> $sisa_cuti_2,
						'updated_date'	=> date("Y-m-d H:i:s")
					];
					$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
				}
			}
			
		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}

	public function approveIjin(){
		$post = $this->input->post(null, true);
		$id = $post['id'];

		if($id != ''){
			$data = [
				'status_approval' 	=> 2,
				'date_approval'		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('leave_absences', $data, "id = '".$id."'");

			if($rs){
				$leaves = $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 
				$total_leave = $leaves[0]->total_leave;
				
				$employees = $this->db->query("select * from employees where id = '".$leaves[0]->employee_id."' ")->result(); 

				$time_in 	= "";
				$time_out 	= "";
				if($employees[0]->shift_type == 'Reguler'){
					$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
					$time_in 	= $dt[0]->time_in;
					$time_out 	= $dt[0]->time_out;
				}
				
				
				$date_att = $leaves[0]->date_leave_start;

				for ($i=0; $i < $total_leave; $i++) { 


					$data2 = [
						'date_attendance' 			=> $date_att,
						'employee_id' 				=> $leaves[0]->employee_id,
						'attendance_type' 			=> $employees[0]->shift_type,
						'time_in' 					=> $time_in,
						'time_out' 					=> $time_out,
						'date_attendance_in' 		=> $date_att,
						'date_attendance_out'		=> $date_att,
						'created_at'				=> date("Y-m-d H:i:s"),
						'leave_type' 				=> $leaves[0]->masterleave_id
					];
					$this->db->insert("time_attendances", $data2);

					
					$date_att = date("Y-m-d", strtotime($date_att.'+ 1 days'));

				}
			}
			
			return $rs;

		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}

 	

}
