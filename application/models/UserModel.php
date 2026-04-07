<?php
class UserModel extends CI_Model {

    public function insertUser($data){
        return $this->db->insert('users',$data);
    }

    public function emailExists($email){
        return $this->db->where('email',$email)->get('users')->row();
    }

public function login($email){
    return $this->db->select('u.*, r.role_name')
                    ->from('users u')
                    ->join('roles r','r.id = u.role_id')
                    ->where('u.email',$email)
                    ->get()
                    ->row();
}



public function getAllUsers(){
    return $this->db->select('u.*, r.role_name')
                    ->from('users u')
                    ->join('roles r','r.id = u.role_id')
                    ->where('u.status',1)
                    ->get()
                    ->result();
}


public function getUserById($id){
    return $this->db->select('u.*, r.role_name')
                    ->from('users u')
                    ->join('roles r','r.id = u.role_id')
                    ->where('u.id',$id)
                    ->where('u.status',1)
                    ->get()
                    ->row();
}
public function getById($id){
   return $this->db->where('id',$id)->get('users')->row();
}

public function getRoles(){
   return $this->db->where('status',1)->get('roles')->result();
}


    public function updateUser($id,$data){
    return $this->db
                ->where('id',$id)
                ->where('status',1)     // update only active users
                ->update('users',$data);
    }
    
    public function softDelete($id){
    return $this->db->where('id',$id)->update('users',['status'=>0]);
    }

// public function deleteUser($id){
//     return $this->db->where('id',$id)->delete('users');
// }

//     public function getAllUsers(){
//     return $this->db->get('users')->result();
// }

public function getUserByEmail($email){
        if(empty($email)) return null;
        return $this->db->where('email',$email)->get('users')->row();
    }

    public function saveOTP($email,$otp){
        if(empty($email)) return false;
        return $this->db->where('email',$email)->update('users',[
            'reset_otp'=>$otp,
            'otp_expire'=>date('Y-m-d H:i:s',strtotime('+1 minute'))
        ]);
    }

    public function verifyOTP($email,$otp){
        if(empty($email) || empty($otp)) return null;
        return $this->db->where('email',$email)
                        ->where('reset_otp',$otp)
                        ->where('otp_expire >=',date('Y-m-d H:i:s'))
                        ->get('users')->row();
    }

    public function clearOTP($email){
        if(empty($email)) return false;
        return $this->db->where('email',$email)->update('users',[
            'reset_otp'=>NULL,
            'otp_expire'=>NULL
        ]);
    }

    public function updatePassword($email,$pass){
        if(empty($email)) return false;
        return $this->db->where('email',$email)->update('users',[
            'password'=>$pass,
            'reset_otp'=>NULL,
            'otp_expire'=>NULL
        ]);
    }
	 

    public function updateById($id, $data)
    {
        return $this->db->where('id', $id)->update('users', $data);
    }

}






?>