<?php

namespace App\Controllers\laporan;
use App\Controllers\BaseController;

use App\Models\PayrollModel;
use App\Models\PayrollDetilModel;

use CodeIgniter\Files\File;

class Payroll extends BaseController {
  var $arr_total = array();

  public function __construct() {
    $this->payrollModel = new PayrollModel();
    $this->payrollDetilModel = new PayrollDetilModel();
    $this->arr_total = array("total_gaji_pokok"=>0, "total_gaji_jabatan"=>0, "total_tunjangan"=>0
        , "total_lembur"=>0, "total_lain_lain"=>0, "total_thr"=>0, "total_gratifikasi"=>0
        , "total_rapel"=>0, "total_bpjs_kes"=>0, "total_bpjs_tk"=>0, "total_pinjaman"=>0
        , "total_gaji_net"=>0, "total_bpjs_kes_pt"=>0, "total_bpjs_tk_pt"=>0, "total_pph21"=>0
      );
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('#datatable').parent().addClass('table-responsive');
        $('#datatable').parent().parent().addClass('pe-2');
      });
    ";

   
    $where = ""; $filter = "";

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
      $where .= "payroll.status = '".$this->data['status']."'";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(payroll.tanggal_pengajuan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
      $filter .= "<h5>Periode ".date("Y-m-d", strtotime($this->data['tglawal']))." s/d ".date("Y-m-d", strtotime($this->data['tglakhir']))."</h5>";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where .= "payroll.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }
    $this->data['payrolls']=$this->payrollModel->getPayroll($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('laporan/payroll/v_payroll', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function cetak() {
    $this->data['laporan'] = "Pencatatan Jurnal Umum";
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
      $where .= "payroll.status = '".$this->data['status']."'";
      $filter .= "<h5>Status: ".$this->data['status']."</h5>";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(payroll.tanggal_pengajuan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
      $filter .= "<h5>Periode ".date("Y-m-d", strtotime($this->data['tglawal']))." s/d ".date("Y-m-d", strtotime($this->data['tglakhir']))."</h5>";
    }

    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      if(!empty($where)) $where .= " AND ";
      $where .= "payroll.kode_pt='".$this->data['session']->get('kode_pt')."'";
    }
    $this->data['payrolls']=$this->payrollModel->getPayroll($where)->find();

       

    $this->data['header'] = "<tr><th colspan='20'><div class='text-center'>
        <h1>LAPORAN PAYROLL</h1>
        <small>Tanggal Cetak: ".date("Y-m-d H:i:s")."</small>
        $filter
      </div>
      </th>
    </tr>
    <tr>
      <th style='max-width: 70px !important;'>Kode</th>
      <th style='min-width: 100px !important;'>Tgl Pengajuan</th>
      <th style='min-width: 120px !important;'>PT</th>
      <th style='max-width: 100px !important;'>Status</th>
      <th style='max-width: 100px !important;'>Kode Bon</th>
      <th style='min-width: 120px !important;'>Gaji Pokok</th>
      <th style='min-width: 120px !important;'>Gaji Jabatan Tetap</th>
      <th style='min-width: 120px !important;'>Tunjangan</th>
      <th style='min-width: 120px !important;'>Lembur</th>
      <th style='min-width: 120px !important;'>Lain-lain</th>
      <th style='min-width: 120px !important;'>THR</th>
      <th style='min-width: 120px !important;'>Gratifikasi</th>
      <th style='min-width: 120px !important;'>Rapel</th>
      <th style='min-width: 120px !important;'>BPJS Kes</th>
      <th style='min-width: 120px !important;'>BPJS TK</th>
      <th style='min-width: 120px !important;'>Pinjaman</th>
      <th style='min-width: 120px !important;'>Gaji Nett</th>
      <th style='min-width: 120px !important;'>BPJS Kes PT</th>
      <th style='min-width: 120px !important;'>BPJS TK PT</th>
      <th style='min-width: 120px !important;'>PPH21</th>
    </tr>";

    $this->data['body'] = $this->generateBodyLaporan($this->data['payrolls']);
    $this->data['foot'] = $this->generateFooterLaporan();

    echo view('laporan/v_cetak_landscape', $this->data);
  }

  private function generateFooterLaporan() {
    $hasil = "<tr class='fw-bold'>
                <td colspan='5' class='text-end'>TOTAL</td>";

    foreach($this->arr_total as $value) {
      $hasil .= "<td class='text-end'>".number_format($value,0,".", ",")."</td>";
    }
    $hasil .= "</tr>";

    return $hasil;
  }

  private function generateBodyLaporan($arr) {
    $hasil = "";
    foreach($arr as $payroll) {
      $hasil .= "<tr>";
      $hasil .= "<td class='text-end'>".$payroll->kode_payroll."</td>";
      $hasil .= "<td>
        <div>".date("Y-m-d", strtotime($payroll->tanggal_pengajuan))."</div>
        </td>";

      $hasil .= "<td>".$payroll->nama_pt."</td>";
      $hasil .= "<td>".$payroll->status."</td>";
      $hasil .= "<td class='text-end'>".$payroll->kode_bon."</td>";
      $hasil .= "<td class='text-end'>".number_format($payroll->total_gaji_pokok,0,".", ",")."</td>"; $this->arr_total["total_gaji_pokok"]+=$payroll->total_gaji_pokok;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_gaji_jabatan,0,".", ",")."</td>"; $this->arr_total["total_gaji_jabatan"]+=$payroll->total_gaji_jabatan;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_tunjangan,0,".", ",")."</td>"; $this->arr_total["total_tunjangan"]+=$payroll->total_tunjangan;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_lembur,0,".", ",")."</td>"; $this->arr_total["total_lembur"]+=$payroll->total_lembur;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_lain_lain,0,".", ",")."</td>"; $this->arr_total["total_lain_lain"]+=$payroll->total_lain_lain;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_thr,0,".", ",")."</td>"; $this->arr_total["total_thr"]+=$payroll->total_thr;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_gratifikasi,0,".", ",")."</td>"; $this->arr_total["total_gratifikasi"]+=$payroll->total_gratifikasi;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_rapel,0,".", ",")."</td>"; $this->arr_total["total_rapel"]+=$payroll->total_rapel;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_bpjs_kes,0,".", ",")."</td>"; $this->arr_total["total_bpjs_kes"]+=$payroll->total_bpjs_kes;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_bpjs_tk,0,".", ",")."</td>"; $this->arr_total["total_bpjs_tk"]+=$payroll->total_bpjs_tk;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_pinjaman,0,".", ",")."</td>"; $this->arr_total["total_pinjaman"]+=$payroll->total_pinjaman;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_gaji_net,0,".", ",")."</td>"; $this->arr_total["total_gaji_net"]+=$payroll->total_gaji_net;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_bpjs_kes_pt,0,".", ",")."</td>"; $this->arr_total["total_bpjs_kes_pt"]+=$payroll->total_bpjs_kes_pt;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_bpjs_tk_pt,0,".", ",")."</td>"; $this->arr_total["total_bpjs_tk_pt"]+=$payroll->total_bpjs_tk_pt;
      $hasil .= "<td class='text-end'>".number_format($payroll->total_pph21,0,".", ",")."</td>"; $this->arr_total["total_pph21"]+=$payroll->total_pph21;

      $hasil .= "</tr>";
    }
    return $hasil;
  }
}