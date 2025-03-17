<?php

namespace App\Controllers\laporan;
use App\Controllers\BaseController;

use App\Models\PTKaryawanModel;

use CodeIgniter\Files\File;

class Karyawan extends BaseController {

  public function __construct() {
    $this->PTKaryawanModel = new PTKaryawanModel();

  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('#datatable').parent().addClass('table-responsive');
        $('#datatable').parent().parent().addClass('pe-2');
      });
    ";

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }

    $this->data['karyawans']=$this->PTKaryawanModel->getPTKaryawan($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('laporan/karyawan/v_karyawan', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function cetak() {
    $this->data['laporan'] = "Karyawan";
    $where = "";
    $filter = "";

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }

    $this->data['karyawans']=$this->PTKaryawanModel->getPTKaryawan($where, "pt_karyawan.no_urut")->find();
    

    $this->data['header'] = "<tr><th colspan='9'><div class='text-center'>
        <h1>LAPORAN KARYAWAN</h1>
        <small>Tanggal Cetak: ".date("Y-m-d H:i:s")."</small>
        $filter
      </div>
      </th>
    </tr>
    <tr>
      <th style='max-width: 90px !important;'>No. Urut</th>
      <th style='min-width: 70px !important;'>NIK</th>
      <th style='min-width: 70px !important;'>NIP</th>
      <th style='min-width: 120px; max-width: 220px !important;'>PT</th>
      <th style='min-width: 180px !important;'>Nama Departmen</th>
      <th style='min-width: 200px !important;'>Nama Karyawan</th>
      <th style='min-width: 150px !important;'>Jabatan</th>
      <th style='min-width: 130px !important;'>BPJS Kes</th>
      <th style='min-width: 130px !important;'>BPJS TK</th>
    </tr>";

    $this->data['body'] = $this->generateBodyLaporan($this->data['karyawans']);

    echo view('laporan/v_cetak_landscape', $this->data);
  }
  
  private function generateBodyLaporan($arr) {
    $hasil = "";
    foreach($arr as $karyawan) {
       $hasil.= "<tr>";
        $hasil.= "<td>".$karyawan->no_urut."</td>";
        $hasil.= "<td>".$karyawan->nik."</td>";
        $hasil.= "<td>".$karyawan->nip."</td>";
        $hasil.= "<td>".$karyawan->nama_pt."</td>";
        $hasil.= "<td>".$karyawan->nama_departmen."</td>";
        $hasil.= "<td>".$karyawan->nama_lengkap;
          if ($karyawan->nama_alias) $hasil.= " (".$karyawan->nama_alias.")";
        $hasil.= "</td>";
        $hasil.= "<td>".$karyawan->jabatan."</td>";
        $hasil.= "<td>".$karyawan->bpjs_kes."</td>";
        $hasil.= "<td>".$karyawan->bpjs_tk."</td>";
       
      $hasil.= "</tr>";
    }
    return $hasil;
  }
}