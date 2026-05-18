<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'/Bonus_int_menu.php';

class Thr_int_menu extends Bonus_int_menu
{
	const LABELMODULE = "thr_int_menu";
	const LABELMASTER = "Menu Perhitungan THR Special Payroll Internal";
	const LABELPATH = "thr_int_menu";

	public $tabel_header = ["ID", "Periode", "Total THR", "Status"];

	public $colnames = ["ID", "Bulan", "Tahun", "Total THR", "Status"];

	public $folder_name = self::LABELFOLDER."/".self::LABELPATH;
	public $module_name = self::LABELMODULE;
	public $model_name = "thr_int_menu_model";
	public $sub_menu = self::LABELMODULE;
	public $label_modul = self::LABELMASTER;
	public $label_list_data = "Daftar Data ".self::LABELMASTER;
	public $label_add_data = "Tambah Data ".self::LABELMASTER;
	public $label_update_data = "Edit Data ".self::LABELMASTER;
	public $label_delete_data = "Hapus Data ".self::LABELMASTER;
	public $label_detail_data = "Detail Data ".self::LABELMASTER;
	public $label_import_data = "Import Data ".self::LABELMASTER;

	public function form_field_asset()
	{
		$field = parent::form_field_asset();
		$field['nominal_label'] = 'THR';

		return $field;
	}
}
