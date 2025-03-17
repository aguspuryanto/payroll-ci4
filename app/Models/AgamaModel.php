<?php

namespace App\Models;

use CodeIgniter\Model;

class AgamaModel extends Model {
    protected $table            = 'agama';
    protected $primaryKey       = 'kode_agama';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['nama'];

    // Dates
    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
}
