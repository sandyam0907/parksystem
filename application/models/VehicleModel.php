<?php

class VehicleModel extends CI_Model
{

    protected $table = 'vehicles';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function getByUser($userId)
    {
        return $this->db
            ->where('user_id', $userId)
            ->order_by('vehicle_id', 'DESC')
            ->get($this->table)
            ->result();
    }


}
?>