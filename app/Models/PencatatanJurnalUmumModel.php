<?php

namespace App\Models;

use CodeIgniter\Model;

class PencatatanJurnalUmumModel extends Model {
  protected $table            = 'pencatatan_jurnal_umum';
  protected $primaryKey       = 'kode_pencatatan_jurnal_umum';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kode_bon', 'kode_coa', 'tanggal_pencatatan', 'tanggal_transaksi', 'nominal', 'posisi', 'status'];

  // Dates
  //protected $useTimestamps = true;
  //protected $createdField  = 'created_at';
  //protected $updatedField  = 'updated_at';

  public function getPencatatanJurnalUmum($where=null, $orderby = "tanggal_pencatatan, bon.kode_bon, kode_pencatatan_jurnal_umum") {
    $this->builder()->select("pencatatan_jurnal_umum.*, coa.nama_transaksi, pt.nama as nama_pt")
      ->join("coa", "coa.kode_coa=pencatatan_jurnal_umum.kode_coa")
      ->join("bon", "bon.kode_bon=pencatatan_jurnal_umum.kode_bon")
      ->join("kas", "bon.kode_kas=kas.kode_kas")
      ->join("departmen", "departmen.kode_departmen=kas.kode_departmen")
      ->join("pt", "pt.kode_pt = departmen.kode_pt")
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }
    
}
