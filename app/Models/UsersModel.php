<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model {
  protected $table            = 'users';
  protected $primaryKey       = 'username';
  protected $useAutoIncrement = false;
  protected $returnType       = 'object';
  protected $allowedFields    = ['username', 'kode_profil', 'id_kebun', 'kode_pt', 'nama', 'email', 'password', 'salt', 'reser_code', 'reset_expired', 'isadmin', 'isaktif'];

  // Dates
  //protected $useTimestamps = true;
  //protected $createdField  = 'created_at';
  //protected $updatedField  = 'updated_at';

  public function getUsers($where=null, $orderby = null) {
    $this->builder()->select("users.*, kb.nama as nama_kebun, profil.nama as nama_profil, pt.nama as nama_pt")
      ->join('profil', 'profil.kode_profil=users.kode_profil')
      ->join('pt', 'pt.kode_pt=users.kode_pt', "LEFT")
      ->join('kebun kb', 'kb.id=users.id_kebun', "LEFT");
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }

}
