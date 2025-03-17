<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\UsersModel;
use App\Models\HakAksesModel;

class OtentikasiModel extends Model {
  public function __construct() {
    $this->userModel = new UsersModel();
    $this->hakAksesModel = new HakAksesModel();
  }

  protected $table            = 'users';
  protected $primaryKey       = 'username';
  protected $useAutoIncrement = false;
  protected $returnType       = 'object';
  protected $allowedFields    = ['username', 'kode_profil', 'id_kebun', 'nama', 'email', 'password', 'salt', 'reser_code', 'reset_expired', 'isadmin', 'isaktif'];

  // Dates
  //protected $useTimestamps = true;
  //protected $createdField  = 'created_at';
  //protected $updatedField  = 'updated_at';

 

  public function generateSalt() {
    $salt = substr(md5(uniqid(rand(), true)), 0, 14);
    return $salt;
  }

  public function encryptPassword($plain_password, $salt) {
    $password = sha1($salt.sha1($plain_password));
    return $password;
  }

  public function ubahPassword($username, $pwd_lama, $pwd_baru) {
    $users = $this->userModel->getUsers(array('users.username'=>$username))->first();
    if($users == false) {
      // username tidak ditemukan
      return false;
    }
    $salt = $users->salt;
    $hash_password = $this->encryptPassword($pwd_lama, $salt);
    $users = $this->userModel->getUsers(array('users.username'=>$username, 'password'=>$hash_password))->first();

    if(!empty($users)) {
      $salt = $this->generateSalt();
      $hash_password = $this->encryptPassword($pwd_baru, $salt);
      $arr_data = array('salt'=>$salt, 'password'=>$hash_password);
      $this->userModel->update($username, $arr_data);
      return true;
    } else {
      return false;
    }
  }

  public function doLogin($username, $password) {
    $users = $this->userModel->getUsers(array('users.username'=>$username))->first();
    if($users == false) {
      // username tidak ditemukan
      return "error";
    }
    $salt = $users->salt;
    $hash_password = $this->encryptPassword($password, $salt);
    $users = $this->userModel->getUsers(array('users.username'=>$username, 'password'=>$hash_password))->first();

    if($users == false) {
      // history login
      $arr_data = array('username'=>$username, 'status'=>'GAGAL');
      //$this->addHistoryLogin($arr_data);
      return "error";
    }
        
    $sess_data = array('username_citra' => $username        
        , 'nama_citra' => $users->nama
        , 'isadmin_citra' => $users->isadmin
        , 'kode_profil' => $users->kode_profil
        , 'id_kebun' => $users->id_kebun
        , 'kode_pt' => $users->kode_pt
        , 'menu_citra' => $this->hakAksesModel->getMenuUntukProfil($users->kode_profil)
      );

    \Config\Services::session()->set($sess_data);
    
    return true;
  }

  public function doLogout() {
    unset($_SESSION['username_citra']);
    unset($_SESSION['nama_citra']);
    unset($_SESSION['menu_citra']);
    unset($_SESSION['isadmin_citra']);
    unset($_SESSION['kode_profil']);
    unset($_SESSION['id_kebun']); 
    unset($_SESSION['kode_pt']); 
  }

  public function UpdateSessionHakAkses() {
    $_SESSION['menu_citra'] = $this->hakAksesModel->getMenuUntukProfil($_SESSION['kode_profil']);
  }

}
