<?php

namespace App\Models;

use CodeIgniter\Model;

class PayrollDetilModel extends Model {
    protected $table            = 'payroll_detil';
    protected $primaryKey       = 'kode_payroll_detil';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_payroll', 'kode_pt_karyawan', 'gaji_pokok', 'gaji_jabatan_tetap', 'tunjangan', 'lembur', 'lain_lain', 'thr', 'gratifikasi', 'rapel', 'bpjs_kes', 'bpjs_tk', 'pinjaman', 'gaji_nett', 'bpjs_kes_pt', 'bpjs_tk_jkm_pt', 'bpjs_tk_jkk_pt', 'bpjs_tk_jp_pt', 'bpjs_tk_jht_pt', 'kode_pinjaman_tagihan', 'pph21'];

    // Dates
    //protected $useTimestamps = true;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';

    public function getPayrollDetil($where=null, $orderby = "payroll_detil.kode_payroll, pt_karyawan.no_urut") {
      $this->builder()->select("payroll_detil.*, pt_karyawan.nama_lengkap as nama_lengkap_pt_karyawan, pt_karyawan.nik  as nik_pt_karyawan, pt_karyawan.jabatan as jabatan_pt_karyawan, pt_karyawan.no_urut as no_urut_pt_karyawan")
        ->join("pt_karyawan", "pt_karyawan.kode_pt_karyawan=payroll_detil.kode_pt_karyawan")
        ->join("payroll", "payroll.kode_payroll=payroll_detil.kode_payroll")
        ;
      if($where) $this->builder()->where($where);
      if($orderby) $this->builder()->orderby($orderby);
      return $this;
    }
    
}
