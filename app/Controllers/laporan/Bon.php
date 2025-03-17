<?php

namespace App\Controllers\laporan;
use App\Controllers\BaseController;

use App\Models\BonModel;
use App\Models\BonStatusModel;

use CodeIgniter\Files\File;

class Bon extends BaseController {
  var $arr_total = array();

  public function __construct() {
    $this->bonModel = new BonModel();
    $this->bonStatusModel = new BonStatusModel();

  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('#datatable').parent().addClass('table-responsive');
        $('#datatable').parent().parent().addClass('pe-2');
      });
    ";

   
    $where = "";

    $this->data['status'] = $this->request->getGet('status');
    $periode = $this->request->getGet('periode');
    $this->data['abaikan'] = $this->request->getGet('abaikan');
    if(!empty($periode)) {
      $this->data['tglawal'] = substr($periode, 0, 10);
      $this->data['tglakhir'] = substr($periode, 13, 10);
    } else {
      $this->data['tglawal'] = date("m/01/Y");
      $this->data['tglakhir'] = date("m/t/Y");
    }
    if($this->data['status']!="" ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "bon.kode_bon_status = '".$this->data['status']."'";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(bon.tanggal_pengajuan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where .= "departmen_kas.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }

    $this->data['bons']=$this->bonModel->getBon($where)->find();
    $this->data['bon_statuss'] = $this->bonStatusModel->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('laporan/bon/v_bon', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function cetak() {
    $this->data['laporan'] = "Bon";
    $where = "";
    $filter = "";


    $this->data['status'] = $this->request->getGet('status');
    $periode = $this->request->getGet('periode');
    $this->data['abaikan'] = $this->request->getGet('abaikan');
    if(!empty($periode)) {
      $this->data['tglawal'] = substr($periode, 0, 10);
      $this->data['tglakhir'] = substr($periode, 13, 10);
    } else {
      $this->data['tglawal'] = date("m/01/Y");
      $this->data['tglakhir'] = date("m/t/Y");
    }
    if($this->data['status']!="" ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "bon.kode_bon_status = '".$this->data['status']."'";
      $filter .= "<h5>Status: ".$this->data['status']."</h5>";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(bon.tanggal_pengajuan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
      $filter .= "<h5>Periode ".date("Y-m-d", strtotime($this->data['tglawal']))." s/d ".date("Y-m-d", strtotime($this->data['tglakhir']))."</h5>";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where .= "departmen_kas.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }

    $this->data['bons']=$this->bonModel->getBon($where)->find();
    

    $this->data['header'] = "<tr><th colspan='11'><div class='text-center'>
        <h1>LAPORAN BON</h1>
        <small>Tanggal Cetak: ".date("Y-m-d H:i:s")."</small>
        $filter
      </div>
      </th>
    </tr>
    <tr>
      <th style='max-width: 70px !important;'>Kode</th>
      <th style='min-width: 90px !important;'>Tgl. Pengajuan</th>
      <th style='min-width: 90px !important;'>Tgl. Persetujuan</th>
      <th style='min-width: 90px !important;'>Tgl. Realisasi</th>
      <th style='min-width: 90px !important;'>Tgl. Konfirmasi</th>
      <th style='max-width: 100px !important;'>Status</th>
      <th style='min-width: 120px !important;'>Karyawan</th>
      <th style='max-width: 120px !important;'>Jenis</th>
      <th style='max-width: 100px !important;'>Nominal</th>
      <th style='max-width: 100px !important;'>Kas Asal</th>
      <th style='max-width: 100px !important;'>Kas Tujuan</th>
    </tr>";

    $this->data['body'] = $this->generateBodyLaporan($this->data['bons']);

    echo view('laporan/v_cetak_landscape', $this->data);
  }
  
  private function generateBodyLaporan($arr) {
    $hasil = "";
    foreach($arr as $bon) {
      $hasil .= "<tr>";
        $hasil .= "<td class='text-end'>".$bon->kode_bon."</td>";
        $hasil .= "<td class='text-start'>".date("Y-m-d H:i:s", strtotime($bon->tanggal_pengajuan))."</td>";
        $hasil .= "<td class='text-start'>".($bon->tanggal_persetujuan ? date("Y-m-d H:i:s", strtotime($bon->tanggal_persetujuan)) : "-")."</td>";
        $hasil .= "<td class='text-start'>".($bon->tanggal_realisasi ? date("Y-m-d H:i:s", strtotime($bon->tanggal_realisasi)) : "-")."</td>";
        $hasil .= "<td class='text-start'>".($bon->tanggal_konfirmasi ? date("Y-m-d H:i:s", strtotime($bon->tanggal_konfirmasi)) : "-")."</td>";
        $hasil .= "<td>".$bon->nama_bon_status."</td>";
        $hasil .= "<td class='text-start'>".$bon->nama_lengkap."</td>";
        $hasil .= "<td>".$bon->jenis."</td>";
        $hasil .= "<td class='text-end'>".number_format($bon->nominal,0,".", ",")."</td>";
        $hasil .= "<td>".$bon->nama_kas."</td>";
        $hasil .= "<td>".$bon->nama_kas_tujuan."</td>";       
       
      $hasil .= "</tr>";
    }
    return $hasil;
  }
}