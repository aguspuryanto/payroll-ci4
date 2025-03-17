<?php

namespace App\Controllers\laporan;
use App\Controllers\BaseController;

use App\Models\PencatatanJurnalUmumModel;

use CodeIgniter\Files\File;

class JurnalUmum extends BaseController {
  public function __construct() {
    $this->pencatatanJurnalUmumModel = new PencatatanJurnalUmumModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        
      });
    ";

   
    $where = "bon.kode_bon_status NOT in (5,6)";

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
      $where .= "pencatatan_jurnal_umum.status = '".$this->data['status']."'";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(pencatatan_jurnal_umum.tanggal_pencatatan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where.= "departmen.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }
    $jurnalumums=$this->pencatatanJurnalUmumModel->getPencatatanJurnalUmum($where, "pencatatan_jurnal_umum.tanggal_pencatatan, pencatatan_jurnal_umum.kode_bon")->find();

    $this->data['arr_jurnalumum'] = array();
    foreach($jurnalumums as $jurnalumum) {
      $this->data['arr_jurnalumum'][$jurnalumum->kode_bon][]=$jurnalumum;
    }
    //print_r ($this->data['arr_jurnalumum']); die();
    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('laporan/jurnalumum/v_jurnalumum', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function cetak() {
    $this->data['laporan'] = "Pencatatan Jurnal Umum";
    $filter = "";
    $where = "bon.kode_bon_status NOT in (5,6)";

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
      $where .= "pencatatan_jurnal_umum.status = '".$this->data['status']."'";
      $filter .= "<h5>Status: ".$this->data['status']."</h5>";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(pencatatan_jurnal_umum.tanggal_pencatatan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
      $filter .= "<h5>Periode ".date("Y-m-d", strtotime($this->data['tglawal']))." s/d ".date("Y-m-d", strtotime($this->data['tglakhir']))."</h5>";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where.= "departmen.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }
    $jurnalumums=$this->pencatatanJurnalUmumModel->getPencatatanJurnalUmum($where, "pencatatan_jurnal_umum.tanggal_pencatatan, pencatatan_jurnal_umum.kode_bon")->find();

    $this->data['arr_jurnalumum'] = array();
    foreach($jurnalumums as $jurnalumum) {
      $this->data['arr_jurnalumum'][$jurnalumum->kode_bon][]=$jurnalumum;
    }

    

    $this->data['header'] = "<tr><th colspan='8'><div class='text-center'>
        <h1>LAPORAN PENCATATAN JURNAL UMUM</h1>
        <small>Tanggal Cetak: ".date("Y-m-d H:i:s")."</small>
        $filter
      </div>
      </th>
    </tr>
    <tr>
      <th style='min-width: 100px !important;'>Tanggal Pencatatan</th>
      <th style='min-width: 100px !important;'>Tanggal Transaksi</th>
      <th style='min-width: 140px !important;'>PT</th>
      <th style='max-width: 90px !important;'>Kode Bon</th>
      <th style='max-width: 150px !important;'>Status</th>
      <th style='min-width: 220px;'>COA</th>
      <th style='min-width: 120px; max-width: 120px !important;' class='text-center'>Debet</th>
      <th style='min-width: 120px; max-width: 120px !important;' class='text-center'>Kredit</th>
    </tr>";

    $this->data['body'] = $this->generateBodyLaporan($this->data['arr_jurnalumum']);

    echo view('laporan/v_cetak', $this->data);
  }

  private function generateBodyLaporan($arr) {
    $hasil = "";
    foreach($arr as $kode_bon => $datas) {
      foreach($datas as $key=> $data) {
        
        $hasil .= "<tr>";
        if(!$key) {
          $hasil .= "<td rowspan='".count($datas)."'>".date("Y-m-d H:i:s", strtotime($data->tanggal_pencatatan))."</td>";
          $hasil .= "<td rowspan='".count($datas)."'>";
            if($data->tanggal_transaksi) $hasil .=  date("Y-m-d H:i:s", strtotime($data->tanggal_transaksi));
          $hasil .= "</td>";
          $hasil .= "<td rowspan='".count($datas)."' class='text-start'>".$data->nama_pt."</td>";
          $hasil .= "<td rowspan='".count($datas)."'>".$data->kode_bon."</td>";
          $hasil .= "<td rowspan='".count($datas)."'>".$data->status."</td>";
          
        }
        $hasil .= "<td class='overflow-x-hidden text-start'>".$data->kode_coa." - ".$data->nama_transaksi."</td>";
        if($data->posisi=="KREDIT") $hasil .= "<td></td>";
        $hasil .= "<td class='text-end'>".number_format($data->nominal, 0, ".", ",")."</td>";
        if($data->posisi=="DEBET") $hasil .= "<td></td>";

        
        $hasil .= "</tr>";
      }
    }
    return $hasil;
  }
}