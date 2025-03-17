<?php

namespace App\Models;

use CodeIgniter\Model;

class KasMutasiModel extends Model {
  protected $table            = 'kas_mutasi';
  protected $primaryKey       = 'kode_kas_mutasi';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kode_kas', 'tanggal_input', 'tanggal_transaksi', 'nominal_perubahan', 'nominal_awal', 'nominal_akhir', 'keterangan', 'transaksi', 'jenis'];

  // Dates
  //protected $useTimestamps = true;
  //protected $createdField  = 'created_at';
  //protected $updatedField  = 'updated_at';

  public function getKasMutasi($where=null, $orderby = "kas_mutasi.kode_kas, kas_mutasi.kode_kas_mutasi") {
    $this->builder()->select("kas_mutasi.*, kas.nama as nama_kas")
      ->join("kas", "kas_mutasi.kode_kas=kas.kode_kas")
      ->join("departmen", "kas.kode_departmen=departmen.kode_departmen")
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }
  
}
