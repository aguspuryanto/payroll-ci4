<?php

namespace App\Models;

use CodeIgniter\Model;

class BonStatusModel extends Model {
    protected $table            = 'bon_status';
    protected $primaryKey       = 'kode_bon_status';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['nama'];

    // Dates
    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';

        
}
