<?php

namespace App\Models;

use CodeIgniter\Model;

class Foto_model extends Model
{
    protected $table = 'foto';

    public function uplooad_foto($data)
    {
        $upl = $this->db->table($this->table)->insert($data);
        return $upl;
    }
}
