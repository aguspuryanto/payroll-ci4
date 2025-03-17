<?php

namespace App\Controllers\pinjaman;
use App\Controllers\BaseController;

use App\Models\BonModel;
use App\Models\BonStatusHistoriModel;
use App\Models\KasModel;
use App\Models\PTModel;
use App\Models\PinjamanTagihanModel;
use App\Models\PTKaryawanModel;

use CodeIgniter\Files\File;

class Tagihan extends BaseController {
  public function __construct() {
    $this->bonModel = new BonModel();
    $this->bonStatusHistoriModel = new BonStatusHistoriModel();
    $this->kasModel = new KasModel();
    $this->PTModel = new PTModel();
    $this->pinjamanTagihanModel = new PinjamanTagihanModel();
    $this->ptKaryawanModel = new PTKaryawanModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
       
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
      $where .= "pinjaman_tagihan.status = '".$this->data['status']."'";
    }
    if(!$this->data['abaikan'] ) {
      if(!empty($where)) $where .= " AND ";
      $where .= "DATE(pinjaman_tagihan.tanggal_tagihan) between '".date("Y-m-d", strtotime($this->data['tglawal']))."' AND '".date("Y-m-d", strtotime($this->data['tglakhir']))."'";
    }

    $this->data['tagihans']=$this->pinjamanTagihanModel->getPinjamanTagihan($where)->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('pinjaman/v_tagihan', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});

      $('body').on('change', '#jenis_bon', function(){
        $('.opsi').val('');
        $('.opsi').attr('disabled', true);
        var opsi = $('#jenis_bon option:selected').attr('tag');
        $('.'+opsi).removeAttr('disabled');
      });
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'karyawan'  => [
            'label' => 'Karyawan',
            'rules' => 'required',
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $jenis_bon = $this->request->getPost('jenis_bon');
        $tanggal_pengajuan = $this->request->getPost('tanggal_pengajuan');
        $tanggal_pengajuan = date("Y-m-d", strtotime($tanggal_pengajuan));
        $karyawan = $this->request->getPost('karyawan');
        $nominal = $this->request->getPost('nominal');
        $nominal = str_replace(",", "", $nominal);
        $keterangan = $this->request->getPost('keterangan');
        $kode_kas = $this->request->getPost('kas');
        $cicilan = $this->request->getPost('cicilan');
        $kas = $this->kasModel->getKas(array('kas.kode_kas'=>$kode_kas))->first();
        $kode_bon_status = empty($kas) ? 0 : $kas->ispersetujuan;

        $arr_data = array('jenis'=>$jenis_bon
            , 'kode_bon_status' => $kode_bon_status
            , 'tanggal_pengajuan'=>$tanggal_pengajuan
            , 'kode_pt_karyawan'=>$karyawan
            , 'nominal'=>$nominal
            , 'keterangan'=>$keterangan
            , 'kode_kas'=>$kode_kas
          ); 

        switch($jenis_bon) {
        case 'Pemindahan Kas':
          $arr_data['kode_kas_tujuan'] = $this->request->getPost('kas_tujuan');
          break;
        case 'Pinjaman':
          $cicilan = $this->request->getPost('cicilan');
          $cicilan = str_replace(",","", $cicilan);
          $arr_data['cicilan'] = $cicilan;
          break;
        }

        $this->bonModel->insert($arr_data);
        $kode_bon = $this->bonModel->getInsertID();

        if($kode_bon) {
          $arr_data = array('kode_bon'=>$kode_bon, 'kode_bon_status'=>$kode_bon_status);
          $this->bonStatusHistoriModel->insert($arr_data);

          switch($jenis_bon) {
            case 'Pinjaman':
              // get bunga
              $persen_bunga = $this->getBungaPinjaman ($karyawan);
              $sisa_pinjaman = $nominal;
              $besar_cicilan = ceil($nominal / $cicilan);
              $bunga = ($persen_bunga/100) * $nominal;
              for($i=1; $i<=$cicilan+1; $i++) {

                if($i == $cicilan+1) {
                  $nominal_cicilan = $bunga;
                } else if($i == $cicilan) {
                  $nominal_cicilan = min($sisa_pinjaman, $besar_cicilan);
                } else {
                  $nominal_cicilan = $besar_cicilan;
                }

                $tgl = date("d", strtotime($tanggal_pengajuan));
                $bln = date("m", strtotime($tanggal_pengajuan));
                $thn = date("Y", strtotime($tanggal_pengajuan));

                $arr_data = array(
                    'nominal' => $nominal_cicilan
                    , 'kode_bon' => $kode_bon
                    , 'tanggal_tagihan' => date("Y-m-d", mktime(0,0,0, $bln+$i,$tgl,$thn))
                  );
                $this->pinjamanTagihanModel->insert($arr_data);

                $sisa_pinjaman -= $besar_cicilan;
              }
              break;
          }

          $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah ditambahkan"));
        } else {
          $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon gagal ditambahkan"));
        }
        return redirect()->to(base_url('bon/daftar/tambah'));
      }
    }

    $this->data['karyawans'] = $this->ptKaryawanModel->getPTKaryawan(array("pt_karyawan.isaktif"=>1))->find();
    $this->data['kass'] = $this->kasModel->getKas()->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/v_tambah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  private function getBungaPinjaman ($kode_pt_karyawan) {
    $kary = $this->ptKaryawanModel->getPTKaryawan(array('kode_pt_karyawan'=>$kode_pt_karyawan))->first();
    $bunga = 0;
    if(!empty($kary)) {
      $pt = $this->PTModel->find($kary->kode_pt);
      if(!empty($pt)) $bunga = $pt->bunga_pinjaman;
    }
    return $bunga;
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