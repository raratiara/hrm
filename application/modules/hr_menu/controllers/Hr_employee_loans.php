<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_employee_loans extends MY_Controller
{ 
	/* Module */
 	const  LABELMODULE				= "hr_employee_loans"; // identify menu
 	const  LABELMASTER				= "Pinjaman Karyawan";
 	const  LABELFOLDER				= "hr_menu"; // module folder
 	const  LABELPATH				= "hr_employee_loans"; // controller file (lowercase)
 	const  LABELNAVSEG1				= ""; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= ""; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Nama Karyawan","Nominal","Tenor","Sisa Tenor","Bunga","Cicilan","Start Angsuran","Status"];
	
	/* Export */
	public $colnames 				= ["ID","Nama Karyawan","Nominal","Tenor","Sisa Tenor","Bunga","Cicilan","Start Angsuran"];
	public $colfields 				= ["id","id_employee","nominal_pinjaman","tenor","sisa_tenor","bunga_per_bulan", "nominal_cicilan_per_bulan","date_start_cicilan"];


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

		$bunga = 0;

		$oKaryawan 									= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); 
		$field['seloPic'] 							= $this->self_model->return_build_select2me($oKaryawan,'','','','id_employee','id_employee','','','id','full_name',' ','','','disabled',3,'-');

		$field['txt_nominal_pinjaman']				= $this->self_model->return_build_txt('','nominal_pinjaman','nominal_pinjaman','','','readonly');
		$field['txt_tenor'] 						= $this->self_model->return_build_txt('','tenor','tenor','','','readonly');
		$field['txt_sisa_tenor'] 					= $this->self_model->return_build_txt('','sisa_tenor','sisa_tenor');
		$field['txt_bunga_per_bulan'] 				= $this->self_model->return_build_txt($bunga,'bunga_per_bulan','bunga_per_bulan','','','readonly');
		$field['txt_nominal_cicilan_per_bulan']  	= $this->self_model->return_build_txt('','teks_nominal_cicilan_per_bulan','teks_nominal_cicilan_per_bulan','','','readonly');
		$field['txt_date_pengajuan'] 				= $this->self_model->return_build_txtdate('','date_pengajuan','date_pengajuan','','','disabled');
		$field['txt_date_persetujuan'] 				= $this->self_model->return_build_txtdate('','date_persetujuan','date_persetujuan','','','disabled');
		$field['txt_date_pencairan'] 				= $this->self_model->return_build_txtdate('','date_pencairan','date_pencairan','','','disabled');
		$field['txt_date_start_cicilan'] 			= $this->self_model->return_build_txtdate('','date_start_cicilan','date_start_cicilan','','','disabled');

		$msStatusLoan 								= $this->db->query("select * from master_status_loan where id in ('5','6')")->result(); 
		$field['selStatus'] 						= $this->self_model->return_build_select2me($msStatusLoan,'','','','status','status','','','id','name',' ','','','',3,'-');


  
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
		define('_USER_ACCESS_LEVEL_IMPORT',0);
		define('_USER_ACCESS_LEVEL_EKSPORT',0);
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
			$rs = $this->db->update('loan', $data, "id = '".$id."'");
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
					'status_id' 	=> 4, //Menunggu Pencairan 
					'approval_date'	=> date("Y-m-d H:i:s"),
					'date_persetujuan'	=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->update('loan', $data, "id = '".$id."'");
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


 	public function getMaxApproval($trx_id){ 
		
		$approval_matrix_type_id = 9; //Loan
		$rs =  $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a 
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = ".$trx_id." 
				order by b.approval_level desc limit 1 ")->result();
		

		return $rs[0]->approval_level;
	}


	public function getCurrApproval($trx_id, $approval_level){

		$approval_matrix_type_id = 9; //Loan
		$rs =  $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();
		

		return $rs;
	}



	public function pencairan(){
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		$post = $this->input->post(null, true);
		$id = $post['id'];
		$date_pencairan = date("Y-m-d H:i:s");
		$date_start_cicilan = date("Y-m-d", strtotime("+1 month", strtotime($date_pencairan)));
		

		if($id != ''){
			$dataLoan 	= $this->db->query("select * from loan where id = '".$id."'")->result();
			$tenor 		= $dataLoan[0]->tenor;

			$data = [
				'status_id' 			=> 5, //Pinjaman Berjalan
				'date_pencairan'		=> $date_pencairan,
				'date_start_cicilan'	=> $date_start_cicilan
			];
			$rs = $this->db->update('loan', $data, "id = '".$id."'");
			if($rs){
				for($i=1; $i<=$tenor; $i++){
					$tgl_jatuh_tempo = date("Y-m-d", strtotime("+".$i." month", strtotime($date_pencairan)));

					$data_detail = [
						'loan_id' 	 		=> $id,
						'cicilan_ke' 		=> $i, 
						'tgl_jatuh_tempo' 	=> $tgl_jatuh_tempo,
						'status' 			=> 'Belum'
					];

					$this->db->insert('loan_detail', $data_detail);
				}
			}
			
		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}


	public function getApprovalLog() {
	    $post = $this->input->post(null, true);
	    $id = $post['id'];
	    $approval_matrix_type_id = 9; //Loan



	    $dataLoan = $this->db->query("select * from loan where id = '".$id."'")->result(); 
	   

	   
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

	        if($dataLoan[0]->status_id == '4'){ ///Menunggu Pencairan
		    	$dt .= '<tr>';
	            $dt .= '<td></td>';
	            $dt .= '<td>HR / Finance</td>';
	            $dt .= '<td>Menunggu Pencairan</td>';
	            $dt .= '<td></td>';
	            $dt .= '</tr>';
		    }
		    else if($dataLoan[0]->status_id == '5'){ ///Pinjaman Berjalan (sudah dicairkan)
		    	$dt .= '<tr>';
	            $dt .= '<td></td>';
	            $dt .= '<td>HR / Finance</td>';
	            $dt .= '<td>Pencairan Berhasil</td>';
	            $dt .= '<td>'.$dataLoan[0]->date_pencairan.'</td>';
	            $dt .= '</tr>';
		    }

	    } else {
	        $dt .= '<tr><td colspan="4" class="text-center text-muted">No data</td></tr>';
	    }

	    echo json_encode(['html' => $dt]);
	}


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
 
 	
}
