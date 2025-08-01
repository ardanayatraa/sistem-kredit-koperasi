<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'tbl_users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'level',
        'no_hp',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash the password before saving to DB
     */
    protected function hashPassword(array $data)
    {
        if (!empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            // If password is empty, remove it from update data
            unset($data['data']['password']);
        }

        return $data;
    }

      public function getByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
