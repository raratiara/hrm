<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_floating_crane_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "master/master_floating_crane_menu";
 	protected $table_name 				= _PREFIX_TABLE."floating_crane";
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
			'id',
			'code',
			'name',
			'latitude',
			'longitude'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = ' '.$this->table_name;
		

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
				$detail = '<a class="btn btn-xs btn-success detail-btn" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
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
				$row->latitude,
				$row->longitude

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
		$data = [
			'code' 	=> trim($post['code']),
			'name' 	=> trim($post['name']),
			'latitude' 	=> trim($post['latitude']),
			'longitude' => trim($post['longitude'])
		];

		
		$rs = $this->db->insert($this->table_name, $data);
		$lastId = $this->db->insert_id();


		if(isset($post['kode'])){
			$item_num = count($post['kode']); // cek sum
			$item_len_min = min(array_keys($post['kode'])); // cek min key index
			$item_len = max(array_keys($post['kode'])); // cek max key index
		} else {
			$item_num = 0;
		}

		if($item_num>0){
			for($i=$item_len_min;$i<=$item_len;$i++) 
			{
				if(isset($post['kode'][$i])){
					$itemData = [
						'floating_crane_id' => $lastId,
						'code' 		=> trim($post['kode'][$i]),
						'name' 		=> trim($post['nama'][$i]),
						'position' 	=> trim($post['posisi'][$i]),
						'rtsp' 		=> trim($post['rtsp'][$i]),
						'embed' 	=> trim($post['embed'][$i])
					];

					$this->db->insert('cctv', $itemData);
				}
			}
		}



		return $rs;
	}  

	public function edit_data($post) { 
		if(!empty($post['id'])){
			$data = [
				'code' 	=> trim($post['code']),
				'name' 	=> trim($post['name']),
				'latitude' 	=> trim($post['latitude']),
				'longitude' => trim($post['longitude'])
			];


			if(isset($post['kode'])){
				$item_num = count($post['kode']); // cek sum
				$item_len_min = min(array_keys($post['kode'])); // cek min key index
				$item_len = max(array_keys($post['kode'])); // cek max key index
			} else {
				$item_num = 0;
			}
			if($item_num>0){
				for($i=$item_len_min;$i<=$item_len;$i++) 
				{
					if(isset($post['kode'][$i])){
						
						if($post['hdnid'][$i] != ''){
							$uid = $post['hdnid'][$i];
							$itemData = [
								'code' 		=> trim($post['kode'][$i]),
								'name' 		=> trim($post['nama'][$i]),
								'position' 	=> trim($post['posisi'][$i]),
								'rtsp'		=> trim($post['rtsp'][$i]),
								'embed'		=> trim($post['embed'][$i])
							];

							$this->db->update('cctv', $itemData, [$this->primary_key => $uid]);
						}else{ 
							$itemData = [
								'floating_crane_id' => $post['id'],
								'code' 		=> trim($post['kode'][$i]),
								'name' 		=> trim($post['nama'][$i]),
								'position' 	=> trim($post['posisi'][$i]),
								'rtsp'		=> trim($post['rtsp'][$i]),
								'embed'		=> trim($post['embed'][$i])
							];

							$this->db->insert('cctv', $itemData);

						}
						
						
					}
				}
			}




			return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		} else return null;
	}  

	public function getRowData($id) { 
		
		$rs = $this->db->where([$this->primary_key => $id])->get($this->table_name)->row();
		
		/*if(!empty($rs->provinsi_id)){
			$ri = $this->db->select('name as parent_title')->where([$this->primary_key => $rs->provinsi_id])->get($this->table_name)->row();
			$rs = (object) array_merge((array) $rs, (array) $ri);
		} else {
			$rs = (object) array_merge((array) $rs, ['parent_title'=>'-']);
		}*/
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'code' 	=> $v["B"],
				'name' 	=> $v["C"],
				'latitude' 	=> $v["D"],
				'longitude' => $v["E"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = "select id, code, name, latitude, longitude from floating_crane
	   		ORDER BY id ASC
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	public function getNewExpensesRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getExpensesRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_txt('','kode['.$row.']','','kode','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','nama['.$row.']','','nama','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','posisi['.$row.']','','posisi','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','rtsp['.$row.']','','rtsp','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','embed['.$row.']','','embed','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getExpensesRows($id,$view,$print=FALSE){ 
		$dt = ''; 
		
		$rs = $this->db->query("select * from cctv where floating_crane_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f){
				$no = $row+1;
				if(!$view){
					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_txt($f->code,'kode['.$row.']','','kode','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->name,'nama['.$row.']','','nama','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->position,'posisi['.$row.']','','posisi','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->rtsp,'rtsp['.$row.']','','rtsp','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->embed,'embed['.$row.']','','embed','text-align: right;','data-id="'.$row.'" ').'</td>';
					
					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del(\''.$row.'\',\''.$f->id.'\')"></td>';
					$dt .= '</tr>';
				} else {
					if($print){
						if($row == ($rs_num-1)){
							$dt .= '<tr class="item last">';
						} else {
							$dt .= '<tr class="item">';
						}
					} else {
						$dt .= '<tr>';
					}
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->code.'</td>';
					$dt .= '<td>'.$f->name.'</td>';
					$dt .= '<td>'.$f->position.'</td>';
					$dt .= '<td>'.$f->rtsp.'</td>';
					$dt .= '<td>'.$f->embed.'</td>';
					$dt .= '</tr>';
				}

				$row++;
			}
		}

		return [$dt,$row];
	}

}
