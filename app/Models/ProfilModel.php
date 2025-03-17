<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model {
    protected $table            = 'profil';
    protected $primaryKey       = 'kode';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_profil', 'nama', 'aplikasi'];

    // Dates
    //protected $useTimestamps = true;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';

    
}
