<?php

namespace App\Controllers\master;
use App\Controllers\BaseController;

use App\Models\PTKaryawanModel;
use App\Models\AfdelingModel;
use App\Models\AgamaModel;
use App\Models\KaryawanJenisModel;
use App\Models\DepartmenModel;

use CodeIgniter\Files\File;

class KaryawanPT extends BaseController {
  public function __construct() {
    $this->afdelingModel = new AfdelingModel();
    $this->agamaModel = new AgamaModel();
    $this->karyawanJenisModel = new KaryawanJenisModel();
    $this->departmenModel = new DepartmenModel();
    $this->PTKaryawanModel = new PTKaryawanModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus karyawan \'<i>'+nama+'</i>\' ?');
        });

        $('#datatable').DataTable({ 'responsive': false});

        $('#datatable').parent().addClass('table-responsive');
        $('#datatable').parent().parent().addClass('pe-2');
        $('#datatable_paginate').parent().addClass('overflow-x-auto');
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
      $arr_data = array('isaktif'=>0);
      $this->PTKaryawanModel->update($kode, $arr_data);
      $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Karyawan telah dihapus"));
      
      return redirect()->to(base_url('master/karyawanpt'));
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }

    $this->data['karyawans']=$this->PTKaryawanModel->getPTKaryawan($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/karyawanpt/v_karyawanpt', $this->data);    
    echo view('master/karyawanpt/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function ajaxAfdeling() {
    if ($this->request->isAJAX()) {
      $hasil = "<option value=''>-- Pilih Afdeling --</option>";
      $id_kebun = $this->request->getPost('id_kebun');
      $where = array('kebun.id'=>$id_kebun );
      $afdelings = $this->afdelingModel->getAfdeling($where)->find();
      foreach($afdelings as $afdeling) {                  
        $hasil .= "<option value='".$afdeling->id."' >".$afdeling->nama."</option>";
      }
      echo $hasil;
    }
  }

  private function jquery() {
    $jquery = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});

      $('body').on('change', '#kode_departmen', function(){
        var id_kebun = $('#kode_departmen option:selected').attr('id_kebun');
        $.post('".site_url('master/karyawanpt/ajaxAfdeling')."', {id_kebun: id_kebun})
        .done(function(data){
          $('#afdeling').html(data);
        });
      });
    ";
    return $jquery;
  }

  public function tambah() {
    $this->data['jquery'] = $this->jquery();

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'nama_lengkap'  => [
            'label' => 'Nama Lengkap',
            'rules' => 'required|max_length[75]',
          ],
          'nik'  => [
            'label' => 'NIK',
            'rules' => 'required|max_length[16]|is_unique[pt_karyawan.nik]',
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $afdeling = $this->request->getPost('afdeling');
        $no_urut=str_replace(",","",$this->request->getPost('no_urut'));

        $arr_data = array('nama_lengkap'=>$this->request->getPost('nama_lengkap')
          , 'no_urut'=>$no_urut
          , 'nama_alias'=>$this->request->getPost('nama_alias')
          , 'kode_departmen'=>$this->request->getPost('kode_departmen')
          , 'kode_karyawan_jenis'=>$this->request->getPost('karyawan_jenis')
          , 'kode_agama'=>$this->request->getPost('agama')
          , 'jenis_kelamin'=>$this->request->getPost('jenis_kelamin')
          , 'nik'=>$this->request->getPost('nik')
          , 'nip'=>$this->request->getPost('nip')
          , 'npwp'=>$this->request->getPost('npwp')
          , 'alamat'=>$this->request->getPost('alamat')
          , 'telepon'=>$this->request->getPost('telepon')
          , 'pendidikan'=>$this->request->getPost('pendidikan')
          , 'jabatan'=>$this->request->getPost('jabatan')
          , 'rekening_no'=>$this->request->getPost('rekening_no')
          , 'keterangan'=>$this->request->getPost('keterangan')
          , 'status_perkawinan'=>$this->request->getPost('status_perkawinan')
          , 'bpjs_kes'=>$this->request->getPost('bpjs_kes')
          , 'bpjs_tk'=>$this->request->getPost('bpjs_tk')
          , 'keterangan'=>$this->request->getPost('keterangan')
          , 'tanggal_bekerja'=>date("Y-m-d", strtotime($this->request->getPost('mulai_bekerja')))
          , 'tempat_lahir'=>$this->request->getPost('tempat_lahir')
          , 'tanggal_lahir'=>date("Y-m-d", strtotime($this->request->getPost('tanggal_lahir')))
          //, 'biaya_bpjs_kes'=>str_replace(",","",$this->request->getPost('biaya_bpjs_kes'))
          //, 'biaya_bpjs_tk'=>str_replace(",","",$this->request->getPost('biaya_bpjs_tk'))
          , 'gaji_pokok'=>str_replace(",","",$this->request->getPost('gaji_pokok'))
          , 'gaji_jabatan_tetap'=>str_replace(",","",$this->request->getPost('gaji_jabatan_tetap'))
          , 'ptkp'=>$this->request->getPost('ptkp')

        ); 

        if($afdeling) $arr_data['id_afdeling']=$afdeling;

        $this->PTKaryawanModel->insert($arr_data);
        $kode_pt_karyawan = $this->PTKaryawanModel->getInsertID();
        $this->PTKaryawanModel->geserSeq($kode_pt_karyawan, $no_urut);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Karyawan telah ditambahkan"));

        return redirect()->to(base_url('master/karyawanpt/tambah'));
      }
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }

    $this->data['departmens'] = $this->departmenModel->getDepartmen($where, "pt.nama, departmen.nama")->findAll();
    //$this->data['afdelings'] = $this->afdelingModel->findAll();
    $this->data['agamas'] = $this->agamaModel->findAll();
    $this->data['karyawan_jeniss'] = $this->karyawanJenisModel->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/karyawanpt/v_tambah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  public function ubah() {
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('master/karyawanpt'));

    $where = array('pt_karyawan.kode_pt_karyawan'=>$kode);
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }

    $this->data['karyawan'] = $this->PTKaryawanModel->getPTKaryawan($where)->first();
    if(empty($this->data['karyawan'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Karyawan tidak ditemukan"));

      return redirect()->to(base_url('master/karyawanpt'));
    }

    $this->data['jquery'] = $this->jquery();

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'nama_lengkap'  => [
            'label' => 'Nama',
            'rules' => 'required|max_length[40]',
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $afdeling = $this->request->getPost('afdeling');
        $no_urut=str_replace(",","",$this->request->getPost('no_urut'));

        $arr_data = array('nama_lengkap'=>$this->request->getPost('nama_lengkap')
          , 'no_urut'=>$no_urut
          , 'nama_alias'=>$this->request->getPost('nama_alias')
          , 'kode_departmen'=>$this->request->getPost('kode_departmen')
          , 'kode_karyawan_jenis'=>$this->request->getPost('karyawan_jenis')
          , 'kode_agama'=>$this->request->getPost('agama')
          , 'jenis_kelamin'=>$this->request->getPost('jenis_kelamin')
          , 'nik'=>$this->request->getPost('nik')
          , 'nip'=>$this->request->getPost('nip')
          , 'npwp'=>$this->request->getPost('npwp')
          , 'alamat'=>$this->request->getPost('alamat')
          , 'telepon'=>$this->request->getPost('telepon')
          , 'pendidikan'=>$this->request->getPost('pendidikan')
          , 'jabatan'=>$this->request->getPost('jabatan')
          , 'rekening_no'=>$this->request->getPost('rekening_no')
          , 'keterangan'=>$this->request->getPost('keterangan')
          , 'status_perkawinan'=>$this->request->getPost('status_perkawinan')
          , 'bpjs_kes'=>$this->request->getPost('bpjs_kes')
          , 'bpjs_tk'=>$this->request->getPost('bpjs_tk')
          , 'keterangan'=>$this->request->getPost('keterangan')
          , 'tanggal_bekerja'=>date("Y-m-d", strtotime($this->request->getPost('mulai_bekerja')))
          , 'tempat_lahir'=>$this->request->getPost('tempat_lahir')
          , 'tanggal_lahir'=>date("Y-m-d", strtotime($this->request->getPost('tanggal_lahir')))
          //, 'biaya_bpjs_kes'=>str_replace(",","",$this->request->getPost('biaya_bpjs_kes'))
          //, 'biaya_bpjs_tk'=>str_replace(",","",$this->request->getPost('biaya_bpjs_tk'))
          , 'gaji_pokok'=>str_replace(",","",$this->request->getPost('gaji_pokok'))
          , 'gaji_jabatan_tetap'=>str_replace(",","",$this->request->getPost('gaji_jabatan_tetap'))
          , 'ptkp'=>$this->request->getPost('ptkp')

        ); 

        if($afdeling) $arr_data['id_afdeling']=$afdeling;

        $kode_pt_karyawan = $this->request->getPost('kode');

        $this->PTKaryawanModel->update($kode_pt_karyawan, $arr_data);
        $this->PTKaryawanModel->geserSeq($kode_pt_karyawan, $no_urut);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Karyawan telah diubah"));

        return redirect()->to(base_url('master/karyawanpt/ubah?kode='.$kode));
      }
    }
    
    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['departmens'] = $this->departmenModel->getDepartmen($where, "pt.nama, departmen.nama")->findAll();
    $this->data['afdelings'] = $this->afdelingModel->findAll();
    $this->data['agamas'] = $this->agamaModel->findAll();
    $this->data['karyawan_jeniss'] = $this->karyawanJenisModel->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/karyawanpt/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }
}