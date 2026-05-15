<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus_int_menu extends MY_Controller
{
	const LABELMODULE = "bonus_int_menu";
	const LABELMASTER = "Menu Perhitungan Bonus Internal";
	const LABELFOLDER = "payroll_internal";
	const LABELPATH = "bonus_int_menu";
	const LABELNAVSEG1 = "payroll_internal";
	const LABELSUBPARENTSEG1 = "Master";
	const LABELNAVSEG2 = "";
	const LABELSUBPARENTSEG2 = "";

	public $icon = 'fa-gift';
	public $tabel_header = ["ID", "Periode", "Total Bonus", "Status"];

	public $colnames = ["ID", "Bulan", "Tahun", "Total Bonus", "Status"];
	public $colfields = ["id", "month_name", "periode_tahun", "total_nominal", "status_name"];

	public function form_field_asset()
	{
		$field = [];

		$msmonth = $this->db->query("select * from master_month order by id asc")->result();
		$field['selmonth'] = $this->self_model->return_build_select2me($msmonth, '', '', '', 'periode_bulan', 'periode_bulan', '', '', 'id', 'name_indo', ' ', '', '', 'required', 3, '-');

		$field['txtyear'] = $this->self_model->return_build_txt('', 'periode_tahun', 'periode_tahun', '', '', 'required maxlength="4"');
		$field['txtnotes'] = $this->self_model->return_build_txtarea('', 'notes', 'notes', 3);
		$field['rfu_reason'] = $this->self_model->return_build_txtarea('', 'rfu_reason', 'rfu_reason');
		$field['reject_reason'] = $this->self_model->return_build_txtarea('', 'reject_reason', 'reject_reason');
		$field['nominal_label'] = 'Bonus';

		return $field;
	}

	public function __construct()
	{
		parent::__construct();
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW', $akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD', $akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE', $akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE', $akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL', $akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT', $akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT', $akses["eksport"]);
	}

	public $folder_name = self::LABELFOLDER."/".self::LABELPATH;
	public $module_name = self::LABELMODULE;
	public $model_name = "bonus_int_menu_model";

	public $parent_menu = self::LABELFOLDER;
	public $subparent_menu = self::LABELNAVSEG1;
	public $subparentitem_menu = self::LABELNAVSEG2;
	public $sub_menu = self::LABELMODULE;

	public $label_parent_modul = self::LABELFOLDER;
	public $label_subparent_modul = self::LABELSUBPARENTSEG1;
	public $label_subparentitem_modul = self::LABELSUBPARENTSEG2;
	public $label_modul = self::LABELMASTER;
	public $label_list_data = "Daftar Data ".self::LABELMASTER;
	public $label_add_data = "Tambah Data ".self::LABELMASTER;
	public $label_update_data = "Edit Data ".self::LABELMASTER;
	public $label_sukses_disimpan = "Data berhasil disimpan";
	public $label_gagal_disimpan = "Data gagal disimpan";
	public $label_delete_data = "Hapus Data ".self::LABELMASTER;
	public $label_sukses_dihapus = "Data berhasil dihapus";
	public $label_gagal_dihapus = "Data gagal dihapus";
	public $label_detail_data = "Detail Data ".self::LABELMASTER;
	public $label_import_data = "Import Data ".self::LABELMASTER;
	public $label_sukses_diimport = "Data berhasil diimport";
	public $label_gagal_diimport = "Import data di baris : ";
	public $label_export_data = "Export";
	public $label_gagal_eksekusi = "Eksekusi gagal karena ketiadaan data";

	public function genbonusrowthr()
	{
		if(_USER_ACCESS_LEVEL_VIEW == "1") {
			$post = $this->input->post(null, true);
			$id = isset($post['id']) ? trim($post['id']) : 0;
			$view = (isset($post['view']) && in_array($post['view'], [TRUE, 'true', '1', 1], TRUE)) ? TRUE : FALSE;

			echo json_encode($this->self_model->getNewBonusThrRows($id, $view));
		} else {
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function rfu()
	{
		$post = $this->input->post(null, true);
		$rs = false;
		if(!empty($post['id'])) {
			$rs = $this->self_model->rfu($post['id'], isset($post['reason']) ? $post['reason'] : '', isset($post['approval_level']) ? $post['approval_level'] : 1);
		}

		echo json_encode($rs);
	}

	public function reject()
	{
		$post = $this->input->post(null, true);
		$rs = false;
		if(!empty($post['id'])) {
			$rs = $this->self_model->reject($post['id'], isset($post['reason']) ? $post['reason'] : '', isset($post['approval_level']) ? $post['approval_level'] : 1);
		}

		echo json_encode($rs);
	}

	public function getApprovalLog()
	{
		$post = $this->input->post(null, true);
		$rows = !empty($post['id']) ? $this->self_model->getApprovalLogRows($post['id']) : [];
		$html = '';

		if(!empty($rows)) {
			foreach($rows as $row) {
				$approval_date = ($row->approval_date == '0000-00-00 00:00:00' || $row->approval_date == '') ? '' : $row->approval_date;
				$html .= '<tr>';
				$html .= '<td>'.$row->approval_level.'</td>';
				$html .= '<td>'.$row->approver_name.'</td>';
				$html .= '<td>'.$row->status_name.'</td>';
				$html .= '<td>'.$approval_date.'</td>';
				$html .= '</tr>';
			}
		} else {
			$html .= '<tr><td colspan="4" class="text-center text-muted">No data</td></tr>';
		}

		echo json_encode(['html' => $html]);
	}
}
