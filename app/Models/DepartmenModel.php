<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmenModel extends Model {
    protected $table            = 'departmen';
    protected $primaryKey       = 'kode_departmen';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_pt', 'nama'];

    // Dates
    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';

  public function getDepartmen($where=null, $orderby="departmen.nama") {
    $this->builder()->select("departmen.*, pt.nama as nama_pt, kebun.nama as nama_kebun, kebun.kode as kode_kebun, pt.id_kebun")
      ->join("pt", "pt.kode_pt=departmen.kode_pt")
      ->join("kebun", "pt.id_kebun=kebun.id", "LEFT")
     
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }
}
