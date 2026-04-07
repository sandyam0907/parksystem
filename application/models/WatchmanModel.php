<?php

class WatchmanModel extends CI_Model
{

    protected $table = 'watchman';
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function getAllWithParking()
    {
        return $this->db
            ->select('w.*, p.parking_name')
            ->from('watchman w')
            ->join('parking p', 'p.park_id = w.parking_id', 'left')
            ->order_by('w.watchman_id', 'DESC')
            ->get()
            ->result();
    }

    public function getById($watchman_id)
    {
        return $this->db
            ->where('watchman_id', $watchman_id)
            ->get('watchman')
            ->row();
    }

    public function update($watchman_id, $data)
    {
        return $this->db
            ->where('watchman_id', $watchman_id)
            ->update('watchman', $data);
    }

    public function delete($watchman_id)
    {
        return $this->db
            ->where('watchman_id', $watchman_id)
            ->delete('watchman');
    }
    public function updateStatus($watchman_id, $status)
    {
        return $this->db
            ->where('watchman_id', $watchman_id)
            ->update('watchman', [
                'status' => $status
            ]);
    }

    public function getByEmail($email)
{
    return $this->db
        ->where('email', $email)
        ->where('status', 'active')
        ->get('watchman')
        ->row();
}


}


?>