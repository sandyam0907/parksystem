<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles extends CI_Controller{


public function __construct() {
    parent::__construct();
    $this->load->model('VehicleModel');
    
}

 public function create_vehicle()
    {
        $vehicle_brand  = $this->input->post('vehicle_brand');
        $vehicle_model  = $this->input->post('vehicle_model');
        $vehicle_number = $this->input->post('vehicle_number');
        $user_id        = $this->input->post('user_id');

        // Validation
        if (!$vehicle_brand || !$vehicle_model || !$vehicle_number || !$user_id) {
            echo json_encode([
                'status'  => false,
                'message' => 'All fields are required'
            ]);
            return;
        }

        $data = [
            'user_id'        => $user_id,
            'vehicle_brand'  => $vehicle_brand,
            'vehicle_model'  => $vehicle_model,
            'vehicle_number' => $vehicle_number,
            'created_at'     => date('Y-m-d H:i:s')
        ];

        if ($this->VehicleModel->insert($data)) {
            echo json_encode([
                'status'  => true,
                'message' => 'Vehicle created successfully'
            ]);
        } else {
            echo json_encode([
                'status'  => false,
                'message' => 'Failed to create vehicle'
            ]);
        }
    }


      public function get_all_vehicle_list($userId)
    {
        if (!$userId) {
            echo json_encode([
                'status'  => false,
                'message' => 'User ID is required'
            ]);
            return;
        }

        $vehicles = $this->VehicleModel->getByUser($userId);

        echo json_encode([
            'status' => true,
            'count'  => count($vehicles),
            'data'   => $vehicles
        ]);
    }
}
?>