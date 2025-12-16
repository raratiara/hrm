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
	public $colfields 				= ["id","full_name","date_leave_start","date_leave_end","leave_name","reason","total_leave","status"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}



		$field = [];
		
		$msemp 					= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');
		$msleave 				= $this->db->query("select * from master_leaves where name != 'Absence' ")->result(); 
		$field['selleavetype'] 	= $this->self_model->return_build_select2me($msleave,'','','','leave_type','leave_type','','','id','name',' ','','','',3,'-');
		$field['txtreason']		= $this->self_model->return_build_txtarea('','reason','reason');
		$field['txtdatestart']	= $this->self_model->return_build_txt('','date_start','date_start');
		$field['txtdateend']	= $this->self_model->return_build_txt('','date_end','date_end');
		$field['attachment'] 	= $this->self_model->return_build_fileinput('attachment','attachment');


		
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
		$approval_level = $post['approval_level'];


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		if($id != ''){

			$leave = $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 

			$data = [
				'status_approval' 	=> 3,
				'date_approval'		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('leave_absences', $data, "id = '".$id."'");

			/*if($leave[0]->masterleave_id != 2){ // tipenya bukan unpaid leave maka jatah cuti dikembalikan
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
			}*/

			if($rs){
				$CurrApproval = $this->getCurrApproval($id, $approval_level);
				if(!empty($CurrApproval)){
					$CurrApprovalId		= $CurrApproval[0]->id;
					$dataapproval = [
						'status' 		=> "Rejected",
						'approval_by' 	=> $karyawan_id,
						'approval_date'	=> date("Y-m-d H:i:s")
					];
					$this->db->update("approval_path_detail", $dataapproval, "id = '".$CurrApprovalId."'");
				}
			}
			
		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}

	public function approveIjin(){
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		$post = $this->input->post(null, true);
		$id = $post['id'];
		$approval_level = $post['approval_level'];


		if($id != ''){ 
			$maxApproval = $this->getMaxApproval($id); 
			if($approval_level == $maxApproval){   //last approver
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
							/*'date_attendance_in' 		=> $date_att,
							'date_attendance_out'		=> $date_att,*/
							'created_at'				=> date("Y-m-d H:i:s"),
							'leave_type' 				=> $leaves[0]->masterleave_id,
							'leave_absences_id' 		=> $leaves[0]->id
						];
						$this->db->insert("time_attendances", $data2);

						
						$date_att = date("Y-m-d", strtotime($date_att.'+ 1 days'));

					}

					//update sisa jatah cuti
					if($leaves[0]->masterleave_id != '2'){ //unpaid leave gak update sisa cuti
						$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$leaves[0]->employee_id."' and status = 1 order by period_start asc")->result(); 

						$is_update_jatah_selanjutnya=0;
						$sisa_cuti = $jatahcuti[0]->sisa_cuti-$total_leave;

						if($total_leave > $jatahcuti[0]->sisa_cuti){ 
							$is_update_jatah_selanjutnya=1;
							$sisa_cuti = 0;
							$diff_day2 = $total_leave-$jatahcuti[0]->sisa_cuti;
							$sisa_cuti2 = $jatahcuti[1]->sisa_cuti-$diff_day2;
							
						}
						
						$data22 = [
									'sisa_cuti' 	=> $sisa_cuti,
									'updated_date'	=> date("Y-m-d H:i:s")
								];
						$this->db->update('total_cuti_karyawan', $data22, "id = '".$jatahcuti[0]->id."'");


						if($is_update_jatah_selanjutnya == 1){ 
							$data33 = [
										'sisa_cuti' 	=> $sisa_cuti2,
										'updated_date'	=> date("Y-m-d H:i:s")
									];
							$this->db->update('total_cuti_karyawan', $data33, "id = '".$jatahcuti[1]->id."'");
						}

					}



					$CurrApproval = $this->getCurrApproval($id, $approval_level);
					if(!empty($CurrApproval)){
						$CurrApprovalId		= $CurrApproval[0]->id;
						
						$updApproval = [
							'status' 		=> "Approved",
							'approval_by' 	=> $karyawan_id,
							'approval_date'	=> date("Y-m-d H:i:s")
						];
						$this->db->update("approval_path_detail", $updApproval, "id = '".$CurrApprovalId."'");
					}




					//return $rs;
				}//else return null;

			}else{  
				$next_level = $approval_level+1;
				//$nextApproval = getNextApproval($id, $next_level);
				$CurrApproval = $this->getCurrApproval($id, $approval_level);
				
				if(!empty($CurrApproval)){
					$CurrApprovalId		= $CurrApproval[0]->id;
					$approval_path_id	= $CurrApproval[0]->approval_path_id;

					$data2 = [
						'current_approval_level' => $next_level
					];
					$rs = $this->db->update("approval_path", $data2, "id = '".$approval_path_id."'");
					
					if($rs){
						$data = [
							'status' 		=> "Approved",
							'approval_by' 	=> $karyawan_id,
							'approval_date'	=> date("Y-m-d H:i:s")
						];
						$this->db->update("approval_path_detail", $data, "id = '".$CurrApprovalId."'");

						$dataApprovalDetail = [
							'approval_path_id' 	=> $approval_path_id, 
							'approval_level' 	=> $next_level
						];
						$this->db->insert("approval_path_detail", $dataApprovalDetail);

						// send emailing to approver
						$this->approvalemailservice->sendApproval('leave_absences', $id, $approval_path_id);
								
					}
				}
			}

		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}


	/*public function getNextApproval($trx_id, $next_level){
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 1;
		$rs =  $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a 
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = '".$trx_id."' and b.approval_level = ".$next_level." ")->result();
		

		return $rs;
	}*/


	public function getMaxApproval($trx_id){ 
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 1;
		$rs =  $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a 
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = ".$trx_id." 
				order by b.approval_level desc limit 1 ")->result();
		

		return $rs[0]->approval_level;
	}


	public function getCurrApproval($trx_id, $approval_level){
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 1;
		$rs =  $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();
		

		return $rs;
	}


	
	/*public function getDataApprovalPath(){
		$post 	= $this->input->post(null, true);
		$id 	= $post['id'];

		$approval_matrix_type_id = 1;
		$rs =  $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a 
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = ".$id." order by b.approval_level asc")->result();
		

		echo json_encode($rs);
	}*/


	public function getApprovalLog() {
	    $post = $this->input->post(null, true);
	    $id = $post['id'];
	    $approval_matrix_type_id = 1;

	    /*$query = "
	        select b.*, a.approval_matrix_id, c.role_id, d.role_name,
	        IF(b.status != '', (SELECT full_name FROM employees WHERE id = b.approval_by), d.role_name) AS approver_name
	        FROM approval_path a
	        LEFT JOIN approval_path_detail b ON b.approval_path_id = a.id
	        LEFT JOIN approval_matrix_detail c ON c.approval_matrix_id = a.approval_matrix_id AND c.approval_level = b.approval_level
	        LEFT JOIN approval_matrix_role d ON d.id = c.role_id
	        WHERE a.approval_matrix_type_id = ".$approval_matrix_type_id." AND a.trx_id = '".$id."'
	    ";*/

	    $query = "
	        select a.*, c.approval_level, d.role_name, e.id as 'id_detail',
				(case when e.id != '' and (e.status = '' or e.status is null) and a.current_approval_level = c.approval_level then 'Waiting Approval'
				when e.id != '' and e.status != '' then e.status
				else ''
				end) as status_name,
				IF(e.id != '' and e.status != '', (SELECT full_name FROM employees WHERE id = e.approval_by), d.role_name) AS approver_name,
				e.approval_date
			from approval_path a 
			left join approval_matrix b on b.id = a.approval_matrix_id
			left join approval_matrix_detail c on c.approval_matrix_id = b.id
			left join approval_matrix_role d on d.id = c.role_id
			left join approval_path_detail e on e.approval_path_id = a.id and e.approval_level = c.approval_level
				where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = '".$id."'
			order by c.approval_level asc
	    ";

	    $rs = $this->db->query($query)->result();

	    $dt = '';
	    if (!empty($rs)) {
	        foreach ($rs as $row) {
	        	$approval_date = $row->approval_date;
	        	if($row->approval_date == '0000-00-00 00:00:00' || $row->approval_date == ''){
	        		$approval_date = '';
	        	}
	        	
	            $dt .= '<tr>';
	            $dt .= '<td>'.$row->approval_level.'</td>';
	            $dt .= '<td>'.$row->approver_name.'</td>';
	            $dt .= '<td>'.$row->status_name.'</td>';
	            $dt .= '<td>'.$approval_date.'</td>';
	            $dt .= '</tr>';
	        }
	    } else {
	        $dt .= '<tr><td colspan="4" class="text-center text-muted">No data</td></tr>';
	    }

	    echo json_encode(['html' => $dt]);
	}


 	

}
