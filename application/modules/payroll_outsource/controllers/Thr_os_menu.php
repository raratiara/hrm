<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'/Bonus_os_menu.php';

class Thr_os_menu extends Bonus_os_menu
{
	const LABELMODULE = "thr_os_menu";
	const LABELMASTER = "Menu Perhitungan THR Outsource";
	const LABELPATH = "thr_os_menu";

	public $tabel_header = ["ID", "Project", "Periode", "Total THR"];

	public $colnames = ["ID", "Project", "Bulan", "Tahun", "Total THR"];

	public $folder_name = self::LABELFOLDER."/".self::LABELPATH;
	public $module_name = self::LABELMODULE;
	public $model_name = "thr_os_menu_model";
	public $sub_menu = self::LABELMODULE;
	public $label_modul = self::LABELMASTER;
	public $label_list_data = "Daftar Data ".self::LABELMASTER;
	public $label_add_data = "Tambah Data ".self::LABELMASTER;
	public $label_update_data = "Edit Data ".self::LABELMASTER;
	public $label_delete_data = "Hapus Data ".self::LABELMASTER;
	public $label_detail_data = "Detail Data ".self::LABELMASTER;
	public $label_import_data = "Import Data ".self::LABELMASTER;
}
