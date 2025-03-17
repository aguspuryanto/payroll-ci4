<?php

namespace App\Models;

use CodeIgniter\Model;

class BonStatusHistoriModel extends Model {
  protected $table            = 'bon_status_histori';
  protected $primaryKey       = 'kode_bon';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kode_bon', 'kode_bon_status'];

  // Dates
  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  public function getBonStatusHistori($where=null, $orderby="bon_status_histori.created_at") {
    $this->builder()->select("bon_status_histori.*, bon_status.nama as nama_bon_status")
      ->join("bon_status", "bon_status.kode_bon_status=bon_status_histori.kode_bon_status")      
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }   
}
