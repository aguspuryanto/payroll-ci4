<?php

namespace App\Models;

use CodeIgniter\Model;

class HakAksesModel extends Model {
  protected $table            = 'hak_akses_keuangan';
  protected $primaryKey       = 'kode_profil';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kode_profil', 'idmenus'];

  // Dates
  //protected $useTimestamps = true;
  //protected $createdField  = 'created_at';
  //protected $updatedField  = 'updated_at';    
  
  public function getHakAkses($where=null, $orderby = "menus_keuangan.jenis, menus_keuangan.seq") {
    $this->builder()->select("menus_keuangan.*, menus_parent.nama as nama_parent, jiu.*")
      ->join("menus_keuangan", "hak_akses_keuangan.idmenus=menus_keuangan.idmenus")
      ->join("profil jiu", "hak_akses_keuangan.kode_profil=jiu.kode_profil")
      ->join("menus_keuangan as menus_parent", "menus_keuangan.idparent=menus_parent.idmenus", "LEFT");
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }

  public function addHakAkses($arr_data, $jika_ada_update = true) {
    $arr_where = array('hak_akses_keuangan.kode_profil'=>$arr_data['kode_profil'], 'hak_akses_keuangan.idmenus'=>$arr_data['idmenus']);
    $has = $this->getHakAkses($arr_where)->find();
    if(!empty($has)) {
      if($jika_ada_update) {
        $builder = $this->db->table('hak_akses_keuangan');
        $arr_where = array('kode_profil'=>$arr_data['kode_profil'], 'idmenus'=>$arr_data['idmenus']);
        $builder->where($arr_where)->update($arr_data);
        return true;
      } else {
        return 'ada';
      }
    }
    $this->builder()->insert($arr_data);
    return true;
  }

  public function getMenuUntukProfil($kode_profil) {
    $menus = $this->getHakAkses(array('hak_akses_keuangan.kode_profil'=>$kode_profil, 'menus_keuangan.isaktif'=>1))->find();
    $hasil = array();
    if($menus!=false) {
      foreach($menus as $menu) {
        $hasil[] = $menu->idmenus;
      }
    }
    return $hasil;
  }
}
