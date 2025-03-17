<?php

namespace App\Controllers\bon;
use App\Controllers\BaseController;

use App\Models\BonModel;
use App\Models\BonStatusHistoriModel;
use App\Models\BonStatusModel;
use App\Models\KasModel;
use App\Models\PTModel;
use App\Models\PinjamanTagihanModel;
use App\Models\PTKaryawanModel;

use CodeIgniter\Files\File;

class Daftar extends BaseController {

  protected $data;
  protected $bonModel;
  protected $bonStatusModel;
  protected $bonStatusHistoriModel;
  protected $kasModel;
  protected $PTModel;
  protected $pinjamanTagihanModel;
  protected $ptKaryawanModel;
  protected $payrollModel;

  public function __construct() {
    $this->bonModel = new BonModel();
    $this->bonStatusModel = new BonStatusModel();
    $this->bonStatusHistoriModel = new BonStatusHistoriModel();
    $this->kasModel = new KasModel();
    $this->PTModel = new PTModel();
    $this->pinjamanTagihanModel = new PinjamanTagihanModel();
    $this->ptKaryawanModel = new PTKaryawanModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false});

        $('#datatable').parent().addClass('table-responsive');
        $('#datatable').parent().parent().addClass('pe-2');
        $('#datatable_paginate').parent().addClass('overflow-x-auto');
        
        $('.daterange').daterangepicker({ 
          'autoApply': true, 
          'drops': 'auto' 
        });  
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak membatalkan bon \'<i>'+kode+'</i>\' ?');
        });
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode_bon = $this->request->getPost('kode');
      $bons = $this->bonModel->getBon(array("bon.kode_bon"=>$kode_bon, "bon.kode_bon_status"=>0))->first();
      if(!empty($bons)) {
        $arr_data = array("kode_bon_status"=>5);
        $where = array("kode_bon"=>$kode_bon);
        //print_r($where); die();
        $this->bonModel->update($kode_bon, $arr_data);

        // hapus dari payroll
        $this->updatePayroll($kode_bon);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah dibatalkan"));
      } else {
        $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon gagal dibatalkan"));
      }
      return redirect()->to(base_url('bon/daftar'));
    }

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
    echo view('bon/v_daftar', $this->data);    
    echo view('bon/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  private function updatePayroll($kode_bon) {
    $arr_where = array("kode_bon"=>$kode_bon, "status"=>"Pengajuan Bon");
    $arr_data = array("status"=>"Tagihan", "kode_bon"=>null);
    $this->payrollModel->where($arr_where)->set($arr_data)->update();    
  }

  private function jquery() {
    $jquery = "
    $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});
      

      $('body').on('change', '#jenis_bon', function(){
        $('.opsi').val('');
        $('.opsi').attr('disabled', true);
        var opsi = $('#jenis_bon option:selected').attr('tag');
        $('.'+opsi).removeAttr('disabled');
        $('#persetujuan').val(1);
      });
      ";

    return $jquery;
  }

  public function tambah() {
    if(!in_array("ATBHBON", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('bon/daftar'));
    
    $this->data['jquery'] = $this->jquery();

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
        //$tanggal_pengajuan = $this->request->getPost('tanggal_pengajuan');
        $tanggal_pengajuan = date("Y-m-d H:i:s");
        $karyawan = $this->request->getPost('karyawan');
        $nominal = $this->request->getPost('nominal');
        $nominal = str_replace(",", "", $nominal);
        $keterangan = $this->request->getPost('keterangan');
        $kode_kas = $this->request->getPost('kas');
        $cicilan = $this->request->getPost('cicilan');
        $persetujuan = $this->request->getPost('persetujuan');
        if(empty($persetujuan)) $persetujuan=0;
        //$kas = $this->kasModel->getKas(array('kas.kode_kas'=>$kode_kas))->first();
        //$kode_bon_status = empty($kas) ? 0 : $kas->ispersetujuan;
        $kode_bon_status = BON_PENGAJUAN; //$persetujuan ? 0 : 1;

        $arr_data = array('jenis'=>$jenis_bon
            , 'kode_bon_status' => $kode_bon_status
            , 'tanggal_pengajuan'=>$tanggal_pengajuan
            , 'kode_pt_karyawan'=>$karyawan
            , 'nominal'=>$nominal
            , 'keterangan'=>$keterangan
            , 'kode_kas'=>$kode_kas
            , 'ispersetujuan'=>$persetujuan
          ); 

        switch($jenis_bon) {
        case 'Pemindahan Kas':
          $arr_data['kode_kas_tujuan'] = $this->request->getPost('kas_tujuan');
          break;
        case 'Pinjaman':
          $cicilan = $this->request->getPost('cicilan');
          $cicilan = str_replace(",","", $cicilan);
          $arr_data['cicilan'] = $cicilan;
          //echo $cicilan; die();
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

    $where = array("pt_karyawan.isaktif"=>1);
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['karyawans'] = $this->ptKaryawanModel->getPTKaryawan($where)->find();

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['kass']=$this->kasModel->getKas($where)->find();

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
    if(!in_array("ATBHBON", $this->data['session']->get('menu_citra'))) return redirect()->to(base_url('bon/daftar'));

    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('bon/daftar'));

    $where = array('bon.kode_bon'=>$kode, 'bon.kode_bon_status'=>0, 'bon.jenis <>'=>'Payroll');
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen_kas.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['bon'] = $this->bonModel->getBon($where)->first();
    if(empty($this->data['bon'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Bon tidak ditemukan"));

      return redirect()->to(base_url('bon/daftar'));
    }

    $this->data['jquery'] = $this->jquery();

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
        $kode_bon = $this->request->getPost('kode');
        $karyawan = $this->request->getPost('karyawan');
        $nominal = $this->request->getPost('nominal');
        $nominal = str_replace(",", "", $nominal);
        $keterangan = $this->request->getPost('keterangan');
        $kode_kas = $this->request->getPost('kas');
        $ispersetujuan = $this->request->getPost('persetujuan');
        if(empty($ispersetujuan)) $ispersetujuan = 0;      

        $arr_data = array('kode_pt_karyawan'=>$karyawan
            , 'nominal'=>$nominal
            , 'keterangan'=>$keterangan
            , 'kode_kas'=>$kode_kas
            , 'ispersetujuan'=>$ispersetujuan
          ); 



        switch($this->data['bon']->jenis) {
        case 'Pemindahan Kas':
          $arr_data['kode_kas_tujuan'] = $this->request->getPost('kas_tujuan');
          break;
        case 'Pinjaman':
          $cicilan = $this->request->getPost('cicilan');
          $cicilan = str_replace(",","", $cicilan);
          $arr_data['cicilan'] = $cicilan;
          // hapus dulu pinjamanTagihan
          $where = array('kode_bon'=>$kode_bon);
          $this->pinjamanTagihanModel->deletePinjamanTagihan($where);

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

            $tanggal_pengajuan = $this->data['bon']->tanggal_pengajuan;
            $tgl = date("d", strtotime($tanggal_pengajuan));
            $bln = date("m", strtotime($tanggal_pengajuan));
            $thn = date("Y", strtotime($tanggal_pengajuan));

            $data = array(
                'nominal' => $nominal_cicilan
                , 'kode_bon' => $kode_bon
                , 'tanggal_tagihan' => date("Y-m-d", mktime(0,0,0, $bln+$i,$tgl,$thn))
              );
            $this->pinjamanTagihanModel->insert($data);

            $sisa_pinjaman -= $besar_cicilan;
          }
          break;
        }
       
        /*if(empty($ispersetujuan)) {
          $arr_data['kode_bon_status']=1;

          $data = array('kode_bon'=>$kode_bon, 'kode_bon_status'=>1);
          $this->bonStatusHistoriModel->insert($data);
        }*/

        $this->bonModel->update($kode_bon, $arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Bon telah diubah"));
       
        return redirect()->to(base_url('bon/daftar'));
      }
    }

    $where = array("pt_karyawan.isaktif"=>1);
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['karyawans'] = $this->ptKaryawanModel->getPTKaryawan($where)->find();

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['kass']=$this->kasModel->getKas($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('bon/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  
}