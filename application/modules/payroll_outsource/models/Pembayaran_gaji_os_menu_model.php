<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_gaji_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/pembayaran_gaji_os_menu";
 	protected $table_name 				= _PREFIX_TABLE."payroll_paid_history";
 	protected $primary_key 				= "id";

	function __construct()
	{
		parent::__construct();
	}

	// fix
	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'dt.id',
			'dt.payroll_slip_id',
			'dt.project_name',
			'dt.periode_penggajian',
			'dt.periode_absensi',
			'dt.status_payroll'
		];

		$where_project = "";
			if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and b.project_id = '".$_GET['flproject']."' ";
		}

		

		$sIndexColumn = $this->primary_key;
		

		$sTable = '(select a.id, a.payroll_slip_id, c.project_name, concat(d.name_indo," ",
					b.tahun_penggajian) as periode_penggajian,concat(b.tgl_start_absen," s/d ",b.tgl_end_absen) as periode_absensi, e.name as status_payroll  
					from payroll_paid_history a 
					left join payroll_slip b on b.id = a.payroll_slip_id
					left join project_outsource c on c.id = b.project_id
					left join master_month d on d.id = b.bulan_penggajian
					left join master_payroll_status e on e.id = a.status
					where 1=1 '.$where_project.'
			)dt';
		

		/* Paging */
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".
			($_GET['iDisplayLength']);
		}

		/* Ordering */
		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$srcCol = $aColumns[ intval($_GET['iSortCol_'.$i])];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sOrder .= trim($pieces[0])."
						".($_GET['sSortDir_'.$i]) .", ";
					} else {
						$sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
						".($_GET['sSortDir_'.$i]) .", ";
					}
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if($sOrder == "ORDER BY"){
				$sOrder = "";
			}
		}

		/* Filtering */
		$sWhere = " WHERE 1 = 1 ";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere .= "AND (";
			foreach ($aColumns as $c) {
				if($c !== NULL){
					$srcCol = $c;
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." LIKE '%".($_GET['sSearch'])."%' OR ";
					} else {
						$sWhere .= $c." LIKE '%".($_GET['sSearch'])."%' OR ";
					}
				}
			}

			$sWhere = substr_replace( $sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		for($i=0 ; $i<count($aColumns) ; $i++) {
			if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && isset($_GET['sSearch_'.$i]) && $_GET['sSearch_'.$i] != ''){
				if($sWhere == ""){
					$sWhere = "WHERE ";
				} else {
					$sWhere .= " AND ";
				}
				$srcString = $_GET['sSearch_'.$i];
				$findme   = '|';
				$pos = strpos($srcString, $findme);
				if ($pos !== false) {
					$srcKey = "";
					$pieces = explode($findme, trim($srcString));
					foreach ($pieces as $value) {
						if(!empty($srcKey)){
							$srcKey .= ",";
						}
						$srcKey .= "'".$value."'";
					}
					
					$srcCol = $aColumns[$i];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." IN (".$srcKey.") ";
					} else {
						$sWhere .= $aColumns[$i]." IN (".$srcKey.") ";
					}
				} else {
					$srcCol = $aColumns[$i];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." LIKE '%".($srcString)."%' ";
					} else {
						$sWhere .= $aColumns[$i]." LIKE '%".($srcString)."%' ";
					}
				}
			}
		}

		/* Get data to display */
		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']); // Filtering NULL value
		$sQuery = "
		SELECT  SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $filtered_cols))."
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit
		";
		# echo $sQuery;exit;
		$rResult = $this->db->query($sQuery)->result();

		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS() AS filter_total
		";
		$aResultFilterTotal = $this->db->query($sQuery)->row();
		$iFilteredTotal = $aResultFilterTotal->filter_total;

		/* Total data set length */
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.") AS total
			FROM $sTable
		";
		$aResultTotal = $this->db->query($sQuery)->row();
		$iTotal = $aResultTotal->total;

		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

		foreach($rResult as $row)
		{
			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			
			
			
            $daftar_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getDaftarGajiOS('."'".$row->payroll_slip_id."'".')">
                <i class="fa fa-download"></i>
                Daftar Gaji
            </a>';
          


			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
				'.$daftar_gaji.'
				'.$detail.'
				'.$edit.'
				'.$delete.'
				</div>',
				$row->id,
				$row->project_name,
				$row->periode_penggajian,
				$row->periode_absensi,
				$row->status_payroll
				
			));


		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val){
		return !is_null($val);
	}		

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			//$this->db->trans_off(); // Disable transaction
			$this->db->trans_start(); // set "True" for query will be rolled back
			$this->db->where([$this->primary_key => $id])->delete($this->table_name);
			$this->db->trans_complete();

			return $rs = $this->db->trans_status();
		} else return null;
	}  

	// delete multi items action
	public function bulk($id= "") {
		if (is_array($id) && count($id)) {
			$err = '';
			foreach ($id as $pid) {
				//$this->db->trans_off(); // Disable transaction
				$this->db->trans_start(); // set "True" for query will be rolled back
				$this->db->where([$this->primary_key => $pid])->delete($this->table_name);
				$this->db->trans_complete();
				$deleted = $this->db->trans_status();
                if ($deleted == false) {
					if(!empty($err)) $err .= ", ";
                    $err .= $pid;
                }
			}
			
			$data = array();
			if(empty($err)){
				$data['status'] = TRUE;
			} else {
				$data['status'] = FALSE;
				$data['err'] = '<br/>ID : '.$err;
			}
			
			return $data;
		} else return null;
	}  

	/*public function ceil_2($number){
	    return ceil($number * 100) / 100;
	}*/



	public function add_data($post) { 
		
  		$data_payroll_hist = $this->db->query("select * from payroll_paid_history where payroll_slip_id = '".$post['periode_gaji']."'  ")->result(); 

  		if(empty($data_payroll_hist)){ 
  			
  			$data = [
				'payroll_slip_id' 	=> trim($post['periode_gaji']),
				'status' 			=> 1, //menunggu pembayaran
				'created_at'		=> date("Y-m-d H:i:s"),
				'created_by'		=> $_SESSION['worker']
			];
			$rs = $this->db->insert($this->table_name, $data);
			
			if($rs){
				

				return [
				    "status" => true,
				    "msg" => "Data berhasil disimpan"
				];
				
			}else{
				return [
				    "status" => false,
				    "msg" 	 => "Data gagal disimpan"
				];
			}

			

  		}else{
  			return [
			    "status" => false,
			    "msg" 	 => "Data gagal disimpan. Data History sudah ada"
			];
  		}

		
	}  


	public function edit_data($post) { 

		if(!empty($post['id'])){

			$itemData = [	
				'status' => trim($post['status'])
			];

			$rs = $this->db->update("payroll_paid_history", $itemData, "id = '".$post['id']."'");
			if($rs){
				$getPayroll = $this->db->query("select * from payroll_paid_history where id = '".$post['id']."' ")->result();

				$itemData2 = [	
					'status' => trim($post['status'])
				];

				$this->db->update("payroll_slip", $itemData2, "id = '".$getPayroll[0]->payroll_slip_id."'");

				return [
				    "status" => true,
				    "msg" => "Data berhasil disimpan"
				];
				
			}else{
				return [
				    "status" => false,
				    "msg" 	 => "Data gagal disimpan"
				];
			}

		}else{
			return [
			    "status" => false,
			    "msg" 	 => "ID tidak ditemukan"
			];
		}
	} 


	public function getRowData($id) { 

		
		$mTable = '(select a.*, c.project_name, concat(d.name_indo," ",
					b.tahun_penggajian) as periode_penggajian,concat(b.tgl_start_absen," s/d ",b.tgl_end_absen) as periode_absensi, e.name as status_payroll, b.project_id, b.tgl_start_absen, b.tgl_end_absen
					from payroll_paid_history a 
					left join payroll_slip b on b.id = a.payroll_slip_id
					left join project_outsource c on c.id = b.project_id
					left join master_month d on d.id = b.bulan_penggajian
					left join master_payroll_status e on e.id = a.status
					
					
			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'employee_id' 			=> $v["B"],
				'task' 					=> $v["C"],
				'progress_percentage' 	=> $v["D"],
				'parent_id' 			=> $v["E"],
				'due_date' 				=> $v["F"],
				'status_id' 			=> $v["G"],
				'solve_date' 			=> $v["H"]
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{

		$sql = "select a.*, b.name_indo as month_name, c.project_name 
					from payroll_slip a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
					where 1=1 
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	
	public function getDataPeriodeGaji($project){ 

		$rs = $this->db->query("select a.*, concat(b.name_indo,' ',a.tahun_penggajian) as periode_penggajian
								from payroll_slip a
								left join master_month b on b.id = a.bulan_penggajian
								where project_id = ".$project." ")->result(); 

		$data['msperiode'] = $rs;


		return $data;

	}


	public function getDataPayroll($payroll_id){ 

		$rs = $this->db->query("select * from payroll_slip
								where id = ".$payroll_id." ")->result(); 

		


		return $rs;

	}


}
