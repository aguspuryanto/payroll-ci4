<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use App\Models\UsersModel;
use App\Models\HakAksesModel;
use App\Models\OtentikasiModel;

class Login extends BaseController {

  private $usersModel;
  private $hakAksesModel;
  private $otentikasiModel;

  public function __construct() {
    $this->usersModel = new UsersModel();
    $this->hakAksesModel = new HakAksesModel();
    $this->otentikasiModel = new OtentikasiModel();
  }

  private function arrHakAkses($kode_profil) {
    $hasil = array();
    $haks = $this->hakAksesModel->where('kode_profil', $kode_profil)->findAll();
    foreach($haks as $hak) {
      $hasil[]=$hak->kode_menu;
    }
    return $hasil;
  }

  public function index() {

    if($this->request->getPost('btn_signin')) {
      $hashPassword = '$2y$10$RKFfunqN/gdvumq5Q9/5eebwB0gfY.PLPQGJZqmii8knikDRuqIZi';
      $username = $this->request->getPost('username');
      $plainPassword = $this->request->getPost('password');

      $resultLogin = $this->otentikasiModel->doLogin($username, $plainPassword);
      if($resultLogin === 'error') {
        $this->data['session']->setFlashdata('notif_login', 'Salah User ID/Password');
        return redirect()->to(current_url());     
      } else if($resultLogin === true) {
        return redirect()->to(site_url('home'));
      } 
    }

      echo view('backend/v_header', $this->data);    
      echo view('backend/v_login', $this->data);
      echo view('backend/v_footer', $this->data);
  }

  public function logout() {
    session_destroy();
    return redirect()->to(base_url('login'));
  }
}