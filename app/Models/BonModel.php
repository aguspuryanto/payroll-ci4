<?php

namespace App\Models;

use CodeIgniter\Model;

class BonModel extends Model {
  protected $table            = 'bon';
  protected $primaryKey       = 'kode_bon';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kode_bon_status', 'kode_pt_karyawan', 'kode_kas', 'kode_kas_tujuan', 'kode_kas_mutasi', 'jenis', 'tanggal_pengajuan', 'tanggal_persetujuan', 'tanggal_pencatatan', 'tanggal_realisasi', 'tanggal_konfirmasi', 'nominal', 'keterangan', 'bukti_persetujuan', 'bukti_realisasi', 'cicilan', 'ispersetujuan'];

  // Dates
  // protected $useTimestamps = true;
  // protected $createdField  = 'created_at';
  // protected $updatedField  = 'updated_at';

  public function getBon($where=null, $orderby="bon.tanggal_pengajuan") {
    $this->builder()->select("bon.*, pt_karyawan.nama_lengkap, pt.nama as nama_pt, bon_status.nama as nama_bon_status, kas.nama as nama_kas, kas_tujuan.nama as nama_kas_tujuan")
      ->join("pt_karyawan", "pt_karyawan.kode_pt_karyawan=bon.kode_pt_karyawan")
      ->join("departmen", "pt_karyawan.kode_departmen=departmen.kode_departmen")
      ->join("pt", "departmen.kode_pt=pt.kode_pt")
      ->join("bon_status", "bon_status.kode_bon_status=bon.kode_bon_status")
      ->join("kas", "bon.kode_kas=kas.kode_kas")
      ->join("departmen departmen_kas", "kas.kode_departmen=departmen_kas.kode_departmen")
      ->join("kas kas_tujuan", "bon.kode_kas_tujuan=kas_tujuan.kode_kas", 'LEFT')
      ->join("kas_mutasi", "bon.kode_kas_mutasi=kas_mutasi.kode_kas_mutasi", "LEFT")
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }
}
