<?php

namespace App\Models;

use CodeIgniter\Model;

class KasModel extends Model {
    protected $table            = 'kas';
    protected $primaryKey       = 'kode_kas';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_departmen', 'nama', 'nominal', 'ispersetujuan'];

    // Dates
    //protected $useTimestamps = true;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';

  public function getKas($where=null, $orderby="kas.nama") {
    $this->builder()->select("kas.*, departmen.nama as nama_departmen")
      ->join("departmen", "kas.kode_departmen=departmen.kode_departmen")
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }
    
}
