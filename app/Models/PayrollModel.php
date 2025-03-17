<?php

namespace App\Models;

use CodeIgniter\Model;

class PayrollModel extends Model {
    protected $table            = 'payroll';
    protected $primaryKey       = 'kode_payroll';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_pt', 'kode_bon', 'tanggal_pengajuan', 'tanggal_pengajuan_bon', 'tanggal_lunas', 'tanggal_pph21', 'total_gaji_pokok', 'total_gaji_jabatan', 'total_tunjangan', 'total_lembur', 'total_lain_lain', 'total_thr', 'total_gratifikasi', 'total_rapel', 'total_bpjs_kes', 'total_bpjs_tk', 'total_pinjaman', 'total_gaji_net', 'total_bpjs_kes_pt', 'total_bpjs_tk_jkm_pt', 'total_bpjs_tk_jkk_pt', 'total_bpjs_tk_jp_pt', 'total_bpjs_tk_jht_pt', 'total_pph21', 'status'];

    // Dates
    //protected $useTimestamps = true;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';

    public function getPayroll($where=null, $orderby = "") {
      $this->builder()->select("payroll.*, pt.nama as nama_pt")
        ->join("pt", "pt.kode_pt=payroll.kode_pt")
        ->join("bon", "payroll.kode_bon=bon.kode_bon", "LEFT")
        ;
      if($where) $this->builder()->where($where);
      if($orderby) $this->builder()->orderby($orderby);
      return $this;
    }
    
}
