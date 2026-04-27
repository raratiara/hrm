<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_revision_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "attendance_revision_menu"; // identify menu
 	const  LABELMASTER				= "Menu Pengajuan Lupa Absen";
 	const  LABELFOLDER				= "time_attendance"; // module folder
 	const  LABELPATH				= "attendance_revision_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "time_attendance"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Employee Name","Date Attendance","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Employee Name","Date Attendance","Status"];
	public $colfields 				= ["id","full_name","date_leave_start","status"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1 && $_SESSION['role'] != 4){ //bukan super user && bukan HR admin
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}



		$field = [];
		
		$msemp 					= $this->db->query("select * from employees where status_id = 1 and emp_source = 'internal' ".$whr." order by full_name asc")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','required',3,'-');
		$field['txtattendancedate']	= $this->self_model->return_build_txt('','attendance_date','attendance_date','','','required');
		$field['txttimein']	= $this->self_model->return_build_txt('','time_in','time_in','','','readonly');
		$field['txttimeout']	= $this->self_model->return_build_txt('','time_out','time_out','','','readonly');
		$field['txtattendancein']	= $this->self_model->return_build_txt('','attendance_in','attendance_in');
		$field['txtattendanceout']	= $this->self_model->return_build_txt('','attendance_out','attendance_out');
		$field['txtdescription']		= $this->self_model->return_build_txtarea('','description','description');
		$field['txtabsencetype']	= $this->self_model->return_build_txt('','absence_type','absence_type','','','readonly');
		
		$field['attachment'] 	= $this->self_model->return_build_fileinput('attachment','attachment');
		$raw = [
		    ['id' => 'wfo', 'name' => 'WFO'],
		    ['id' => 'wfh', 'name' => 'WFH'],
		    ['id' => 'onsite', 'name' => 'On Site']
		];
		$msLoc = [];
		foreach ($raw as $row_raw) {
		    $obj = new stdClass();
		    $obj->id = $row_raw['id'];
		    $obj->name = $row_raw['name'];
		    $msLoc[] = $obj;
		}
		$field['selloc'] 			= $this->self_model->return_build_select2me($msLoc,'','','','location','location','','','id','name',' ','','','',3,'-');

		
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


 	public function getDataEmp(){
		$post = $this->input->post(null, true);

		$f_attendance_date 	= date_create($post['attendance_date']);
		$attendance_date 	= date_format($f_attendance_date,"Y-m-d");

		$empid = $post['employee'];
		

		$rs =  $this->self_model->get_data_emp($empid, $attendance_date);
		

		echo json_encode($rs);
	}



	public function rejectIjin(){
		$post = $this->input->post(null, true);
		$id = $post['id'];
		$approval_level = $post['approval_level'];

		$karyawan_id = $_SESSION['worker'];

		if($id != ''){
			$data = [
				'status_approval' 	=> 3,
				'date_approval'		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('time_attendances_revision', $data, "id = '".$id."'");

			if($rs){
				$CurrApproval = $this->getCurrApproval($id, $approval_level);
				if(!empty($CurrApproval)){
					$CurrApprovalId = $CurrApproval[0]->id;
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

		$karyawan_id = $_SESSION['worker'];

		$post = $this->input->post(null, true);
		$id = $post['id'];
		$approval_level = $post['approval_level'];

		if($id != ''){
			$maxApproval = $this->getMaxApproval($id);
			if($approval_level == $maxApproval){ //last approver
				$data = [
					'status_approval' 	=> 2,
					'date_approval'		=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->update('time_attendances_revision', $data, "id = '".$id."'");

				if($rs){
					$revision = $this->db->query("select * from time_attendances_revision where id = '".$id."' ")->result();

					if(!empty($revision)){
						$rev = $revision[0];

						// update or insert time_attendances
						$existing = $this->db->query("select id from time_attendances where date_attendance = '".$rev->date_attendance."' and employee_id = '".$rev->employee_id."' ")->result();

						$dataAtt = [
							'date_attendance' 			=> $rev->date_attendance,
							'employee_id' 				=> $rev->employee_id,
							'attendance_type' 			=> $rev->attendance_type,
							'time_in' 					=> $rev->time_in,
							'time_out' 					=> $rev->time_out,
							'date_attendance_in' 		=> $rev->date_attendance_in,
							'date_attendance_out'		=> $rev->date_attendance_out,
							'is_late'					=> $rev->is_late,
							'is_leaving_office_early' 	=> $rev->is_leaving_office_early,
							'num_of_working_hours'		=> $rev->num_of_working_hours,
							'attendance_revision_id'	=> $rev->id,
							'updated_at'				=> date("Y-m-d H:i:s")
						];

						if(!empty($existing)){
							$this->db->update("time_attendances", $dataAtt, "id = '".$existing[0]->id."'");
						}else{
							$dataAtt['created_at'] = date("Y-m-d H:i:s");
							$this->db->insert("time_attendances", $dataAtt);
						}
					}

					$CurrApproval = $this->getCurrApproval($id, $approval_level);
					if(!empty($CurrApproval)){
						$CurrApprovalId = $CurrApproval[0]->id;
						$updApproval = [
							'status' 		=> "Approved",
							'approval_by' 	=> $karyawan_id,
							'approval_date'	=> date("Y-m-d H:i:s")
						];
						$this->db->update("approval_path_detail", $updApproval, "id = '".$CurrApprovalId."'");
					}
				}

			}else{ //not last approver, move to next level
				$next_level = $approval_level+1;
				$CurrApproval = $this->getCurrApproval($id, $approval_level);

				if(!empty($CurrApproval)){
					$CurrApprovalId  = $CurrApproval[0]->id;
					$approval_path_id = $CurrApproval[0]->approval_path_id;

					$data2 = ['current_approval_level' => $next_level];
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

						$this->approvalemailservice->sendApproval('attendance_revision', $id, $approval_path_id);
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
		$approval_matrix_type_id = 13; //attendance revision
		$rs = $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = ".$trx_id."
				order by b.approval_level desc limit 1 ")->result();

		return $rs[0]->approval_level;
	}


	public function getCurrApproval($trx_id, $approval_level){
		$approval_matrix_type_id = 13; //attendance revision
		$rs = $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();

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
	    $approval_matrix_type_id = 13; //attendance revision

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
