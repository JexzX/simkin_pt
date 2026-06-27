<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterUnitModel extends Model
{
    protected $table = 'master_unit';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_unit'];
    protected $useTimestamps = false;
}
