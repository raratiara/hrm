<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjalanan_dinas_menu extends MY_Controller
{ 
	/* Module */
 	const  LABELMODULE				= "perjalanan_dinas_menu"; // identify menu
 	const  LABELMASTER				= "Menu Perjalanan Dinas";
 	const  LABELFOLDER				= "compensation_benefit"; // module folder
 	const  LABELPATH				= "perjalanan_dinas_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "compensation_benefit"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Employee Name","Destination","Start Date","End Date","Reason","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Employee Name","Destination","Start Date","End Date","Reason","Status"];
	public $colfields 				= ["id","full_name","destination","start_date","end_date","reason","status_name"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}



		$field = [];
		
		$field['txtdestination']	= $this->self_model->return_build_txt('','destination','destination');
		$field['txtreason'] 		= $this->self_model->return_build_txtarea('','reason','reason');
		$field['txtstartdate'] 		= $this->self_model->return_build_txt('','start_date','start_date');
		$field['txtenddate'] 		= $this->self_model->return_build_txt('','end_date','end_date');
		
		$msemp 					= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');

		$field['reject_reason']	= $this->self_model->return_build_txtarea('','reject_reason','reject_reason');
		$field['rfu_reason']	= $this->self_model->return_build_txtarea('','rfu_reason','rfu_reason');
		$field['txttotalamount'] 	= $this->self_model->return_build_txt('','total_amount','total_amount','','','readonly');

		$field['different_work_location'] 	= $this->self_model->return_build_radio('', [['1','Yes'],['0','No']], 'different_work_location', 'different_work_location', 'inline');
		
		$msloc 					= $this->db->query("select * from master_work_location order by name asc")->result(); 
		$field['selbustriploc'] = $this->self_model->return_build_select2me($msloc,'','','','bustrip_loc','bustrip_loc','','','id','name',' ','','','',3,'-');


		
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


 	public function reject(){
 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		$post = $this->input->post(null, true);
		$id = $post['id'];
		$approval_level = $post['approval_level'];
		$reject_reason 	= $post['reject_reason'];

		if($id != ''){

			$data = [
				'status_id' 	=> 3,
				'approval_date'	=> date("Y-m-d H:i:s"),
				'reject_reason' => $reject_reason
			];
			$rs = $this->db->update('business_trip', $data, "id = '".$id."'");
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

	public function approve(){
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		$post = $this->input->post(null, true);
		$id = $post['id'];
		$approval_level = $post['approval_level'];


		if($id != ''){
			$maxApproval = $this->getMaxApproval($id); 
			if($approval_level == $maxApproval){   //last approver
				$data = [
					'status_id' 	=> 2, 
					'approval_date'	=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->update('business_trip', $data, "id = '".$id."'");
				if($rs){
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
				}
				
			}else{
				$next_level = $approval_level+1;
			
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
					}
				}
			}
		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}


	public function rfu(){
		$post 	= $this->input->post(null, true);
		$id 	= $post['id'];
		$reason = $post['reason'];
		$approval_level = $post['approval_level'];


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		if($id != ''){

			$data = [
				'status_id' 	=> 4, //rfu
				'rfu_reason' 	=> $reason,
				'approval_date'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('business_trip', $data, "id = '".$id."'");

			if($rs){

				$CurrApproval = $this->getCurrApproval($id, $approval_level);
				if(!empty($CurrApproval)){
					$CurrApprovalId		= $CurrApproval[0]->id;
					$CurrApprovalPathId = $CurrApproval[0]->approval_path_id;
					
					$updApproval = [
						'status' 		=> "Request for Update",
						'approval_by' 	=> $karyawan_id,
						'approval_date'	=> date("Y-m-d H:i:s")
					];
					$this->db->update("approval_path_detail", $updApproval, "id = '".$CurrApprovalId."'");

				}
			}

		}else return null;

		echo json_encode($rs);

	}


	public function genbustriprow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			 
			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewBustripRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewBustripRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function delrowDetailBustrip(){ 
		$post = $this->input->post(); 
		$id = trim($post['id']); 
		
		if($id != ''){
			$rs = $this->db->delete('business_trip_detail',"id = '".$id."'");
		}
		
	}


	public function getMaxApproval($trx_id){ 
		
		$approval_matrix_type_id = 7; //Business Trip
		$rs =  $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a 
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = ".$trx_id." 
				order by b.approval_level desc limit 1 ")->result();
		

		return $rs[0]->approval_level;
	}


	public function getCurrApproval($trx_id, $approval_level){

		$approval_matrix_type_id = 7; //Business Trip
		$rs =  $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();
		

		return $rs;
	}

	public function getApprovalLog() {
	    $post = $this->input->post(null, true);
	    $id = $post['id'];
	    $approval_matrix_type_id = 7; //Business Trip

	   
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
