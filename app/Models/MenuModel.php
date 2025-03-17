<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model {
    protected $table            = 'menus_keuangan';
    protected $primaryKey       = 'idmenus';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['idmenus', 'idparent', 'nama', 'link', 'folder', 'icon', 'jenis', 'seq', 'isaktif'];

    // Dates
    //protected $useTimestamps = true;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';

    public function getMenu($where=null, $orderby = "menus_keuangan.jenis, menus_keuangan.seq") {
      $this->builder()->select("menus_keuangan.*, p.nama as nama_parent")
        ->join("menus_keuangan p", "menus_keuangan.idparent=p.idmenus", "LEFT");
      if($where) $this->builder()->where($where);
      if($orderby) $this->builder()->orderby($orderby);
      return $this;
    }

    
    
}
