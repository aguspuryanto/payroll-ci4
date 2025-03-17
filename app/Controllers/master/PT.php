<?php

namespace App\Controllers\master;
use App\Controllers\BaseController;

use App\Models\PTModel;
use App\Models\KebunModel;

use CodeIgniter\Files\File;

class PT extends BaseController {
  public function __construct() {
    $this->PTModel = new PTModel();
    $this->kebunModel = new KebunModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('kode');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus PT \'<i>'+nama+'</i>\' ?');
        });
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
      
      $this->PTModel->delete($kode);
      $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"PT telah dihapus"));
     
      return redirect()->to(base_url('master/pt'));
    }

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pts']=$this->PTModel->getPT($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/pt/v_pt', $this->data);    
    echo view('master/pt/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });      
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'nama'  => [
            'label' => 'Nama PT',
            'rules' => 'required|max_length[100]|is_unique[pt.nama]',
            'errors' => ['is_unique' => "{field} '{value}' sudah ada"]
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $nama = $this->request->getPost('nama');
        $id_kebun = $this->request->getPost('kebun');
        $arr_data = array('nama'=>$nama); 
        if(!empty($id_kebun)) $arr_data['id_kebun'] = $id_kebun;

        $this->PTModel->insert($arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"PT telah ditambahkan"));

        return redirect()->to(base_url('master/pt/tambah'));
      }
    }
    $this->data['kebuns'] = $this->kebunModel->findAll();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/pt/v_tambah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  public function ubah() {
    $kode_pt = $this->request->getGet('kode');
    if(!$kode_pt) return redirect()->to(base_url('master/pt'));

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pt'] = $this->PTModel->getPT($where)->first();
    if(empty($this->data['pt'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"PT tidak ditemukan"));

      return redirect()->to(base_url('master/pt'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'nama'  => [
            'label' => 'Nama',
            'rules' => "required|max_length[40]|is_unique[pt.nama, kode_pt, $kode_pt]",
            'errors' => ['is_unique'=> "{field} PT sudah ada"]
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $nama = $this->request->getPost('nama');
        $kebun = $this->request->getPost('kebun');
        if(!$kebun) $kebun=null;
        $arr_data = array('nama'=>$nama, 'id_kebun'=>$kebun); 

        $this->PTModel->update($kode_pt, $arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"PT telah diubah"));

        return redirect()->to(base_url('master/pt/ubah?kode='.$kode_pt));
      }
    }
    $this->data['kebuns'] = $this->kebunModel->findAll();
    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('master/pt/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }
}