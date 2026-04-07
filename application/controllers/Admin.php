<?php
class Admin extends CI_Controller {

 
    public function index(){
        if($this->session->userdata('role_id')!=1) redirect('login');
        $this->load->view('admin/dashboard');
    }
}

   
