<?php

namespace App\Controllers\user;
use App\Controllers\BaseController;

use App\Models\OtentikasiModel;

use CodeIgniter\Files\File;

class UbahPassword extends BaseController {
  public function __construct() {
    $this->otentikasiModel = new OtentikasiModel();
  }

  public function index() {
    $this->data['jquery'] = "   
    $(document).ready(function () {
        
      });
    ";

    if($this->request->getPost('btnSimpan')) {
      $validationRule = [
          'pwd_lama'  => [
            'label' => 'Password Lama',
            'rules' => 'required',
          ],
          'pwd_baru'  => [
            'label' => 'Password Baru',
            'rules' => 'required|min_length[6]|matches[pwd_baru2]',
            'errors' => [
              'matches' => '{field} harus sesuai'
            ]
          ],

      ];
      if (! $this->validate($validationRule)) {
        $this->data['errors'] = $this->validator->getErrors();  
      } else {
        $pwd_lama = $this->request->getPost("pwd_lama");
        $pwd_baru = $this->request->getPost("pwd_baru");

        $hasil = $this->otentikasiModel->ubahPassword(session()->get('username_citra'), $pwd_lama, $pwd_baru);

        if($hasil)
          $this->data['session']->setFlashdata("toast", array("head"=>"Berhasil", "text"=>"Password telah diubah"));
        else 
          $this->data['session']->setFlashdata("toast", array("head"=>"Error", "text"=>"Password lama tidak sesuai"));

        return redirect()->to(base_url('user/ubahpassword'));

      }
    }

    echo view('backend/v_header', $this->data);    
    echo view('backend/v_menu', $this->data);    
    echo view('user/v_ubahpassword', $this->data);    
    echo view('backend/v_footer', $this->data);
  }

  
}