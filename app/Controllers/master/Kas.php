<?php

namespace App\Controllers\master;
use App\Controllers\BaseController;

use App\Models\DepartmenModel;
use App\Models\KasModel;

use CodeIgniter\Files\File;

class Kas extends BaseController {
  public function __construct() {
    $this->kasModel = new KasModel();
    $this->departmenModel = new DepartmenModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus kas \'<i>'+nama+'</i>\' ?');
        });
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
      $this->kasModel->delete($kode);
      $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Kas telah dihapus"));
      
      return redirect()->to(base_url('master/kas'));
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['kass']=$this->kasModel->getKas($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/kas/v_kas', $this->data);    
    echo view('master/kas/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});
      
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'nama'  => [
            'label' => 'Nama',
            'rules' => 'required|max_length[45]',
          ],
      ];
    if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $nama = $this->request->getPost('nama');
        $kode_departmen = $this->request->getPost('kode_departmen');
        $nominal = $this->request->getPost('nominal');
        $nominal=str_replace(",", "", $nominal);
        $arr_data = array('nama'=>$nama, 'kode_departmen'=>$kode_departmen, 'nominal'=>$nominal); 

        $this->kasModel->insert($arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Kas telah ditambahkan"));

        return redirect()->to(base_url('master/kas/tambah'));
      }
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['departmens'] = $this->departmenModel->getDepartmen($where, "pt.nama, departmen.nama")->findAll();
    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/kas/v_tambah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  public function ubah() {
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('master/kas'));

    $where = array('kas.kode_kas'=>$kode);
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['kas'] = $this->kasModel->getKas($where)->first();
    if(empty($this->data['kas'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Kas tidak ditemukan"));

      return redirect()->to(base_url('master/kas'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});
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
        $kode_departmen = $this->request->getPost('kode_departmen');
        $nominal = $this->request->getPost('nominal');
        $nominal = str_replace(",", "", $nominal);
        $arr_data = array('nama'=>$nama, 'kode_departmen'=>$kode_departmen, 'nominal'=>$nominal); 

        $this->kasModel->update($kode, $arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Kas telah diubah"));

        return redirect()->to(base_url('master/kas/ubah?kode='.$kode));
      }
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['departmens'] = $this->departmenModel->getDepartmen($where, "pt.nama, departmen.nama")->findAll();
    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/kas/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }
}