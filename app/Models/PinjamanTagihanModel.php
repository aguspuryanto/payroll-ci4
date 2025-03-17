<?php

namespace App\Models;

use CodeIgniter\Model;

class PinjamanTagihanModel extends Model {
  protected $table            = 'pinjaman_tagihan';
  protected $primaryKey       = 'kode_pinjaman_tagihan';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kode_bon', 'kode_bon_payroll', 'nominal', 'tanggal_tagihan', 'status'];

  // Dates
  // protected $useTimestamps = true;
  // protected $createdField  = 'created_at';
  // protected $updatedField  = 'updated_at';
  
  public function getPinjamanTagihan($where=null, $orderby = "pinjaman_tagihan.status desc, pinjaman_tagihan.tanggal_tagihan") {
    $this->builder()->select("pinjaman_tagihan.*, pt_karyawan.nama_lengkap, pt_karyawan.nip, pt.nama as nama_pt")
      ->join("bon", "pinjaman_tagihan.kode_bon=bon.kode_bon")
      ->join("pt_karyawan", "pt_karyawan.kode_pt_karyawan=bon.kode_pt_karyawan")
      ->join("departmen", "pt_karyawan.kode_departmen=departmen.kode_departmen")
      ->join("pt", "pt.kode_pt=departmen.kode_pt")
      ->join("bon bon_payroll", "pinjaman_tagihan.kode_bon_payroll=bon_payroll.kode_bon", "Left")
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }

  public function deletePinjamanTagihan($where) {
    $this->builder()->where($where)->delete();
  }
  
}
