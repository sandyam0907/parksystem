<?php 
class Navigation extends CI_Controller {

    public function index()
    {
        $this->load->view('live_navigation');
    }

    public function route()
    {
        $this->load->view('route_navigation');
    }

}
?>
