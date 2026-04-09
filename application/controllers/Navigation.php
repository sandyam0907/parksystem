<?php 
class Navigation extends CI_Controller {

public function __construct()
    {
        parent::__construct();
        $this->load->model('NavigationModel','navigation_model');
	}
     public function get_live_navigation($booking_id = null)
    {
        $data['booking'] = null;

        if ($booking_id) {
            $data['booking'] = $this->navigation_model->getBooking($booking_id);
        }

        $this->load->view('live_navigation', $data);
    }

}
?>
