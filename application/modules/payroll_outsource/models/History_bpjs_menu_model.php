<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History_bpjs_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/history_bpjs_menu";
 	protected $table_name 				= _PREFIX_TABLE."history_bpjs";
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
			'dt.project_name',
			'dt.periode_gaji_bulan_name',
			'dt.periode_gaji_tahun',
			'dt.periode'
		];
		

		$where_disetor="";
		if(isset($_GET['fldisetor']) && $_GET['fldisetor'] != '' && $_GET['fldisetor'] != 0){
			if($_GET['fldisetor'] == 1){
				$where_disetor = " and d.tanggal_setor is not null ";
			}else if($_GET['fldisetor'] == 0){
				$where_disetor = " and d.tanggal_setor is null ";
			}
			
		}

		$where_dikembalikan = "";
		if(isset($_GET['fldikembalikan']) && $_GET['fldikembalikan'] != '' && $_GET['fldikembalikan'] != 0){
			if($_GET['fldikembalikan'] == 1){
				$where_dikembalikan = " and d.tanggal_dikembalikan is not null ";
			}else if($_GET['fldikembalikan'] == 0){
				$where_dikembalikan = " and d.tanggal_dikembalikan is null ";
			}
		}

		$where="";
		if($where_disetor != "" || $where_dikembalikan != ""){
			$where = " left join history_bpjs_detail d on d.history_bpjs_id = a.id 
						where 1=1 ".$where_disetor.$where_dikembalikan." ";
		}


		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name_indo as periode_gaji_bulan_name, c.project_name, 
					concat(b.name_indo, " ",a.periode_gaji_tahun) as periode
					from history_bpjs a left join master_month b on b.id = a.periode_gaji_bulan 
					left join project_outsource c on c.id = a.project_id '.$where.')dt';
		

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
					'.$delete.'
				</div>',
				$row->id,
				$row->project_name,
				$row->periode


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
		$show_date_start 	= trim($post['show_date_start']);
		$show_date_end 		= trim($post['show_date_end']);

  		if(!empty($post['label1']) && !empty($post['label2']) && !empty($post['title'])){ 
  			if($post['info_type'] == 'Event'){
  				$color = 'today';
  			}else if($post['info_type'] == 'News'){
  				$color = 'yellow';
  			}else{
  				$color = 'grey'; //orange
  			}

  			$data = [
				'label1' 			=> trim($post['label1']),
				'label2' 			=> trim($post['label2']),
				'color'				=> $color,
				'title' 			=> trim($post['title']),
				'description' 		=> trim($post['description']),
				'type' 				=> trim($post['info_type']),
				'show_date_start' 	=> date("Y-m-d", strtotime($show_date_start)),
				'show_date_end' 	=> date("Y-m-d", strtotime($show_date_end)),
				'created_at'		=> date("Y-m-d H:i:s")
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
			    "msg" 	 => "Data gagal disimpan"
			];
  		}

	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){ 
		
			if($post['info_type'] == 'Event'){
  				$color = 'today';
  			}else if($post['info_type'] == 'News'){
  				$color = 'yellow';
  			}else{
  				$color = 'grey'; //orange
  			}

  			$data = [
				'label1' 			=> trim($post['label1']),
				'label2' 			=> trim($post['label2']),
				'color'				=> $color,
				'title' 			=> trim($post['title']),
				'description' 		=> trim($post['description']),
				'type' 				=> trim($post['info_type']),
				'show_date_start' 	=> date("Y-m-d", strtotime($show_date_start)),
				'show_date_end' 	=> date("Y-m-d", strtotime($show_date_end)),
				'updated_at'		=> date("Y-m-d H:i:s")
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
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

		} else{
			return [
				    "status" => false,
				    "msg" 	 => "Data gagal disimpan"
				];
		}
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.name_indo as periode_gaji_bulan_name, c.project_name, 
					concat(b.name_indo, " ",a.periode_gaji_tahun) as periode
					from history_bpjs a left join master_month b on b.id = a.periode_gaji_bulan 
					left join project_outsource c on c.id = a.project_id
			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	public function import_data($list_data){  
		/*error_reporting(E_ALL);
		ini_set('display_errors', 1);*/

		$error = '';

		if (isset($list_data[0][0]) && is_array($list_data[0][0])) {
		    $list_data[0] = $list_data[0][0];
		}

		// Lewati header (baris ke-0)
		for ($i = 1; $i < count($list_data); $i++) {
            $row = $list_data[$i];
            $baris = $i+1;


            /// UPDATE DATA
         	if($row[0] != '' && $row[1] != '' && $row[2] != '' && ($row[3] != '' || $row[4] != '')) 
         	{ 
         		$getID = $this->db->query("select id from employees where emp_code = '".$row[0]."'")->result();
            	$employee_id = $getID[0]->id;
            	$periode_gaji_bulan = $row[1];
            	$periode_gaji_tahun = $row[2];

         		if($employee_id != ''){ 
         			$dataBpjs = $this->db->query("select a.*, b.periode_gaji_bulan, b.periode_gaji_tahun from history_bpjs_detail a left join history_bpjs b on b.id = a.history_bpjs_id where employee_id = '".$employee_id."' and  b.periode_gaji_bulan = '".$periode_gaji_bulan."' and b.periode_gaji_tahun = '".$periode_gaji_tahun."'")->result();

         			if(!empty($dataBpjs)){
         				$iddetail = $dataBpjs[0]->id;

         				$data = [
			                'tanggal_setor' 		=> trim($row[3]),
			                'tanggal_dikembalikan' 	=> trim($row[4])
			            ];

			            $rs = $this->db->update("history_bpjs_detail", $data, "id = '".$iddetail."'");


			            if (!$rs) $error .=",baris ". $baris;
         			}

            	}else{ 
            		$error .=",baris ". $baris;
            	} 

         	}

        }


		return $error;

	}

	public function eksport_data()
	{

		$sql = "select a.*, b.name_indo as periode_gaji_bulan_name, c.project_name, 
					concat(b.name_indo, ' ',a.periode_gaji_tahun) as periode
					from history_bpjs a left join master_month b on b.id = a.periode_gaji_bulan 
					left join project_outsource c on c.id = a.project_id
			order by a.id asc
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}



	public function getNewHistBpjsRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getHistBpjsRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td colspan="9">No Data</td>';

			
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getHistBpjsRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.emp_code, b.full_name from history_bpjs_detail a 
								left join employees b on b.id = a.employee_id
								where a.history_bpjs_id = ".$id."
								order by b.full_name
								")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'<input type="hidden" id="hdnempid" name="hdnempid['.$row.']" value="'.$f->employee_id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->no_bpjs_kesehatan,'no_bpjs_kesehatan['.$row.']','','no_bpjs_kesehatan','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->nominal_bpjs_kesehatan,'nominal_bpjs_kesehatan['.$row.']','','nominal_bpjs_kesehatan','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->no_bpjs_tk,'no_bpjs_tk['.$row.']','','no_bpjs_tk','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->nominal_bpjs_tk,'nominal_bpjs_tk['.$row.']','','nominal_bpjs_tk','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tanggal_potong,'tanggal_potong['.$row.']','','tanggal_potong','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tanggal_setor,'tanggal_setor['.$row.']','','tanggal_setor','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tanggal_dikembalikan,'tanggal_dikembalikan['.$row.']','','tanggal_dikembalikan','text-align: right;','data-id="'.$row.'" ').'</td>';

				
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

					$tanggal_dikembalikan = $f->tanggal_dikembalikan;
					if($f->tanggal_dikembalikan == '' || $f->tanggal_dikembalikan == '0000-00-00'){
						$tanggal_dikembalikan = "";
					}

					$tanggal_setor = $f->tanggal_setor;
					if($f->tanggal_setor == '' || $f->tanggal_setor == '0000-00-00'){
						$tanggal_setor = "";
					}
					
					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'</td>';
					$dt .= '<td>'.$f->no_bpjs_kesehatan.'</td>';
					$dt .= '<td>'.$f->nominal_bpjs_kesehatan.'</td>';
					$dt .= '<td>'.$f->no_bpjs_tk.'</td>';
					$dt .= '<td>'.$f->nominal_bpjs_tk.'</td>';
					$dt .= '<td>'.$f->tanggal_potong.'</td>';
					$dt .= '<td>'.$tanggal_setor.'</td>';
					$dt .= '<td>'.$tanggal_dikembalikan.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


}
