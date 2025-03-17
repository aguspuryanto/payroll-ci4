<?php

namespace App\Controllers\laporan;
use App\Controllers\BaseController;

use App\Models\CoaModel;
use App\Models\KasModel;
use App\Models\KasMutasiModel;

use CodeIgniter\Files\File;

class Kas extends BaseController {
  var $arr_total = array();

  public function __construct() {
    $this->coaModel = new CoaModel();
    $this->kasModel = new KasModel();
    $this->kasMutasiModel = new KasMutasiModel();

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
    $this->data['jenis'] = $this->request->getGet('jenis');
    if(!empty($this->data['jenis'])) {
      $where = "kas_mutasi.jenis = '".$this->data['jenis']."'";
    }

    $periode = $this->request->getGet('periode');
    $this->data['abaikan'] = $this->request->getGet('abaikan');
    if(!empty($periode)) {
      $this->data['tglawal'] = substr($periode, 0, 10);
      $this->data['tglakhir'] = substr($periode, 13, 10);
    } else {
      $this->data['tglawal'] = date("m/01/Y");
      $this->data['tglakhir'] = date("m/t/Y");
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(kas_mutasi.tanggal_transaksi) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where .= "departmen.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }

    $this->data['kas_mutasis']=$this->kasMutasiModel->getKasMutasi($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('laporan/kas/v_kas', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function cetak() {
    $this->data['laporan'] = "Mutasi Kas";
    $where = "";
    $filter = "";

    $this->data['jenis'] = $this->request->getGet('jenis');
    if(!empty($this->data['jenis'])) {
      $where = "kas_mutasi.jenis = '".$this->data['jenis']."'";
      $filter .= "<h5>Jenis: ".$this->data['jenis']."</h5>";
    }

    $periode = $this->request->getGet('periode');
    $this->data['abaikan'] = $this->request->getGet('abaikan');
    if(!empty($periode)) {
      $this->data['tglawal'] = substr($periode, 0, 10);
      $this->data['tglakhir'] = substr($periode, 13, 10);
    } else {
      $this->data['tglawal'] = date("m/01/Y");
      $this->data['tglakhir'] = date("m/t/Y");
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(kas_mutasi.tanggal_transaksi) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
      $filter .= "<h5>Periode ".date("Y-m-d", strtotime($this->data['tglawal']))." s/d ".date("Y-m-d", strtotime($this->data['tglakhir']))."</h5>";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where .= "departmen.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }

    $this->data['kas_mutasis']=$this->kasMutasiModel->getKasMutasi($where)->find();
    

    $this->data['header'] = "<tr><th colspan='10'><div class='text-center'>
        <h1>LAPORAN MUTASI KAS</h1>
        <small>Tanggal Cetak: ".date("Y-m-d H:i:s")."</small>
        $filter
      </div>
      </th>
    </tr>
    <tr>
      <th style='max-width: 70px !important;'>Kode</th>
      <th style='width: 90px !important;'>Tgl Input</th>
      <th style='width: 90px !important;'>Tgl Transaksi</th>
      <th style='width: 120px !important;'>Kas</th>
      <th style='max-width: 120px !important;'>Nominal Perubahan</th>
      <th style='max-width: 120px !important;'>Nominal Awal</th>
      <th style='max-width: 120px !important;'>Nominal Akhir</th>
      <th style='max-width: 100px !important;'>Keterangan</th>
      <th style='max-width: 100px !important;'>Transaksi</th>
      <th style='max-width: 100px !important;'>Jenis</th>
    </tr>";

    $this->data['body'] = $this->generateBodyLaporan($this->data['kas_mutasis']);

    echo view('laporan/v_cetak_landscape', $this->data);
  }
  
  private function generateBodyLaporan($arr) {
    $hasil = "";
    foreach($arr as $kas_mutasi) {
      $hasil .= "<tr>";
        $hasil .= "<td class='text-end'>".$kas_mutasi->kode_kas_mutasi."</td>";
        $hasil .= "<td>".date("Y-m-d", strtotime($kas_mutasi->tanggal_input))."</td>";
        $hasil .= "<td>".($kas_mutasi->tanggal_transaksi ? date("Y-m-d", strtotime($kas_mutasi->tanggal_transaksi)) : "-")."</td>";
        
        $hasil .= "<td>".$kas_mutasi->nama_kas."</td>";
        $hasil .= "<td class='text-end'>".number_format($kas_mutasi->nominal_perubahan,0,".", ",")."</td>";
        $hasil .= "<td class='text-end'>".number_format($kas_mutasi->nominal_awal,0,".", ",")."</td>";
        $hasil .= "<td class='text-end'>".number_format($kas_mutasi->nominal_akhir,0,".", ",")."</td>";
        $hasil .= "<td>".$kas_mutasi->keterangan."</td>";
        $hasil .= "<td>".$kas_mutasi->transaksi."</td>";
        $hasil .= "<td>".$kas_mutasi->jenis."</td>";
        
      $hasil .= "</tr>";
    }
    return $hasil;
  }
}