<?php

namespace App\Controllers\bon;
use App\Controllers\BaseController;

use App\Models\BonModel;
use App\Models\BonStatusHistoriModel;
use App\Models\KasModel;
use App\Models\KasMutasiModel;
use App\Models\PTKaryawanModel;
use App\Models\PencatatanJurnalUmumModel;

use CodeIgniter\Files\File;

define("WIDTH", 650);
define("HEIGHT", 650);

class Realisasi extends BaseController {
  public function __construct() {
    $this->bonModel = new BonModel();
    $this->bonStatusHistoriModel = new BonStatusHistoriModel();
    $this->kasModel = new KasModel();
    $this->kasMutasiModel = new KasMutasiModel();
    $this->ptKaryawanModel = new PTKaryawanModel();
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

    $where = "";
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
      $where .= "DATE(bon.tanggal_realisasi) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
    }

    $this->data['bons']=$this->bonModel->getBon($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/realisasi/v_realisasi', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    if(!in_array("AREABON", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('bon/daftar'));

    $kode = $this->request->getGet('kode');
    if(empty($kode)) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon tidak dikenali"));
      return redirect()->to(site_url('bon/daftar'));
    }
    // $where = "bon.kode_bon='$kode' AND (bon.kode_bon_status=".BON_PERSETUJUAN_DIREKSI." OR (bon.kode_bon_status=".BON_PENCATATAN_ACCOUNTING." AND bon.ispersetujuan=0))";
    $where = "bon.kode_bon='$kode' AND (bon.kode_bon_status='1' OR (bon.kode_bon_status='2' AND bon.ispersetujuan=0))";
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
        $arr_data = array('kode_bon_status' => 3, 'tanggal_realisasi'=>date('Y-m-d H:i:s')); 
        $kode_bon = $this->request->getPost('kode_bon');
        $where = array('kode_bon'=>$kode_bon);        

        $img = $this->request->getFile('bukti');

        if($img->isValid()) {
          if (! $img->hasMoved()) {
            $url = $img->getRandomName();
            $arr_data['bukti_realisasi'] = $url;
            $img->move("./assets/realisasi/",$url,true);

            $fileInfo = getimagesize("./assets/realisasi/".$url);
            if(!empty($fileInfo)) 
              if($fileInfo[0] > WIDTH || $fileInfo[1]>HEIGHT)
                $this->resizeImage($url);
            
            switch($this->data['bon']->jenis) {
              case 'Pemindahan Kas':
                break;
            }
          }
        }

        $this->bonModel->update($where, $arr_data);

        $arr_data = array('kode_bon'=>$kode_bon, 'kode_bon_status'=>3);
        $this->bonStatusHistoriModel->insert($arr_data);

        // kas mutasi
        $this->updateKasMutasi($kode_bon);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah disetujui"));
     
        return redirect()->to(base_url('bon/daftar?status=3'));
      
    }

    $this->data['historis'] = $this->bonStatusHistoriModel->getBonStatusHistori(array('bon_status_histori.kode_bon'=>$kode))->find();
    
    $where = array('bon.kode_bon'=>$kode);
    $this->data['catats'] = $this->pencatatanJurnalUmumModel->getPencatatanJurnalUmum($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/realisasi/v_tambah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  private function updateKasMutasi($kode_bon) {
    $bon = $this->bonModel->getBon(array('bon.kode_bon'=>$kode_bon, 'bon.kode_bon_status'=>3))->first();
    if(empty($bon)) return;

    $kas = $this->kasModel->getKas(array('kas.kode_kas'=>$bon->kode_kas))->first();
    if(empty($kas)) return;

    $nominal_awal = $kas->nominal;
    $nominal_akhir = $kas->nominal-$bon->nominal;

    $arr_data = array(
      'kode_kas'  => $bon->kode_kas
      , 'tanggal_input'=>date('Y-m-d')
      , 'tanggal_transaksi'=>date('Y-m-d')
      , 'nominal_perubahan'=>$bon->nominal
      , 'nominal_awal'=>$nominal_awal
      , 'nominal_akhir'=>$nominal_akhir
      , 'keterangan'=>'Mutasi bon: '.$kode_bon
      , 'transaksi'=>'Bon'
      , 'jenis' => 'Keluar'
    );
    $this->kasMutasiModel->insert($arr_data);

    if($bon->jenis=='Pemindahan Kas') {
      $kasTujuan = $this->kasModel->getKas(array('kas.kode_kas'=>$bon->kode_kas_tujuan))->first();
      //echo $this->kasModel->getLastQuery(); die();
      if(empty($kasTujuan)) return;

      $nominal_awal = $kasTujuan->nominal;
      $nominal_akhir = $kasTujuan->nominal+$bon->nominal;

      $arr_data = array(
        'kode_kas'  => $bon->kode_kas
        , 'tanggal_input'=>date('Y-m-d')
        , 'tanggal_transaksi'=>date('Y-m-d')
        , 'nominal_perubahan'=>$bon->nominal
        , 'nominal_awal'=>$nominal_awal
        , 'nominal_akhir'=>$nominal_akhir
        , 'keterangan'=>'Mutasi bon: '.$kode_bon
        , 'transaksi'=>'Bon'
        , 'jenis' => 'Masuk'
      );
      $this->kasMutasiModel->insert($arr_data);
    }
  }

  private function resizeImage($gambar_url) {
    $path = './assets/realisasi/'.$gambar_url;
    if(!empty($gambar_url) && file_exists($path)) {
      $image = \Config\Services::image();
      $image->withFile($path)
            ->resize(WIDTH, HEIGHT, true)
            ->save($path);
    }
  }

  public function ubah() {
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('adminku/lainlain/afiliasi'));

    $this->data['afiliasi'] = $this->afiliasiModel->find($kode);
    if(empty($this->data['afiliasi'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Afiliasi tidak ditemukan"));

      return redirect()->to(base_url('adminku/lainlain/afiliasi'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      tinymce.init({
        selector: 'textarea',
        height: 300,
        menubar: false,
        plugins: 
          'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount'
        ,
        toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
      });
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'nama'  => [
            'label' => 'Nama',
            'rules' => 'required|max_length[40]',
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $nama = $this->request->getPost('nama');
        $deskripsi = $this->request->getPost('deskripsi');
        $url = $this->request->getPost('url');
        $arr_data = array('nama'=>$nama, 'url'=>$url, 'deskripsi'=>$deskripsi); 

        $this->afiliasiModel->update($kode, $arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Afiliasi telah diubah"));

        return redirect()->to(base_url('adminku/lainlain/afiliasi/ubah?kode='.$kode));
      }
    }
    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('backend/lainlain/afiliasi/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }
}