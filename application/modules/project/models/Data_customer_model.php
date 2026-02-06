<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_customer_model extends MY_Model
{
	/* Module */
 	protected $folder_name			= "project/data_customer";
 	protected $table_name 			= _PREFIX_TABLE."data_customer";
 	//protected $table_industrial 	= _PREFIX_TABLE."option_industrial_type";
 	//protected $table_bank 			= _PREFIX_TABLE."option_bank";
 	protected $table_status 		= _PREFIX_TABLE."option_general_status";
 	//protected $table_document 		= _PREFIX_TABLE."option_doc_attachment";
 	protected $primary_key 			= "id";
	/* upload */
 	protected $attachment_folder	= "./uploads/data/customer";
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	protected $allow_size			= "0"; // 0 for limit by default php conf (in Kb)

	function __construct()
	{
		parent::__construct();
	}

	// Get Document Need 
	public function getAttachData() { 
		$rs	= $this->db->select('id,description')->order_by('idx_seq', 'ASC')->where("active='1'")->get($this->table_document)->result();

		return $rs;
	} 

	// Remove file
	public function remove_file($id = "", $filename= "") {
		if(!empty($id) && !empty($filename)){
			$filepath = $this->attachment_folder.'/'.$id.'/'.$filename;
			if(is_file($filepath)) {
				unlink($filepath);
			}
		}
	}
	
	// Upload file
	public function upload_file($id = "", $fieldname= "", $replace=FALSE, $oldfilename= "") {
		$data = array();
		$data['status'] = FALSE;
		if(!empty($id) && !empty($fieldname)){
			if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
			{
				$dir = $this->attachment_folder.'/'.$id;
				if(!is_dir($dir)) {
					mkdir($dir);
				}
				if($replace){
					$this->remove_file($id, $oldfilename);
				}
				$config['upload_path']   = $dir.'/';
				$config['allowed_types'] = $this->allow_type;
				$config['max_size'] 	 = $this->allow_size;
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload($fieldname)){
					$err_msg = $this->upload->display_errors();
					$data['error_warning'] = strip_tags($err_msg);				
					$data['status'] = FALSE;
				} else {
					$fileData = $this->upload->data();
					$data['upload_file'] = $fileData['file_name'];
					$data['status'] = TRUE;
				}
			}
		}
		
		return $data;
	}

	// Generate item list
	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'a.id',
			'a.code',
			'a.name',
			'a.address',
			'a.contact_name',
			'a.contact_phone',
			'a.contact_email',
			'b.description as status',
			'a.npwp'
		];

		$sIndexColumn = 'a.'.$this->primary_key;
		$sTable = ' '.$this->table_name.' a
					LEFT JOIN '.$this->table_status.' b ON b.id=a.id_status
					';

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
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
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
				$row->name,
				substr($row->address,0,30).' ...',
				$row->contact_name,
				$row->contact_phone,
				$row->contact_email,
				$row->status,
				$row->npwp
			));
		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val){
		return !is_null($val);
	}		

	// delete item action
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

	// adding data
	public function add_data($post) { 
		$id_status = (isset($post['id_status']) && !empty($post['id_status']))? trim($post['id_status']):NULL;

		$dataCust = $this->db->query("select * from data_customer where name = '".$post['name']."' ")->result();
		if(!empty($dataProject)){
			echo "Tidak dapat menyimpan data dengan Nama Customer yang sama";
		}else{

			$data = [
				'code' 				=> trim($post['code']),
				'name' 				=> trim($post['name']),
				'address' 			=> trim($post['address']),
				'contact_name' 		=> trim($post['contact_name']),
				'contact_phone' 	=> trim($post['contact_phone']),
				'contact_email' 	=> trim($post['contact_email']), 
				'id_status' 		=> $id_status,
				'insert_by'			=> $_SESSION["username"],
				'npwp' 				=> trim($post['customer_npwp']),
				'province_id' 		=> trim($post['province'] ?? ''),
				'regency_id' 		=> trim($post['regency'] ?? ''),
				'district_id' 		=> trim($post['district'] ?? ''),
				'village_id' 		=> trim($post['village'] ?? ''),
				'postal_code' 		=> trim($post['postal_code'] ?? ''),
				'sistem_lembur' 	=> trim($post['sistem_lembur'] ?? ''),
				'tanggal_pembayaran_lembur' => trim($post['tgl_pembayaran_lembur'] ?? '')
			];

			if($post['sistem_lembur'] == 'tidak_sistem_lembur'){
				
				$data['nominal_lembur'] = trim($post['nominal_lembur']);
			}

			//$this->db->trans_off(); // Disable transaction
			$this->db->trans_start(); // set "True" for query will be rolled back
			$this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id(); 
	 
			$this->db->trans_complete();

			return $rs = $this->db->trans_status();
		}
		
	}  

	// update data
	public function edit_data($post) { 
		if(!empty($post['id'])){
			$uid = trim($post['id']); 
			$id_status = (isset($post['id_status']) && !empty($post['id_status']))? trim($post['id_status']):NULL;
			$data = [
				'code' 				=> trim($post['code']),
				'name' 				=> trim($post['name']),
				'address' 			=> trim($post['address']),
				'contact_name' 		=> trim($post['contact_name']),
				'contact_phone' 	=> trim($post['contact_phone']),
				'contact_email' 	=> trim($post['contact_email']), 
				'id_status' 		=> $id_status,
				'update_by'			=> $_SESSION["username"],
				'npwp' 				=> trim($post['customer_npwp']),
				'province_id' 		=> trim($post['province'] ?? ''),
				'regency_id' 		=> trim($post['regency'] ?? ''),
				'district_id' 		=> trim($post['district'] ?? ''),
				'village_id' 		=> trim($post['village'] ?? ''),
				'postal_code' 		=> trim($post['postal_code'] ?? ''),
				'sistem_lembur' 	=> trim($post['sistem_lembur'] ?? ''),
				'tanggal_pembayaran_lembur' => trim($post['tgl_pembayaran_lembur'] ?? '')
			];

			if($post['sistem_lembur'] == 'tidak_sistem_lembur'){
				
				$data['nominal_lembur'] = trim($post['nominal_lembur']);
			}

			//$this->db->trans_off(); // Disable transaction
			$this->db->trans_start(); // set "True" for query will be rolled back
			$this->db->update($this->table_name, $data, [$this->primary_key => $uid]);
			 
			$this->db->trans_complete();

			return $rs = $this->db->trans_status();			
		} else return null;
	}  

	// getting row data for update / detail view
	public function getRowData($id) { 
		$mTable = "(select a.*, b.description as status_name, c.name as province_name, d.name as regency_name, e.name as district_name, f.name as village_name, if(a.sistem_lembur = 'tidak_sistem_lembur','Tidak Sistem Lembur','Sistem Lembur') as sistem_lembur_desc
			from data_customer a 
			left join option_general_status b on b.id = a.id_status
			left join provinces c on c.id = a.province_id
			left join regencies d on d.id = a.regency_id
			left join districts e on e.id = a.district_id
			left join villages f on f.id = a.village_id
			)dt";

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		return $rs;
	} 
	
	 

	// importing data
	public function import_data($list_data)
	{
		$i = 0;
		$error = '';
		
		foreach ($list_data as $k => $v) {
			$i += 1;
 
			$id_status = trim($v["H"]);
			if(empty($id_status)) $id_status = NULL;
			$data = [
				'code' 				=> trim($v["B"]),
				'name' 				=> trim($v["C"]),
				'address' 			=> trim($v["D"]),
				'contact_name' 		=> trim($v["E"]),
				'contact_phone' 	=> trim($v["F"]),
				'contact_email' 	=> trim($v["G"]), 
				'id_status' 		=> $id_status,
				'insert_by'			=> $_SESSION["username"]
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	// export data
	// Need use GET for accept parameter
	public function eksport_data()
	{
		$sql = "SELECT
					a.id,
					a.code,
					a.name, 
					a.contact_name,
					a.contact_phone,
					a.contact_email, 
					c.description as status
			FROM
	   	 		".$this->table_name." a  
			LEFT JOIN 
				".$this->table_status." c ON a.id_status = c.id 
	   		ORDER BY
	   			".$this->primary_key." ASC
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getDataRegency($province){ 

		$rs = $this->db->query("select * from regencies where province_id = '".$province."' order by name asc")->result(); 

		$data['msregency'] = $rs;


		return $data;

	}

	public function getDataDistrict($province,$regency){ 

		$rs = $this->db->query("select * from districts where province_id = '".$province."' and regency_id = '".$regency."' order by name asc")->result(); 

		$data['msdistrict'] = $rs;


		return $data;

	}


	public function getDataVillage($province,$regency,$district){ 

		$rs = $this->db->query("select * from villages where province_id = '".$province."' and regency_id = '".$regency."' and district_id = '".$district."' order by name asc")->result(); 

		$data['msvillage'] = $rs;


		return $data;

	}


}
