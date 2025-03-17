<?php

namespace App\Controllers\kas;
use App\Controllers\BaseController;

use App\Models\CoaModel;
use App\Models\KasModel;
use App\Models\KasMutasiModel;
use App\Models\PTKaryawanModel;

use CodeIgniter\Files\File;

class Mutasi extends BaseController {
  public function __construct() {
    $this->coaModel = new CoaModel();
    $this->kasModel = new KasModel();
    $this->kasMutasiModel = new KasMutasiModel();
    $this->ptKaryawanModel = new PTKaryawanModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus Mutasi Kas \'<i>'+nama+'</i>\' ?');
        });
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
    echo view('kas/mutasi/v_mutasi', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

}