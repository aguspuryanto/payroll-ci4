<?php

namespace App\Models;

use CodeIgniter\Model;

class KebunModel extends Model {
    protected $table            = 'kebun';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode', 'nama', 'telp', 'fax', 'email', 'alamat', 'logo', 'nama_rekening', 'nama_bank', 'cabang_bank', 'no_rekening', 'adm', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
}
