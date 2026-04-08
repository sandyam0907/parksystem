<?php
class ParkingModel extends CI_Model{
    public function getAll($sort='',$keyword=''){
		if($sort=='price')
		{
			
			if (!empty($keyword)) {
				$this->db->like('parking_name', $keyword);
                }
			return $this->db
					->where('parking_status', 'open')
					->order_by('parking_price', 'ASC')
					->order_by('park_id', 'ASC')
					->get('parking')
					->result();
					echo $this->db->last_query();
		}else if($sort=='availability'){
				if (!empty($keyword)) {
				$this->db->like('parking_name', $keyword);
                }
			return $this->db
					->where('parking_slots >', 0)
					->where('parking_status', 'open')
					->order_by('parking_price', 'ASC')
					->order_by('park_id', 'ASC')
					->get('parking')
					->result();
		}else if($sort=='parking'){
				if (!empty($keyword)) {
				$this->db->like('parking_name', $keyword);
                }
			return $this->db					
					->where('parking_status', 'open')
					->order_by('parking_price', 'ASC')
					->order_by('park_id', 'ASC')
					->get('parking')
					->result();
		}else{
			return $this->db
					->where('parking_status', 'open')
					->order_by('park_id', 'ASC')
					->get('parking')
					->result();
		}
    }

     public function getByparkingId($id){
        return $this->db->where('park_id',$id)->get('parking')->row();
    }
	
	

    public function parkinsert($data){
        return $this->db->insert('parking',$data);
    }

    public function parkupdate($id,$data){
        return $this->db->where('park_id',$id)->update('parking',$data);
    }

    public function parkdelete($id){
        return $this->db->where('park_id',$id)->delete('parking');
    }
	public function getallbooking($id){
        return $this->db
        ->where('user_id', $id)
        ->order_by('booking_id', 'DESC') 
        ->get('park_booking')
        ->result();
    }
	public function getWatchmanList($parkid){
        return $this->db->where('parking_id',$parkid)->get('watchman')->result();
    }
	
	

}

?>