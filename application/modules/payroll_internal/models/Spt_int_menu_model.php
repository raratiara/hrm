<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spt_int_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_internal/spt_int_menu";
 	protected $table_name 				= _PREFIX_TABLE."spt_pph21_internal";
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
			'dt.tahun',
			'dt.created_at',
			'dt.status_name'
		];
		
		
		$karyawan_id = $_SESSION['worker'];

		$sIndexColumn = $this->primary_key;

		$dateNow = date("Y-m-d");

		
		$where_emp = "";
			if(isset($_GET['flemployee']) && $_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and b.employee_id = '".$_GET['flemployee']."' ";
		}

		$sTable = '(select a.*, c.name as status_name, b.employee_id 
					from spt_pph21_internal a 
					left join spt_pph21_detail_internal b on b.spt_pph21_id = a.id
					left join master_status_spt c on c.id = a.status_id
					where 1=1 '.$where_emp.'
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
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && $row->status_name == 'Draft')  {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1" && $row->status_name == 'Draft')  {
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
				$row->tahun,
				$row->created_at,
				$row->status_name
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


	public function getBiayaJabatan($type, $bruto) { 

		if ($bruto <= 0) return 0;

		$biaya_jabatan=0;

		if($type == 'tahunan'){
			$biaya_jabatan = min(0.05 * $bruto, 6000000);
		}else if($type == 'bulanan'){
			$biaya_jabatan = min(0.05 * $bruto, 500000);
		}
		
		


		return $biaya_jabatan;
		
	} 


	public function getPph21_tahunan($pkp) { 

		if ($pkp <= 0) return 0;

		$pajak = 0;

	    if($pkp <= 60000000){ /// 60juta
	        $pajak += $pkp * 0.05; /// 5%
	    }
	    else{
	    	$pajak += 60000000 * 0.05; /// 5%
	        $pkp -= 60000000;

	        if($pkp <= 190000000){ /// 190jt
	            $pajak += $pkp * 0.15; /// 15%
	        }
	        else{
	        	$pajak += 190000000 * 0.15; /// 15%
	            $pkp -= 190000000;

	            if($pkp <= 250000000){ ///250jt
	                $pajak += $pkp * 0.25; /// 25%
	            }
	            else{
	            	$pajak += 250000000 * 0.25; /// 25%
	                $pkp -= 250000000;

	                if($pkp <= 4500000000){ /// 4.5 M
	                    $pajak += $pkp * 0.3; ///30%
	                }
	                else{
	                	$pajak += 4500000000 * 0.3; ///30%
	                    $pkp -= 4500000000;

	                    $pajak += $pkp * 0.35; ///35%
	                }
	            }
	        }
	    }
	        
	                
	             

    	return $pajak;

	} 


	public function getPTKP($marital_status_id) { 

		$ptkp =0;

		$getptkp = $this->db->query("select * from tax_ptkp where marital_status_id = '".$marital_status_id."' ")->result(); 
		if(!empty($getptkp)){
			$ptkp = $getptkp[0]->amount;
		}
		
		


		return $ptkp;
		
	} 


	public function add_data($post)
	{
	    
	    if (
	        empty($post['tahun_pajak'])
	    ) {
	        
	        return [
			    "status" => false,
			    "msg" 	 => "Tahun Pajak harus diisi"
			];
	    }

	    $tahun = trim($post['tahun_pajak']);

	    /* ===============================
	       FILTER EMPLOYEE 
	    =============================== */

	    $filter_employee = "";
	   
	    if ($post['is_all_project'] == 'Karyawan' && !empty($post['employeeIds'])) {
	        $ids = implode(',', array_map('intval', $post['employeeIds']));
	        $filter_employee = " AND a.employee_id IN ($ids) ";
	    }


	    /* ===============================
	       QUERY AGGREGASI (NO LOOP QUERY)
	    =============================== */

	    $sql = "
	    select dt.*, concat(dt.tahun_penggajian, '-', bb.code) as periode_start_desc
		, concat(dt.tahun_penggajian, '-', cc.code) as periode_end_desc
		from (SELECT 
			a.employee_id,
			c.marital_status_id,
			b.tahun_penggajian,
			MIN(b.bulan_penggajian) AS periode_start,
			MAX(b.bulan_penggajian) AS periode_end,

			SUM(a.total_pendapatan) AS ttl_pendapatan,
			SUM(a.pph_21) AS ttl_pph21,
			SUM(a.bpjs_kesehatan) AS ttl_bpjs_kesehatan,
			SUM(a.bpjs_tk) AS ttl_bpjs_tk,
			SUM(a.tunjangan_jabatan) AS ttl_tunjangan_jabatan,
			SUM(a.tunjangan_transport) AS ttl_tunjangan_transport,
			SUM(a.tunjangan_konsumsi) AS ttl_tunjangan_konsumsi,
			SUM(a.tunjangan_komunikasi) AS ttl_tunjangan_komunikasi,
			sum(a.gaji) as ttl_gaji

		FROM payroll_slip_detail_internal a
		LEFT JOIN payroll_slip_internal b ON b.id = a.payroll_slip_id
		LEFT JOIN employees c ON c.id = a.employee_id

		WHERE c.emp_source = 'internal' and b.tahun_penggajian = '".$tahun."'

		$filter_employee
		$filter_project

		GROUP BY 
			a.employee_id,
			c.marital_status_id) dt
			left join master_month bb on bb.id = dt.periode_start
			left join master_month cc on cc.id = dt.periode_end
	    
	    ";

	    $data_summary = $this->db->query(
	        $sql
	    )->result();

	    if (empty($data_summary)) {
	        return [
			    "status" => false,
			    "msg" 	 => "Data gagal disimpan"
			];
	    }

	    /* ===============================
	       PROCESS PER PROJECT (HEADER)
	    =============================== */

	    $insert_batch = [];

	    foreach ($data_summary as $row) {

	        if (empty($row->project_id)) continue;

	        /* ---- cek / buat header per project ---- */

	        $header = $this->db
	            ->where('tahun', $tahun)
	            ->get('spt_pph21_internal')
	            ->row();

	        if (!$header) {

	            $this->db->insert('spt_pph21_internal', [
	                'tahun' 		   => $tahun,
	                'created_at'       => date("Y-m-d H:i:s"),
	                'created_by' 	   => $_SESSION['worker'],
	                'status_id'=> 1 ///draft
	            ]);

	            $header_id = $this->db->insert_id();
	        } else {
	            $header_id = $header->id;
	        }

	       
	       	$iuran_pensiun = 0 ; ///belum ada
	        $biaya_jabatan = $this->getBiayaJabatan('tahunan',$row->ttl_pendapatan); 
	        $iuran = $row->ttl_bpjs_kesehatan + $row->ttl_bpjs_tk + $iuran_pensiun;
	        $neto = max(0, $row->ttl_pendapatan - $biaya_jabatan - $iuran);
	        $ptkp = $this->getPTKP($row->marital_status_id); 

	        // PKP tidak boleh negatif
			$pkp = max(0, $neto - $ptkp);
			// pembulatan ribuan ke bawah
			$pkp = floor($pkp / 1000) * 1000;

	        $pph21_tahunan = $this->getPph21_tahunan($pkp); 
	        $kurang_lebih_bayar = $pph21_tahunan-$row->ttl_pph21;

	        $kurang_lebih_bayar_desc = 'pas';
	        if ($kurang_lebih_bayar > 0) {
			    $kurang_lebih_bayar_desc = 'kurang bayar';
			} else if($kurang_lebih_bayar < 0) {
			    $kurang_lebih_bayar_desc = 'lebih bayar';
			}

			$total_tunjangan = $row->ttl_tunjangan_jabatan + $row->ttl_tunjangan_transport + $row->ttl_tunjangan_konsumsi + $row->ttl_tunjangan_komunikasi;
			

	        /* ---- siapkan batch insert ---- */

	        $insert_batch[] = [
	            'spt_pph21_id' 			=> $header_id,
	            'employee_id'           => $row->employee_id,
	            'bruto_tahunan'        	=> $row->ttl_pendapatan,
	            'biaya_jabatan'         => $biaya_jabatan,
	            'iuran'         		=> $iuran,
	            'neto_tahunan'         	=> $neto,
	            'ptkp'       			=> $ptkp,
	            'pkp'    				=> $pkp,
	            'pph21_tahunan'   		=> $pph21_tahunan,
	            'pph21_ter_total'		=> $row->ttl_pph21,
	            'kurang_lebih_bayar'	=> $kurang_lebih_bayar,
	            ///'status_id'=> 1, ///draft
	            'periode_start'			=> $row->periode_start_desc,
	            'periode_end'			=> $row->periode_end_desc,
	            'total_tunjangan' 		=> $total_tunjangan,
	            'total_gaji' 			=> $row->ttl_gaji
	            
	        ];
	    }

	    /* ===============================
	       INSERT BATCH DETAIL
	    =============================== */

	    if (!empty($insert_batch)) {
	        $this->db->insert_batch(
	            'spt_pph21_detail_internal',
	            $insert_batch
	        );
	    }

	    return [
		    "status" => true,
		    "msg" => "Data berhasil disimpan"
		];
	}


	public function edit_data($post) { 

		if(!empty($post['id'])){

			$item = [
				'status_id' => trim($post['status']) 
			];

			$rs = $this->db->update("spt_pph21_internal", $item, "id = '".$post['id']."'");


			/// update detail
			if(isset($post['hdnempid'])){
				$item_num = count($post['hdnempid']); // cek sum
				$item_len_min = min(array_keys($post['hdnempid'])); // cek min key index
				$item_len = max(array_keys($post['hdnempid'])); // cek max key index
			} else {
				$item_num = 0;
			}

			if($item_num>0){
				for($i=$item_len_min;$i<=$item_len;$i++) 
				{
					$hdnid = trim($post['hdnid'][$i]);

					if(!empty($hdnid)){ //update
						if(isset($post['hdnempid'][$i])){
							$itemData = [
								'periode_start'			=> trim($post['periode_start'][$i]),
								'periode_end' 			=> trim($post['periode_end'][$i]),
								'bruto_tahunan' 		=> trim($post['ttl_bruto_tahunan'][$i]),
								'biaya_jabatan'			=> trim($post['ttl_biaya_jabatan'][$i]),
								'iuran' 				=> trim($post['ttl_iuran'][$i]),
								'neto_tahunan' 			=> trim($post['ttl_neto_tahunan'][$i]),
								'ptkp' 					=> trim($post['ttl_ptkp'][$i]),
								'pkp' 					=> trim($post['ttl_pkp'][$i]),
								'pph21_tahunan' 		=> trim($post['ttl_pph21_tahunan'][$i]),
								'pph21_ter_total' 		=> trim($post['ttl_pph21_ter_total'][$i]),
								'kurang_lebih_bayar' 	=> trim($post['ttl_kurang_lebih_bayar'][$i])
							];

							$this->db->update("spt_pph21_detail_internal", $itemData, "id = '".$hdnid."'");
						}
					}else{ //insert
						if(isset($post['hdnempid'][$i])){
							$itemData = [
								'spt_pph21_id'			=> $post['id'],
								'employee_id' 			=> $trim($post['hdnempid'][$i]),
								'periode_start'			=> trim($post['periode_start'][$i]),
								'periode_end' 			=> trim($post['periode_end'][$i]),
								'bruto_tahunan' 		=> trim($post['ttl_bruto_tahunan'][$i]),
								'biaya_jabatan'			=> trim($post['ttl_biaya_jabatan'][$i]),
								'iuran' 				=> trim($post['ttl_iuran'][$i]),
								'neto_tahunan' 			=> trim($post['ttl_neto_tahunan'][$i]),
								'ptkp' 					=> trim($post['ttl_ptkp'][$i]),
								'pkp' 					=> trim($post['ttl_pkp'][$i]),
								'pph21_tahunan' 		=> trim($post['ttl_pph21_tahunan'][$i]),
								'pph21_ter_total' 		=> trim($post['ttl_pph21_ter_total'][$i]),
								'kurang_lebih_bayar' 	=> trim($post['ttl_kurang_lebih_bayar'][$i])
							];

							$this->db->insert('spt_pph21_detail_internal', $itemData);
						}
					}
				}
			}

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
			    "msg" 	 => "ID tidak ditemukan"
			];
		}
	}  

	public function getRowData($id) { 
		$mTable = '(
					select a.*, c.name as status_name from spt_pph21_internal a left join master_status_spt c on c.id = a.status_id
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
				'date_attendance' 	=> $v["B"],
				'employee_id' 		=> $v["C"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{ 
		/*$where_project = "";
			if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}*/


		$sql = 'select a.*, c.name as status_name from spt_pph21_internal a left join master_status_spt c on c.id = a.status_id 
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	

	public function getNewSptIntRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getSptIntRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$this->return_build_txt('','ttl_hari_kerja','ttl_hari_kerja','ttl_hari_kerja','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid" name="hdnid" value=""/></td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_masuk','ttl_masuk','ttl_masuk','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_ijin','ttl_ijin','ttl_ijin','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_cuti','ttl_cuti','ttl_cuti','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_alfa','ttl_alfa','ttl_alfa','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_lembur','ttl_lembur','ttl_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_jam_kerja','ttl_jam_kerja','ttl_jam_kerja','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_jam_lembur','ttl_jam_lembur','ttl_jam_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_alfa','ttl_alfa','ttl_alfa','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_lembur','ttl_lembur','ttl_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_jam_kerja','ttl_jam_kerja','ttl_jam_kerja','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_jam_lembur','ttl_jam_lembur','ttl_jam_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" onclick="del_fpp(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getSptIntRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select a.*, c.emp_code, c.full_name, b.status_id as status_id_header
								from spt_pph21_detail_internal a
								left join spt_pph21_internal b on b.id = a.spt_pph21_id
								left join employees c on c.id = a.employee_id
								where c.emp_source = 'internal' and a.spt_pph21_id = '".$id."'
								ORDER BY c.full_name ASC
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

					$dt .= '<td>'.$this->return_build_txt($f->periode_start,'periode_start['.$row.']','','periode_start','text-align: right;','data-id="'.$row.'"  ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->periode_end,'periode_end['.$row.']','','periode_end','text-align: right;','data-id="'.$row.'"  ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->bruto_tahunan,'ttl_bruto_tahunan['.$row.']','','ttl_bruto_tahunan','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->biaya_jabatan,'ttl_biaya_jabatan['.$row.']','','ttl_biaya_jabatan','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->iuran,'ttl_iuran['.$row.']','','ttl_iuran','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->neto_tahunan,'ttl_neto_tahunan['.$row.']','','ttl_neto_tahunan','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->ptkp,'ttl_ptkp['.$row.']','','ttl_ptkp','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->pkp,'ttl_pkp['.$row.']','','ttl_pkp','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->pph21_tahunan,'ttl_pph21_tahunan['.$row.']','','ttl_pph21_tahunan','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->pph21_ter_total,'ttl_pph21_ter_total['.$row.']','','ttl_pph21_ter_total','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->kurang_lebih_bayar,'ttl_kurang_lebih_bayar['.$row.']','','ttl_kurang_lebih_bayar','text-align: right;','data-id="'.$row.'" ').'</td>';

					

					
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

					$print_spt = '-';
					if($f->status_id_header == '2'){ ///Final
						$print_spt = '<a class="btn btn-default btn-xs" onclick="getFormSpt_int('."'".$f->id."'".')"><i class="fa fa-download"></i> Form 1721</a>';
					}
					
					$dt .= '<td>'.$print_spt.'</td>';
					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'</td>';
					$dt .= '<td>'.$f->periode_start.'</td>';
					$dt .= '<td>'.$f->periode_end.'</td>';
					$dt .= '<td>'.$f->bruto_tahunan.'</td>';
					$dt .= '<td>'.$f->biaya_jabatan.'</td>';
					$dt .= '<td>'.$f->iuran.'</td>';
					$dt .= '<td>'.$f->neto_tahunan.'</td>';
					$dt .= '<td>'.$f->ptkp.'</td>';
					$dt .= '<td>'.$f->pkp.'</td>';
					$dt .= '<td>'.$f->pph21_tahunan.'</td>';
					$dt .= '<td>'.$f->pph21_ter_total.'</td>';
					$dt .= '<td>'.$f->kurang_lebih_bayar.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}





}