<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanJenisModel extends Model {
    protected $table            = 'karyawan_jenis';
    protected $primaryKey       = 'kode_karyawan_jenis';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['nama'];

    // Dates
    //protected $useTimestamps = true;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    
}
