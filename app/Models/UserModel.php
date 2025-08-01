<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tbl_users';
    protected $primaryKey = 'id_user';
    protected $allowedFields = [
        'nama_lengkap', 
        'username', 
        'email', 
        'password', 
        'level', 
        'no_hp', 
        'id_anggota_ref'
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function getByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}