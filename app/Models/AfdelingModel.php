<?php

namespace App\Models;

use CodeIgniter\Model;

class AfdelingModel extends Model {
    protected $table            = 'afdeling';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode', 'nama', 'kode_kebun'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAfdeling($where=null, $orderby="afdeling.nama") {
    $this->builder()->select("afdeling.*, kebun.nama as nama_kebun")
      ->join("kebun", "kebun.kode=afdeling.kode_kebun")      
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }  
}
