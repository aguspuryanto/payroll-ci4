<?php

namespace App\Controllers\user;
use App\Controllers\BaseController;

use App\Models\OtentikasiModel;
use App\Models\UsersModel;
use App\Models\ProfilModel;
use App\Models\PTModel;

use CodeIgniter\Files\File;

class Daftar extends BaseController {
  public function __construct() {
    $this->usersModel = new UsersModel();
    $this->profilModel = new ProfilModel();
    $this->PTModel = new PTModel();
    $this->otentikasiModel = new OtentikasiModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        $('#datatable').DataTable({ 'responsive': false });   
        $('body').on('click', 'a.hapus', function(){
          var kode = $(this).attr('username');
          var nama = $(this).attr('nama');
          $('#hid-hapus-kode').val(kode);
          $('#pesan-hapus').html('Anda yakin hendak menghapus user \'<i>'+nama+'</i>\' ?');
        });
      });
    ";

    if($this->request->getPost('btnSimpanHapus')) {
      $kode = $this->request->getPost('kode');
      $this->usersModel->delete($kode);
      $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"User telah dihapus"));
      
      return redirect()->to(base_url('user/daftar'));
    }

    $where = array('profil.aplikasi'=>'Keuangan');
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['users.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['users']=$this->usersModel->getUsers($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('user/daftar/v_daftar', $this->data);    
    echo view('user/daftar/v_fragment_hapus', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  public function tambah() {
    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});
      
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'username'  => [
            'label' => 'Username',
            'rules' => 'required|max_length[45]|is_unique[users.username]',
            'errors' => ['is_unique'=>"{field} sudah ada"]
          ], 
          'nama'  => [
            'label' => 'Nama',
            'rules' => 'required|max_length[45]',
          ],
          'password'  => [
            'label' => 'Password',
            'rules' => 'required|min_length[6]|matches[password2]',
            'errors' => ['matches'=>"{field} tidak cocok",
                          'min_length' => '{field} harus min. {param} karakter'
                        ]
          ],
      ];
      if (! $this->validate($validationRule)) {
          $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $nama = $this->request->getPost('nama');
        $kode_pt = $this->request->getPost('pt');
        $kode_profil = $this->request->getPost('profil');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $salt = $this->otentikasiModel->generateSalt();

        $arr_data = array('nama'=>$nama
                        , 'kode_profil'=>$kode_profil
                        , 'username'=>$username, 'salt'=>$salt
                        , 'password'=>$this->otentikasiModel->encryptPassword($password, $salt)
                      ); 
        if($kode_pt) $arr_data['kode_pt']=$kode_pt;

        $this->usersModel->insert($arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"User telah ditambahkan"));

        return redirect()->to(base_url('user/daftar/tambah'));
      }
    }

    $where = array('profil.aplikasi'=>'Keuangan');
    $this->data['profils'] = $this->profilModel->where($where)->find();

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pts'] = $this->PTModel->getPT($where)->find();

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('user/daftar/v_tambah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }

  public function ubah() {
    $kode = $this->request->getGet('username');
    if(!$kode) return redirect()->to(base_url('user/daftar'));

    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['user']=$this->usersModel->getUsers($where)->first();
    
    if(empty($this->data['user'])) {
      $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"User tidak ditemukan"));

      return redirect()->to(base_url('user/daftar'));
    }

    $this->data['jquery'] = "
      $('.select2').select2({ theme: 'bootstrap-5' });
      $('input.number').priceFormat({centsLimit: 0, prefix: ''});
    ";

    if($this->request->getPost('btnSimpan')) {
      $username = $this->request->getPost('username');
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
        $kode_profil = $this->request->getPost('profil');
        $username = $this->request->getPost('username');
        $kode_pt = $this->request->getPost('pt');
        $arr_data = array('nama'=>$nama, 'kode_profil'=>$kode_profil); 
        if($kode_pt) $arr_data['kode_pt']=$kode_pt;

        $this->usersModel->update($username, $arr_data);

        $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"User telah diubah"));

        return redirect()->to(base_url('user/daftar/ubah?username='.$username));
      }
    }
    $where = array('profil.aplikasi'=>'Keuangan');
    $this->data['profils'] = $this->profilModel->where($where)->find();
    $where = array();
    if(!$this->data['session']->get('isadmin_citra') && $this->data['session']->get('kode_pt')) {
      $where['pt.kode_pt'] = $this->data['session']->get('kode_pt');
    }
    $this->data['pts'] = $this->PTModel->getPT($where)->find();
    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('user/daftar/v_ubah', $this->data);  
    echo view('backend/v_footer', $this->data);
  }
}