<?php

namespace App\Models;

use CodeIgniter\Model;

class PTKaryawanModel extends Model {
  protected $table            = 'pt_karyawan';
  protected $primaryKey       = 'kode_pt_karyawan';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['kode_departmen', 'id_afdeling', 'no_urut', 'nama_lengkap', 'nama_alias', 'nik', 'nip', 'npwp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'kode_agama', 'alamat', 'telepon', 'pendidikan', 'status_perkawinan', 'rekening_no', 'rekening_bank', 'tanggal_bekerja', 'kode_karyawan_jenis', 'jabatan', 'keterangan', 'bpjs_tk', 'bpjs_kes', 'gaji_pokok', 'gaji_jabatan_tetap', 'biaya_bpjs_tk', 'biaya_bpjs_kes', 'ptkp', 'isaktif'];

  // Dates
  // protected $useTimestamps = true;
  // protected $createdField  = 'created_at';
  // protected $updatedField  = 'updated_at';

  public function getPTKaryawan($where=null, $orderby="pt_karyawan.nama_lengkap") {
    $this->builder()->select("pt_karyawan.*, pt.kode_pt, pt.nama as nama_pt, departmen.nama as nama_departmen, afdeling.nama as nama_afdeling, afdeling.kode as kode_afdeling")
      ->join("departmen", "pt_karyawan.kode_departmen=departmen.kode_departmen")
      ->join("pt", "departmen.kode_pt=pt.kode_pt")
      ->join("karyawan_jenis", "karyawan_jenis.kode_karyawan_jenis=pt_karyawan.kode_karyawan_jenis")
      ->join("afdeling", "afdeling.id=pt_karyawan.id_afdeling", "LEFT")
      ;
    if($where) $this->builder()->where($where);
    if($orderby) $this->builder()->orderby($orderby);
    return $this;
  }

  public function geserSeq($kode, $newSeq) {
      /*$pis = $this->builder()->where("kode_pt_karyawan", $kode)->get()->getResult();
      if(!empty($pis)) {
        $currentSeq = $pis[0]->no_urut;

        $sign = ($newSeq < $currentSeq) ? "+" : "-";
        $this->builder()->set("no_urut", "no_urut".$sign."1", false);
        if($newSeq < $currentSeq) {
          $this->builder()->where(array("no_urut >= "=>$newSeq, "no_urut < "=>$currentSeq, "kode_pt_karyawan <>"=>$kode));
        } else {
          $this->builder()->where(array("no_urut <= "=>$newSeq, "no_urut > "=>$currentSeq, "kode_pt_karyawan <>"=>$kode));
        }
        $this->builder()->update();

        $this->builder()->set("no_urut", $newSeq)->where("kode_pt_karyawan", $kode)->update();
      }*/
      $this->builder()->set("no_urut", "no_urut+1", false);
      $this->builder()->where(array("no_urut >= "=>$newSeq, "kode_pt_karyawan <>"=>$kode));
      $this->builder()->update();
    }
}
