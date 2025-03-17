<?php

namespace App\Models;

use CodeIgniter\Model;

class CoaModel extends Model {
  protected $table            = 'coa';
  protected $primaryKey       = 'kode_coa';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kdoe_coa_jenis_biaya', 'kode_coa_kkb', 'nama_transaksi'];

  // Dates
  //protected $useTimestamps = true;
  //protected $createdField  = 'created_at';
  //protected $updatedField  = 'updated_at';

  public function getCoa($where=null, $orderby = "coa_jenis_biaya.nama, coa.kode_coa") {
    $this->builder()->select("coa.*, coa_jenis_biaya.nama as nama_coa_jenis_biaya")
      ->join("coa_jenis_biaya", "coa_jenis_biaya.kode_coa_jenis_biaya=coa.kode_coa_jenis_biaya")
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }
  
}
