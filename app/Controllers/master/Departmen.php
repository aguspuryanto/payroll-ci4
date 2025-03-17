<?php

namespace App\Controllers\master;
use App\Controllers\BaseController;

use App\Models\DepartmenModel;
use App\Models\PTModel;

use CodeIgniter\Files\File;

class Departmen extends BaseController {
  public function __construct() {
    $this->departmenModel = new DepartmenModel();
    $this->PTModel = new PTModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus departmen \'<i>'+nama+'</i>\' ?');
        });
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
        $this->departmenModel->delete($kode);
        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Departmen telah dihapus"));
      return redirect()->to(base_url('master/departmen'));
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['departmens']=$this->departmenModel->getDepartmen($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/departmen/v_departmen', $this->data);    
    echo view('master/departmen/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'kode_pt'  => [
            'label' => 'Nama PT',
            'rules' => 'required'
          ],
          'nama'  => [
            'label' => 'Nama Departmen',
            'rules' => 'trim|required|max_length[45]'
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $nama = $this->request->getPost('nama');
        $kode_pt = $this->request->getPost('kode_pt');
        $arr_data = array('nama'=>$nama, 'kode_pt'=>$kode_pt); 

        $this->departmenModel->insert($arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Departmen telah ditambahkan"));

        return redirect()->to(base_url('master/departmen/tambah'));
      }
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pts'] = $this->PTModel->getPT($where, "kebun.nama, pt.nama")->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/departmen/v_tambah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  public function ubah() {
    $kode = $this->request->getGet('kode');
    if(!$kode) return redirect()->to(base_url('master/departmen'));

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['departmen.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['departmen'] = $this->departmenModel->getDepartmen($where)->first();
    if(empty($this->data['departmen'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Departmen tidak ditemukan"));

      return redirect()->to(base_url('master/departmen'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      
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
        $kode_pt = $this->request->getPost('kode_pt');
        $arr_data = array('nama'=>$nama, 'kode_pt'=>$kode_pt); 

        $this->departmenModel->update($kode, $arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Departmen telah diubah"));

        return redirect()->to(base_url('master/departmen/ubah?kode='.$kode));
      }
    }
    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pts'] = $this->PTModel->getPT($where, "kebun.nama, pt.nama")->findAll();
    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/departmen/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }
}