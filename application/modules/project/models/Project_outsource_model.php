<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_outsource_model extends MY_Model
{
	/* Module */
 	protected $folder_name			= "project/project_outsource";
 	protected $table_name 			= _PREFIX_TABLE."project_outsource";
 	protected $table_customer 		= _PREFIX_TABLE."data_customer"; 
 	protected $table_karyawan 		= _PREFIX_TABLE."employees";
 	protected $table_status 		= _PREFIX_TABLE."option_project_status";
 	protected $primary_key 			= "id"; 

 	
 	/* upload */
 	/*protected $attachment_folder	= "./uploads/employee";*/
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	protected $allow_size			= "0"; // 0 for limit by default php conf (in Kb)


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
			'dt.code',
			'dt.customer_name',
			'dt.lokasi', 
			'dt.jenis_pekerjaan',
			'dt.management_fee',
			'dt.periode_start',
			'dt.periode_end',
			'dt.lokasi_name',
			'dt.project_name'
			
		];

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name as customer_name, c.name as lokasi_name from project_outsource  a 
					left join data_customer b on b.id = a.customer_id 
					left join master_work_location_outsource c on c.id = a.lokasi_id
					order by a.code asc)dt';
			 


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
			

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.' 
				</div>',
				$row->id,
				$row->code,
				$row->project_name,
				$row->customer_name,
				$row->lokasi_name, 
				$row->jenis_pekerjaan,
				$row->management_fee,
				$row->periode_start,
				$row->periode_end
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
  
	public function add_data($post) { 
		$customer = (isset($post['customer']) && !empty($post['customer']))? trim($post['customer']):0; 
		$lokasi = (isset($post['lokasi']) && !empty($post['lokasi']))? trim($post['lokasi']):0;


		// CEK LOKASI BARU
    	// =========================
	    if (!empty($lokasi) && !is_numeric($lokasi)) {

	        $namaLokasi = strtoupper(trim($lokasi));

	        // cek dulu biar gak dobel
	        $cekLokasi = $this->db
	            ->where('cust_id', $customer)
	            ->where('UPPER(name)', $namaLokasi)
	            ->get('master_work_location_outsource')
	            ->row();

	        if ($cekLokasi) {
	            $lokasi = $cekLokasi->id;
	        } else {

	            $this->db->insert('master_work_location_outsource', [
	                'cust_id' => $customer,
	                'name'    => $namaLokasi
	            ]);

	            $lokasi = $this->db->insert_id();
	        }
	    }



		$dataProject = $this->db->query("select * from project_outsource where project_name = '".$post['nama_project']."' or code = '".$post['kode_project']."' ")->result();
		if(!empty($dataProject)){
			
			return [
	            "status" => false,
	            "msg"    => "Tidak dapat menyimpan data dengan Nama/Kode Project yang sama"
	        ];

		}else{

			$data = [
				'code' 				=> trim($post['kode_project']),
				'project_name' 		=> trim($post['nama_project']),
				'customer_id' 		=> $customer,
				'lokasi_id' 		=> $lokasi,
				'jenis_pekerjaan' 	=> trim($post['jenis_pekerjaan']),
				'management_fee' 	=> trim($post['management_fee']),
				'periode_start' 	=> date("Y-m-d", strtotime(trim($post['periode_start']))), 
				'periode_end' 		=> date("Y-m-d", strtotime(trim($post['periode_end'])))
				
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
		}


	}  

	public function edit_data($post) {   
		if(!empty($post['id'])){ 
			
			$customer = (isset($post['customer']) && !empty($post['customer']))? trim($post['customer']):0; 
			$lokasi = (isset($post['lokasi']) && !empty($post['lokasi']))? trim($post['lokasi']):0;


			// CEK LOKASI BARU
	    	// =========================
		    if (!empty($lokasi) && !is_numeric($lokasi)) {

		        $namaLokasi = strtoupper(trim($lokasi));

		        // cek dulu biar gak dobel
		        $cekLokasi = $this->db
		            ->where('cust_id', $customer)
		            ->where('UPPER(name)', $namaLokasi)
		            ->get('master_work_location_outsource')
		            ->row();

		        if ($cekLokasi) {
		            $lokasi = $cekLokasi->id;
		        } else {

		            $this->db->insert('master_work_location_outsource', [
		                'cust_id' => $customer,
		                'name'    => $namaLokasi
		            ]);

		            $lokasi = $this->db->insert_id();
		        }
		    }
	    
			
			$data = [
				'code' 				=> trim($post['kode_project']),
				'project_name' 		=> trim($post['nama_project']),
				'customer_id' 		=> $customer,
				'lokasi_id' 		=> $lokasi,
				'jenis_pekerjaan' 	=> trim($post['jenis_pekerjaan']),
				'management_fee' 	=> trim($post['management_fee']),
				'periode_start' 	=> date("Y-m-d", strtotime(trim($post['periode_start']))), 
				'periode_end' 		=> date("Y-m-d", strtotime(trim($post['periode_end'])))
				
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]); 

			if($rs){
				return [
		            "status" => true,
		            "msg"    => "Data berhasil disimpan"
		        ];
			}else{
				return [
		            "status" => false,
		            "msg"    => "Data gagal disimpan",
		            "debug"  => $this->db->error()
		        ];
			}

		} else{
			return [
	            "status" => false,
	            "msg"    => "Data gagal disimpan. ID tidak ditemukan"
	        ];
		}
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.name as customer_name, c.name as lokasi_name from project_outsource  a 
					left join data_customer b on b.id = a.customer_id 
					left join master_work_location_outsource c on c.id = a.lokasi_id
					
			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	/*
	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'employee_id' 	=> $v["B"],
				'training_name' => $v["C"],
				'training_date' => $v["D"],
				'location' 		=> $v["E"],
				'trainer' 		=> $v["F"],
				'notes' 		=> $v["G"],
				'status_id' 	=> $v["H"],
				'created_at' 	=> date("Y-m-d H:i:s")
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1){ //bukan super user
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}


		
		$sql = 'select a.*, b.full_name, b.direct_id, b.emp_code,
				(case
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "Rejected"
				else ""
				 end) as status_name
				from employee_training a left join employees b on b.id = a.employee_id
				'.$whr.'
				order by a.id asc

		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
		*/ 


	public function getDataLokasi($customer){ 

		$rs = $this->db->query("select * from master_work_location_outsource where cust_id = '".$customer."' order by name asc")->result(); 

		$data['mslokasi'] = $rs;


		return $data;

	}


}