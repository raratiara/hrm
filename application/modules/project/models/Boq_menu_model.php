<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boq_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "project/boq_menu";
 	protected $table_name 				= _PREFIX_TABLE."project_outsource_boq";
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
			'dt.customer_name',
			'dt.project_name',
			'dt.periode_start',
			'dt.periode_end'
		];
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name as customer_name, 
					(case when c.jenis_pekerjaan != "" and c.lokasi != "" then concat(c.code," (",c.lokasi," - ",c.jenis_pekerjaan,")")
					when c.jenis_pekerjaan != "" and c.lokasi = "" then concat(c.code," (",c.jenis_pekerjaan,")")
					when c.lokasi != "" and c.jenis_pekerjaan = "" then concat(c.code," (",c.lokasi,")")
					else c.code end
					) as project_desc, c.project_name, c.periode_start, c.periode_end
					from project_outsource_boq a left join data_customer b on b.id = a.customer_id
					left join project_outsource c on c.id = a.project_outsource_id)dt';
		

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
			
			$print_pdf = '<a class="btn btn-xs btn-success" style="background-color: #18a11d;" href="javascript:void(0);" onclick="print_pdf('."'".$row->id."'".')" role="button"><i class="fa fa-download"></i></a>';

			$periode = '';
			if($row->periode_start != '' && $row->periode_end != ''){
				$periode = $row->periode_start.' s/d '.$row->periode_end;
			}

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
					'.$print_pdf.'
				</div>',
				$row->id,
				$row->customer_name,
				$row->project_name,
				$periode


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
		
  		if(!empty($post['customer_boq']) && !empty($post['project_boq']) ){ 
  			

  			$data = [
				'project_outsource_id' 	=> trim($post['project_boq']),
				'customer_id' 			=> trim($post['customer_boq']),
				'created_at'			=> date("Y-m-d H:i:s"),
				'created_by' 			=> $_SESSION['worker'],
				'ppn_percen' 			=> trim($post['hdnppn_percen']),
				'pph_percen' 			=> trim($post['hdnpph_percen']),
				'management_fee_percen' => trim($post['hdnmanagementfee_percen']),
				'ppn_harga' 			=> trim($post['hdnppn_harga']),
				'pph_harga' 			=> trim($post['hdnpph_harga']),
				'jumlah' 				=> trim($post['hdnjumlah_harga']),
				'management_fee_harga' 	=> trim($post['hdnmanagement_fee']),
				'jumlah_total' 			=> trim($post['hdnjumlah_total']),
				'grand_total' 			=> trim($post['hdngrand_total']),
				'master_boq_template_id' => trim($post['template_boq'])
			];
			$rs = $this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id();

			if($rs){

				if(isset($post['hdnid_dtlboq'])){
					$item_num = count($post['hdnid_dtlboq']); // cek sum
					$item_len_min = min(array_keys($post['hdnid_dtlboq'])); // cek min key index
					$item_len = max(array_keys($post['hdnid_dtlboq'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						if(isset($post['hdnid_dtlboq'][$i])){
							
							$raw_jumlah_harga = trim($post['jumlah_harga'][$i]);

							$jumlah_harga = $raw_jumlah_harga !== ''
						    ? str_replace(['.', ','], ['', '.'], $raw_jumlah_harga)
						    : 0;


							$itemData = [
								'boq_id'			=> $lastId,
								'ms_boq_detail_id' 	=> trim($post['hdnid_dtlboq'][$i]),
								'jumlah' 			=> trim($post['jumlah'][$i]),
								'harga_satuan' 		=> trim($post['satuan_harga'][$i]),
								'jumlah_harga'		=> $jumlah_harga
							];

							$this->db->insert('project_outsource_boq_detail', $itemData);
						}
					}
				}

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
			    "msg" 	 => "Customer dan Project tidak boleh kosong"
			];
  		}

	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){ 
		
			$data = [
				'project_outsource_id' 	=> trim($post['project_boq']),
				'customer_id' 			=> trim($post['customer_boq']),
				'ppn_percen' 			=> trim($post['hdnppn_percen']),
				'pph_percen' 			=> trim($post['hdnpph_percen']),
				'management_fee_percen' => trim($post['hdnmanagementfee_percen']),
				'ppn_harga' 			=> trim($post['hdnppn_harga']),
				'pph_harga' 			=> trim($post['hdnpph_harga']),
				'jumlah' 				=> trim($post['hdnjumlah_harga']),
				'management_fee_harga' 	=> trim($post['hdnmanagement_fee']),
				'jumlah_total' 			=> trim($post['hdnjumlah_total']),
				'grand_total' 			=> trim($post['hdngrand_total']),
				'master_boq_template_id' => trim($post['template_boq'])
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
			if($rs){
				if(isset($post['hdnid_dtlboq'])){
					$item_num = count($post['hdnid_dtlboq']); // cek sum
					$item_len_min = min(array_keys($post['hdnid_dtlboq'])); // cek min key index
					$item_len = max(array_keys($post['hdnid_dtlboq'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						if(isset($post['hdnid_dtlboq'][$i])){
							
							$raw_jumlah_harga = trim($post['jumlah_harga'][$i]);

							$jumlah_harga = $raw_jumlah_harga !== ''
						    ? str_replace(['.', ','], ['', '.'], $raw_jumlah_harga)
						    : 0;


							$itemData = [
								'jumlah' 			=> trim($post['jumlah'][$i]),
								'harga_satuan' 		=> trim($post['satuan_harga'][$i]),
								'jumlah_harga'		=> $jumlah_harga
							];

							$this->db->update("project_outsource_boq_detail", $itemData, "id = '".$post['hdnid_dtlboq'][$i]."'");
						}
					}
				}

				return [
				    "status" => true,
				    "msg" 	 => "Data berhasil disimpan"
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
			    "msg" 	 => "ID tidak ditemukan"
			];
		}
	}  

	public function getRowData($id) { 
		
		$mTable = '(select a.*, b.name as customer_name, 
					(case when c.jenis_pekerjaan != "" and c.lokasi != "" then concat(c.code," (",c.lokasi," - ",c.jenis_pekerjaan,")")
					when c.jenis_pekerjaan != "" and c.lokasi = "" then concat(c.code," (",c.jenis_pekerjaan,")")
					when c.lokasi != "" and c.jenis_pekerjaan = "" then concat(c.code," (",c.lokasi,")")
					else c.code end
					) as project_desc, c.project_name, c.periode_start, c.periode_end, d.name as template_name
					from project_outsource_boq a left join data_customer b on b.id = a.customer_id
					left join project_outsource c on c.id = a.project_outsource_id
					left join master_boq_template d on d.id = a.master_boq_template_id
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

		$sql = "select * from office_info
			order by id asc
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getDataProject($customer){ 

		$rs = $this->db->query("select *, 
								(case when jenis_pekerjaan != '' and lokasi != '' then concat(code,' (',lokasi,' - ',jenis_pekerjaan,')')
								when jenis_pekerjaan != '' and lokasi = '' then concat(code,' (',jenis_pekerjaan,')')
								when lokasi != '' and jenis_pekerjaan = '' then concat(code,' (',lokasi,')')
								else code end
								) as project_desc
								from project_outsource 
								where customer_id = ".$customer."
								order by code asc")->result(); 

		$data['msproject'] = $rs;


		return $data;

	}


	public function getNewBoqRow($row,$id=0,$customer,$project,$template_id,$view=FALSE)
	{  
		$data = $this->getBoqRows($id,$customer,$project,$template_id,$view);
		/*if($id > 0){ 
			$data = $this->getBoqRows($id,$view);
		} else { 
			$data = '';


			$rs = $this->db->query("select a.*, b.name as header_name, c.name as parent_name from master_boq_detail a 
					left join master_boq_header b on b.id = a.master_header_boq_id
					left join master_boq_parent_detail c on c.id = a.parent_id")->result(); 
			if(!empty($rs)){
				foreach($rs as $f){
					$no = $row+1;
			
			
					$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid_dtlboq'.$row.'" name="hdnid_dtlboq['.$row.']" value="'.$f->id.'"/></td>';

					$data 	.= '<td>'.$f->name.'</td>';

					$data 	.= '<td>'.$this->return_build_txt('','jumlah['.$row.']','','jumlah','text-align: right;','data-id="'.$row.'" ').'</td>';

					$data 	.= '<td>'.$this->return_build_txt('','satuan_harga['.$row.']','','satuan_harga','text-align: right;','data-id="'.$row.'" ').'</td>';

					$data 	.= '<td>'.$this->return_build_txt('','jumlah_harga['.$row.']','','jumlah_harga','text-align: right;','data-id="'.$row.'" ').'</td>';

					$data 	.= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" onclick="del(\''.$row.'\',\''.$f->id.'\')" value="Delete"></td>';
				}
			}

			
		}*/

		return $data;
	} 
	

	public function number_id_trim($n) {
	    if (floor($n) == $n) {
	        // bilangan bulat
	        return number_format($n, 0, ',', '.');
	    } else {
	        // ada desimal
	        return number_format($n, 2, ',', '.');
	    }
	}



	// Generate expenses item rows for edit & view
	public function getBoqRows($id,$customer,$project,$template_id,$view,$print=FALSE){ 

		$this->load->helper('global');


		$dataProject = $this->db->query("select * from project_outsource where customer_id = '".$customer."' and id = '".$project."'")->result(); 
		$management_fee=0;
		if(!empty($dataProject)){
			$management_fee = $dataProject[0]->management_fee;
		}


		$dt=''; 
		$checked_ppn=''; $ppn_percen = '11'; $ppn_harga='';
		$checked_pph=''; $pph_percen = '2'; $pph_harga='';
		$display_none_ppn = 'display:none';
		$display_none_pph = 'display:none';
		$grand_total=''; $jumlah_total='';
		$management_fee_harga=''; $jumlah_harga='';
		$jumlah_harga_allheader='';


		if($id > 0){ 
			$rs = $this->db->query("select b.master_header_boq_id, a.id, b.name, b.is_active, b.parent_id, b.no_urut, a.jumlah, a.harga_satuan, a.jumlah_harga, bb.name AS header_name, bb.id as header_id,
				cc.name AS parent_name, bb.no_urut as no_urut_header, cc.no_urut as no_urut_parent
			from project_outsource_boq_detail a
			left join master_boq_detail b on b.id = a.ms_boq_detail_id
			left join master_boq_header c on c.id = b.master_header_boq_id
			LEFT JOIN master_boq_header bb ON bb.id = b.master_header_boq_id
			LEFT JOIN master_boq_parent_detail cc ON cc.id = b.parent_id
			where a.boq_id = '".$id."'
			ORDER BY 
				bb.no_urut ASC,
				cc.no_urut ASC,
				b.no_urut ASC ")->result(); 

			$dataProject = $this->db->query("select * from project_outsource_boq where id = '".$id."' ")->result(); 
			if(!empty($dataProject)){
				$management_fee = $dataProject[0]->management_fee_percen;
				$grand_total = $dataProject[0]->grand_total;
				$jumlah_total = $dataProject[0]->jumlah_total;
				$management_fee_harga = $dataProject[0]->management_fee_harga;
				$jumlah_harga_allheader = $dataProject[0]->jumlah;
				
        		if($ppn_percen != '' && $ppn_percen != 0){
        			$checked_ppn = 'checked';
        			$ppn_percen = $dataProject[0]->ppn_percen;
					$ppn_harga = $dataProject[0]->ppn_harga;
					$display_none_ppn = '';
        		}
        		if($pph_percen != '' && $pph_percen != 0){
        			$checked_pph = 'checked';
        			$pph_percen = $dataProject[0]->pph_percen;
					$pph_harga = $dataProject[0]->pph_harga;
					$display_none_pph = '';
        		}
            	
			}

		}else{ /// add

			$rs = $this->db->query("select 
										a.*, '' as jumlah, '' as jumlah_harga,
										b.name AS header_name, b.id as header_id,
										c.name AS parent_name, b.no_urut as no_urut_header, c.no_urut as no_urut_parent
									FROM master_boq_detail a
									LEFT JOIN master_boq_header b ON b.id = a.master_header_boq_id
									LEFT JOIN master_boq_parent_detail c ON c.id = a.parent_id
									where a.master_boq_template_id = ".$template_id."
									ORDER BY 
										b.no_urut ASC,
										c.no_urut ASC,
										a.no_urut ASC
									")->result(); 
		}
		
		
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			$last_header_id = '';
			$last_header = '';
			$last_parent = '';
			$header_parent_count = [];
			$no_in_header = 0; // nomor urut per header

			$sum_parent_jumlah        = 0;
			$sum_parent_jumlah_harga = 0;

			$sum_header_jumlah        = 0;
			$sum_header_jumlah_harga = 0;
			$gaji_pokok_parent_jumlah = 0;

			$sum_all_jumlah        = 0;
			$sum_all_jumlah_harga = 0;


			foreach ($rd as $f){

				$header_name = normalize_text($f->header_name);
				$parent_name = normalize_text($f->parent_name);



				/*$no = $row+1;*/
				

				// Kumpulkan parent unik per header
				if (!isset($header_parent_count[$header_name])) {
				    $header_parent_count[$header_name] = [];
				}
				if (!empty($parent_name)) {
				    $header_parent_count[$header_name][$parent_name] = true;
				}

				// JIKA GANTI HEADER → tutup total parent & header sebelumnya
	            if ($last_header != '' && $header_name != $last_header) {

	                $parentCount = isset($header_parent_count[$last_header])
					    ? count($header_parent_count[$last_header])
					    : 0;

					if ($last_parent != '' && $parentCount > 1) {
					/*if ($last_parent != '') {*/
					    $dt .= '<tr class="boq-total-parent" data-header="'.htmlspecialchars($last_header).'"
            					data-parent="'.htmlspecialchars($last_parent).'">';
					    $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
					                Total '.$last_parent.'
					            </td>';
					    $dt .= '<td style="text-align:right;background:#fafafa;">'.number_id_trim($sum_parent_jumlah).'</td>';
					    $dt .= '<td style="background:#fafafa;"></td>';
					    $dt .= '<td style="text-align:right;background:#fafafa;">'.number_id_trim($sum_parent_jumlah_harga).'</td>';
					    $dt .= '</tr>';

					    // reset parent total
					    $sum_parent_jumlah        = 0;
					    $sum_parent_jumlah_harga = 0;
					}

	                $dt .= '<tr class="boq-total-header" data-header="'.$last_header.'">';
					$dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#91f560;">
					            Total '.$last_header.'
					        </td>';
					$dt .= '<td style="text-align:right;font-weight:bold;background:#91f560;">'.number_id_trim($sum_header_jumlah).'</td>';
					$dt .= '<td style="background:#91f560;"></td>';
					$dt .= '<td style="text-align:right;font-weight:bold;background:#91f560;">'.number_id_trim($sum_header_jumlah_harga).'<input type="hidden" id="header_id" name="header_id[]" value="'.$f->header_id.'"/><input type="hidden" id="header_jumlah_harga" name="header_jumlah_harga[]" value="'.$sum_header_jumlah_harga.'"/></td>';
					$dt .= '</tr>';

					// akumulasi ke grand total
					$sum_all_jumlah        	+= $sum_header_jumlah;
					$sum_all_jumlah_harga 	+= $sum_header_jumlah_harga;

					// reset header total
					$sum_header_jumlah        	= 0;
					$sum_header_jumlah_harga 	= 0;


	                $last_parent = '';
	            }

	            // JIKA GANTI PARENT → tutup total parent sebelumnya
	            $parentCount = isset($header_parent_count[$last_header])
				    ? count($header_parent_count[$last_header])
				    : 0;

				if ($last_parent != '' && $parent_name != $last_parent && $parentCount > 1) {
				/*if ($last_parent != '' && $parent_name != $last_parent) {*/
				   $dt .= '<tr class="boq-total-parent" data-header="'.htmlspecialchars($last_header).'"
            				data-parent="'.htmlspecialchars($last_parent).'">';
				    $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
				                Total '.$last_parent.'
				            </td>';
				    $dt .= '<td style="text-align:right;background:#fafafa;">'.number_id_trim($sum_parent_jumlah).'</td>';
				    $dt .= '<td style="background:#fafafa;"></td>';
				    $dt .= '<td style="text-align:right;background:#fafafa;">'.number_id_trim($sum_parent_jumlah_harga).'</td>';
				    $dt .= '</tr>';

				    $sum_parent_jumlah        = 0;
				    $sum_parent_jumlah_harga = 0;
				}

	            // CETAK HEADER kalau berubah
				if ($header_name != $last_header) {
				    $dt .= '<tr class="boq-header">';
				    $dt .= '<td colspan="5" style="font-weight:bold;background:#f5e965;">'
				         . strtoupper($header_name) .
				         '</td>';
				    $dt .= '</tr>';

				    $last_header_id   = $f->header_id;
				    $last_header   = $header_name;
				    $last_parent   = '';
				    $no_in_header  = 0; // reset nomor tiap ganti header
				}

	            // CETAK PARENT kalau berubah
	            if ($parent_name != $last_parent && !empty($parent_name)) {
	                $dt .= '<tr class="boq-parent">';
	                $dt .= '<td colspan="5" style="font-weight:bold;padding-left:20px;background:#fafafa;">'
	                     . $parent_name .
	                     '</td>';
	                $dt .= '</tr>';

	                $last_parent = $parent_name;
	            }

	            $no_in_header++;
				$no = $no_in_header;

				$jumlah_val        = 0;
				$jumlah_harga_val = 0;

				
				
				if(!$view){ 

					$dt .= '<tr class="boq-item" 
				            data-header="'.htmlspecialchars($header_name).'" 
				            data-parent="'.htmlspecialchars($parent_name).'">';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid_dtlboq'.$row.'" name="hdnid_dtlboq['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$f->name.'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->jumlah,'jumlah['.$row.']','','jumlah','text-align: right;','data-id="'.$row.'" onkeyup="set_jumlah_harga(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->harga_satuan,'satuan_harga['.$row.']','','satuan_harga','text-align: right;','data-id="'.$row.'" onkeyup="set_jumlah_harga2(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt(number_id_trim($f->jumlah_harga),'jumlah_harga['.$row.']','','jumlah_harga','text-align: right;','data-id="'.$row.'"  readonly ').'</td>';

					
					/*$dt .= '<td><input type="button" class="btn btn-md btn-danger ibtnDelBoq" id="btndelboq" value="Delete" onclick="del(\''.$row.'\',\''.$f->id.'\')"></td>';*/
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

					// $jumlah='';
					// if($f->jumlah != 0 && $f->jumlah != ''){
					// 	$jumlah = $f->jumlah;
					// }
					// $harga_satuan='';
					// if($f->harga_satuan != 0 && $f->harga_satuan != ''){
					// 	$harga_satuan = number_id_trim($f->harga_satuan);
					// }
					// $jumlah_harga='';
					// if($f->jumlah_harga != 0 && $f->jumlah_harga != ''){
					// 	$jumlah_harga = number_id_trim($f->jumlah_harga);
					// }

					$jumlah = $f->jumlah;
					$harga_satuan = number_id_trim($f->harga_satuan);
					$jumlah_harga = number_id_trim($f->jumlah_harga);
					
					$dt .= '<td style="text-align:right">'.$no.'</td>';
					$dt .= '<td style="text-align:left">'.$f->name.'</td>';
					$dt .= '<td style="text-align:right">'.$jumlah.'</td>';
					$dt .= '<td style="text-align:right">'.$harga_satuan.'</td>';
					$dt .= '<td style="text-align:right">'.$jumlah_harga.'</td>';
					
					$dt .= '</tr>';


					$jumlah_val        	= (float) $f->jumlah;
    				$jumlah_harga_val 	= (float) $f->jumlah_harga;
					
				}


				// akumulasi
				/*$sum_parent_jumlah        	+= $jumlah_val;
				$sum_parent_jumlah_harga 	+= $jumlah_harga_val;

				$sum_header_jumlah        	+= $jumlah_val;
				$sum_header_jumlah_harga 	+= $jumlah_harga_val;*/

				// ================= PARENT =================
				$sum_parent_jumlah        += $jumlah_val;
				$sum_parent_jumlah_harga += $jumlah_harga_val;

				// ================= HEADER =================
				/*if (strtoupper(trim($last_header)) === 'gaji pokok' || strtoupper(trim($last_header)) === 'gaji pokok')*/
				if ($last_header === 'gaji pokok')  {

				    // KHUSUS JUMLAH saja
				    /*if (strtoupper(trim($last_parent)) === 'gaji pokok') {*/
				    if ($last_parent === 'gaji pokok') {
				        $gaji_pokok_parent_jumlah += $jumlah_val;
				    }

				} else {

				    // header lain normal
				    $sum_header_jumlah += $jumlah_val;
				}

				// jumlah_harga TETAP NORMAL
				$sum_header_jumlah_harga += $jumlah_harga_val;



				

				$row++;
			}


			// TUTUP TOTAL TERAKHIR
	        $parentCount = isset($header_parent_count[$last_header])
			    ? count($header_parent_count[$last_header])
			    : 0;

			if ($last_parent != '' && $parentCount > 1) {
			/*if ($last_parent != '') {*/
			    $dt .= '<tr class="boq-total-parent" data-header="'.htmlspecialchars($last_header).'"
            				data-parent="'.htmlspecialchars($last_parent).'">';
			    $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
			                Total '.$last_parent.'
			            </td>';
			    $dt .= '<td style="text-align:right;background:#fafafa;">'.number_id_trim($sum_parent_jumlah).'</td>';
			    $dt .= '<td style="background:#fafafa;"></td>';
			    $dt .= '<td style="text-align:right;background:#fafafa;">'.number_id_trim($sum_parent_jumlah_harga).'</td>';
			    $dt .= '</tr>';
			}

	        if ($last_header != '') {
	        	// VALIDASI KHUSUS HEADER GAJI POKOK (JUMLAH SAJA)
				/*if (strtoupper(trim($last_header)) === 'gaji pokok') {*/
				if ($last_header === 'gaji pokok') {

				    $sum_header_jumlah = $gaji_pokok_parent_jumlah;

				    // reset biar header berikutnya aman
				    $gaji_pokok_parent_jumlah = 0;
				}

	            $dt .= '<tr class="boq-total-header" data-header="'.$last_header.'">';
			    $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#91f560;">
			                Total '.$last_header.'
			            </td>';
			    $dt .= '<td style="text-align:right;font-weight:bold;background:#91f560;">'.number_id_trim($sum_header_jumlah).'</td>';
			    $dt .= '<td style="background:#91f560;"></td>';
			    $dt .= '<td style="text-align:right;font-weight:bold;background:#91f560;">'.number_id_trim($sum_header_jumlah_harga).'<input type="hidden" id="header_id" name="header_id[]" value="'.$last_header_id.'"/><input type="hidden" id="header_jumlah_harga" name="header_jumlah_harga[]" value="'.$sum_header_jumlah_harga.'"/></td>';
			    $dt .= '</tr>';

			    // akumulasi ke total semua header
			    $sum_all_jumlah        += $sum_header_jumlah;
			    $sum_all_jumlah_harga += $sum_header_jumlah_harga;



	            $dt .= '<tr class="boq-total-all" data-type="grand">';
				$dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
				            Jumlah
				        </td>';
				$dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;">'.number_id_trim($sum_all_jumlah).'</td>';
				$dt .= '<td style="background:#fafafa;"><input type="hidden" id="hdnjumlah_harga" name="hdnjumlah_harga" value="'.$jumlah_harga_allheader.'" /></td>';
				$dt .= '<td style="font-weight:bold;text-align:right;background:#a6d1fb;">'.number_id_trim($sum_all_jumlah_harga).'</td>';
				$dt .= '</tr>';


	            $dt .= '<tr class="boq-management-fee">';
	            $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
	                        Management Fee
	                    </td>';
	            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;">'.$management_fee.' %
	            <input type="hidden" id="hdnmanagementfee_percen" name="hdnmanagementfee_percen" value="'.$management_fee.'"/></td>';
	            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"></td>';
                $dt .= '<td style="font-weight:bold;text-align:right;background:#a6d1fb;"><input type="hidden" id="hdnmanagement_fee" name="hdnmanagement_fee" value='.$management_fee_harga.' /><span id="management_fee">'.number_id_trim($management_fee_harga).'</span></td>';
	            $dt .= '</tr>';



	            $dt .= '<tr class="boq-jumlah-total">';
	            $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fba6a6;">
	                        JUMLAH TOTAL (Jumlah + Management Fee)
	                    </td>';
	            $dt .= '<td style="font-weight:bold;text-align:right;background:#fba6a6;"></td>';
	            $dt .= '<td style="font-weight:bold;text-align:right;background:#fba6a6;"></td>';
                $dt .= '<td style="font-weight:bold;text-align:right;background:#fba6a6;"><input type="hidden" id="hdnjumlah_total" name="hdnjumlah_total" value="'.$jumlah_total.'" /><span id="jumlah_total">'.number_id_trim($jumlah_total).'</span></td>';
	            $dt .= '</tr>';


	            if($view){ //view
	            	$dt .= '<tr class="boq-ppn">';
		            $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
		                       PPN (%)
		                    </td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><span id="ppn_percen"></span></td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"></td>';
	                $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><span id="ppn_harga"></span></td>';
		            $dt .= '</tr>';


		            $dt .= '<tr class="boq-pph">';
		            $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
		                        PPH 23 (%)
		                    </td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><span id="pph_percen"></span> </td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"></td>';
	                $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><span id="pph_harga"></span></td>';
		            $dt .= '</tr>';



	            }else{ //add or edit
	            	
	            	$dt .= '<tr class="boq-ppn">';
		            $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
		                       <input type="checkbox" id="with_ppn" name="with_ppn" '.$checked_ppn.' onclick="set_with_ppn()"/> &nbsp; include PPN (%)
		                    </td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><input type="text" id="hdnppn_percen" name="hdnppn_percen" value="'.$ppn_percen.'" onkeyup="set_harga_ppn()" style="'.$display_none_ppn.'; text-align:right" class="form-control"/></td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"></td>';
	                $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><input type="hidden" id="hdnppn_harga" name="hdnppn_harga" value="'.$ppn_harga.'" /><span id="ppn_harga">'.number_id_trim($ppn_harga).'</span></td>';
		            $dt .= '</tr>';



		            $dt .= '<tr class="boq-pph">';
		            $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#fafafa;">
		                        <input type="checkbox" id="with_pph" name="with_pph" '.$checked_pph.'  onclick="set_with_pph()"/> &nbsp; incude PPH 23 (%)
		                    </td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><input type="text" id="hdnpph_percen" name="hdnpph_percen" value="'.$pph_percen.'" onkeyup="set_harga_pph()" style="'.$display_none_pph.'; text-align:right" class="form-control"/> </td>';
		            $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"></td>';
	                $dt .= '<td style="font-weight:bold;text-align:right;background:#fafafa;"><input type="hidden" id="hdnpph_harga" name="hdnpph_harga" value="'.$ppn_harga.'" /><span id="pph_harga">'.number_id_trim($pph_harga).'</span></td>';
		            $dt .= '</tr>';

	            }


	            $dt .= '<tr class="boq-grand-total">';
	            $dt .= '<td colspan="2" style="font-weight:bold;text-align:right;background:#d3d3d3;">
	                        GRAND TOTAL
	                    </td>';
	            $dt .= '<td style="font-weight:bold;text-align:right;background:#d3d3d3;"></td>';
	            $dt .= '<td style="font-weight:bold;text-align:right;background:#d3d3d3;"></td>';
                $dt .= '<td style="font-weight:bold;text-align:right;background:#d3d3d3;"><input type="hidden" id="hdngrand_total" name="hdngrand_total" value="'.$grand_total.'"/><span id="grand_total">'.number_id_trim($grand_total).'</span></td>';
	            $dt .= '</tr>';
	        }


		}

		return [$dt,$row];
	}




	

}
