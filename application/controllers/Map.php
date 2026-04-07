<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {

    public function index()
    {
        // You can load these from DB also
        $data['from_lat'] = 12.9352; 
        $data['from_lng'] = 77.6973;

        $data['to_lat'] = 12.9698;
        $data['to_lng'] = 77.7500;

        $this->load->view('osm_map_view', $data);
    }
	public function locations()
    {
        // You can load this from database later
        $data['locations'] = [
            1 => ['name' => 'Panathur',   'lat' => 12.9352, 'lng' => 77.6973],
            2 => ['name' => 'Whitefield', 'lat' => 12.9698, 'lng' => 77.7500],
            3 => ['name' => 'Marathahalli','lat' => 12.9591, 'lng' => 77.6974],
            4 => ['name' => 'Bellandur',  'lat' => 12.9304, 'lng' => 77.6784],
        ];

        $this->load->view('osm_dropdown_map', $data);
    }
	
	    public function auto_locations()
    {
        $this->load->view('mapview');
    }

}
