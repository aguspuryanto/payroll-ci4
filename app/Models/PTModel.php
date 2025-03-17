<?php

namespace App\Models;

use CodeIgniter\Model;

class PTModel extends Model {
    protected $table            = 'pt';
    protected $primaryKey       = 'kode_pt';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_kebun', 'nama', 'bunga_pinjaman'];

    // Dates
    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';

  public function getPT($where=null, $orderby="pt.nama") {
    $this->builder()->select("pt.*, kebun.nama as nama_kebun, kebun.kode as kode_kebun")
      ->join("kebun", "pt.id_kebun=kebun.id", "LEFT")
     
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }
}
