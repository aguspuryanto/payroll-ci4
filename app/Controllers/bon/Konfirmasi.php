<?php

namespace App\Controllers\bon;
use App\Controllers\BaseController;

use App\Models\BonModel;
use App\Models\BonStatusHistoriModel;
use App\Models\KasModel;
use App\Models\PinjamanTagihanModel;
use App\Models\PayrollModel;
use App\Models\PayrollDetilModel;
use App\Models\PTKaryawanModel;
use App\Models\PencatatanJurnalUmumModel;

use CodeIgniter\Files\File;

class Konfirmasi extends BaseController {
  public function __construct() {
    $this->bonModel = new BonModel();
    $this->bonStatusHistoriModel = new BonStatusHistoriModel();
    $this->kasModel = new KasModel();
    $this->pinjamanTagihanModel = new PinjamanTagihanModel();
    $this->ptKaryawanModel = new PTKaryawanModel();
    $this->payrollModel = new PayrollModel();
    $this->payrollDetilModel = new PayrollDetilModel();
    $this->pencatatanJurnalUmumModel = new PencatatanJurnalUmumModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus bon \'<i>'+nama+'</i>\' ?');
        });
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
      $afiliasis = $this->afiliasiModel->find($kode);
      if(!empty($afiliasis)) {
        $this->afiliasiModel->geserSeqSebelumDelete($kode);
        $this->afiliasiModel->delete($kode);
        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah dihapus"));
      } else {
        $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon gagal dihapus"));
      }
      return redirect()->to(base_url('adminku/lainlain/afiliasi'));
    }

    $where = array();

    $this->data['bons']=$this->bonModel->getBon($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/v_daftar', $this->data);    
    echo view('bon/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    if(!in_array("AREABON", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('bon/daftar'));
    
    $kode = $this->request->getGet('kode');
    if(empty($kode)) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon tidak dikenali"));
      return redirect()->to(site_url('bon/daftar'));
    }
    $where = array('bon.kode_bon'=>$kode, 'bon.kode_bon_status'=>3);
    $this->data['bon'] = $this->bonModel->getBon($where)->first();
    if(empty($this->data['bon'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon tidak dikenali"));
      return redirect()->to(site_url('bon/daftar'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});
    ";

    if($this->request->getPost('btnSimpan')) {
      $arr_data = array('kode_bon_status' => 4, 'tanggal_konfirmasi'=>date("Y-m-d H:i:s")); 
      $kode_bon = $this->request->getPost('kode_bon');
      $where = array('kode_bon'=>$kode_bon);
      $this->bonModel->update($where, $arr_data);

      $arr_data = array('kode_bon'=>$kode_bon, 'kode_bon_status'=>4);
      $this->bonStatusHistoriModel->insert($arr_data);

      if($this->data['bon']->jenis=="Payroll") {
        $this->updatePayroll($kode_bon);
      }

      $this->updatePencatatanJurnalUmum($kode_bon);

      $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah dikonfirmasi"));
      
      return redirect()->to(base_url('bon/daftar?status=4'));
    }

    $this->data['historis'] = $this->bonStatusHistoriModel->getBonStatusHistori(array('bon_status_histori.kode_bon'=>$kode))->find();
    
    $where = array('bon.kode_bon'=>$kode);
    $this->data['catats'] = $this->pencatatanJurnalUmumModel->getPencatatanJurnalUmum($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/konfirmasi/v_tambah_konfirmasi', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  private function updatePencatatanJurnalUmum($kode_bon) {
    $arr_where = array("kode_bon"=>$kode_bon, "status"=>"Pencatatan");
    $arr_data = array("status"=>"Selesai", "tanggal_transaksi"=>date("Y-m-d H:i:s"));
    $this->pencatatanJurnalUmumModel->where($arr_where)->set($arr_data)->update();
  }

  private function updatePayroll($kode_bon) {
    $arr_where = array("kode_bon"=>$kode_bon, "status"=>"Pengajuan Bon");
    $arr_data = array("status"=>"Lunas", "tanggal_lunas"=>date("Y-m-d H:i:s"));
    $this->payrollModel->where($arr_where)->set($arr_data)->update();
    $this->updatePinjamanKaryawan($kode_bon);
  }

  private function updatePinjamanKaryawan($kode_bon) {
    $arr_where = array("payroll.kode_bon"=>$kode_bon, "payroll.status"=>"Lunas", 'payroll_detil.kode_pinjaman_tagihan <>'=>null);
    $detils = $this->payrollDetilModel->getPayrollDetil($arr_where)->find();
    if(!empty($detils)) {
      foreach($detils as $detil) {
        $where = array('kode_pinjaman_tagihan'=>$detil->kode_pinjaman_tagihan, 'status'=>'Tagihan');
        $arr_data = array('status'=>'Lunas', 'kode_bon_payroll'=>$kode_bon);
        $this->pinjamanTagihanModel->where($where)->set($arr_data)->update();
      }
    }
  }

}