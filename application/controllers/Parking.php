<?php

class Parking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['UserModel', 'ParkingModel']);
        $this->load->library(['form_validation', 'session']);

    }

    public function parking_login()
    {
        $email = $this->input->post('email');
        $pass = $this->input->post('password');

        if (!$email) {
            echo json_encode(['status' => false, 'message' => 'Email is  required']);
            return;
        }
        if (!$pass) {
            echo json_encode(['status' => false, 'message' => ' password is  required']);
            return;
        }
        $user = $this->UserModel->login($email);
        if (!$user) {
            echo json_encode(['status' => false, 'message' => 'User not found']);
            return;
        }

        if (!password_verify($pass, $user->password)) {
            echo json_encode(['status' => false, 'message' => 'Wrong password']);
            return;
        }

        // session is set but NO redirect
        $this->session->set_userdata([
            'uid' => $user->id,
            'role_id' => $user->role_id,
            'logged_in' => true
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'Login success',
            'user' => $user
        ]);
    }


    public function index()
    {
        $data['parkings'] = $this->ParkingModel->getAll();
        $this->load->view('admin/parking_list', $data);
    }

    public function add()
    {
        $this->load->view('admin/add_parking');
    }

public function store()
{
    $this->form_validation->set_rules('parking_name', 'Parking Name', 'required');
    $this->form_validation->set_rules('parking_price', 'Price', 'required|numeric');
    $this->form_validation->set_rules('parking_slots', 'Slots', 'required|numeric');
    $this->form_validation->set_rules('parking_location', 'Location', 'required');
    $this->form_validation->set_rules('area', 'Area', 'required');
    $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric');
    $this->form_validation->set_rules('latitude', 'Latitude', 'required|numeric');
    $this->form_validation->set_rules('longitude', 'Longitude', 'required|numeric');

    if ($this->form_validation->run() == FALSE) {

        $this->load->view('admin/add_parking');

    } else {

        $imageName = null;

        if (!empty($_FILES['parking_image']['name'])) {

            $config['upload_path']   = './uploads/parking/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = time().'_'.$_FILES['parking_image']['name'];

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('parking_image')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('parking/add');
                return;
            }

            $imageName = $this->upload->data('file_name');
        }

        
        $data = [
            'parking_name'     => $this->input->post('parking_name'),
            'parking_price'    => $this->input->post('parking_price'),
            'parking_slots'    => $this->input->post('parking_slots'),
            'parking_location' => $this->input->post('parking_location'),
            'area'             => $this->input->post('area'),
            'pincode'          => $this->input->post('pincode'),
            'latitude'         => $this->input->post('latitude'),
            'longitude'        => $this->input->post('longitude'),
            'parking_image'    => $imageName,
            'user_id'          => $this->session->userdata('uid'),
            'parking_status'   => 'open',
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $this->ParkingModel->parkinsert($data);
        $this->session->set_flashdata('success', 'Parking added successfully');
        redirect('parking');
    }
}


    // public function save()
    // {
    //     $this->form_validation->set_rules('parking_name', 'Parking Name', 'required|trim');
    //     $this->form_validation->set_rules('parking_price', 'Price', 'required|numeric');
    //     $this->form_validation->set_rules('parking_slots', 'Slots', 'required|numeric');
    //     $this->form_validation->set_rules('parking_location', 'Location', 'required|trim');
    //     $this->form_validation->set_rules('area', 'Area', 'required|trim');
    //     $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric|exact_length[6]');

    //     if ($this->form_validation->run() == FALSE) {

    //         $this->load->view('admin/add_parking');
    //     } else {
    //         $this->ParkingModel->insert($this->input->post());
    //         $this->session->set_flashdata('success', 'Parking added successfully');
    //         redirect('parking');
    //     }
    // }


    public function edit($id)
    {
        $data['parking'] = $this->ParkingModel->getByparkingId($id);
        $this->load->view('admin/edit_parking', $data);
    }

    public function update($id)
    {
        $data = [
            'parking_name' => $this->input->post('parking_name'),
            'parking_price' => $this->input->post('parking_price'),
            'parking_slots' => $this->input->post('parking_slots'),
            'parking_location' => $this->input->post('parking_location'),
            'area' => $this->input->post('area'),
            'pincode' => $this->input->post('pincode'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
        ];

        if (!empty($_FILES['parking_image']['name'])) {

            $config['upload_path'] = './uploads/parking/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . $_FILES['parking_image']['name'];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('parking_image')) {
                $uploadData = $this->upload->data();
                $data['parking_image'] = $uploadData['file_name'];

                $old = $this->ParkingModel->getByparkingId($id);
                if ($old && !empty($old->parking_image)) {
                    $oldPath = './uploads/parking/' . $old->parking_image;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

            } else {
                $this->session->set_flashdata(
                    'error',
                    $this->upload->display_errors()
                );
                redirect('parking/edit/' . $id);
                return;
            }
        }
        $this->ParkingModel->parkupdate($id, $data);
        $this->session->set_flashdata('success', 'Parking updated successfully');
        redirect('parking');
    }

    public function delete($id)
    {
        $this->ParkingModel->parkdelete($id);
        redirect('parking');
    }

    public function test_location()
    {
        $this->load->view('admin/test_location');
    }

    public function get_all_parking($id='',$sort="",$keyword='')
    {
		$result=$this->UserModel->getUserById($id);
		$output = [];
        $parkings= $this->ParkingModel->getAll($sort,$keyword);
	
		if(count($parkings)>0){
			foreach($parkings as $eachPark){
				 $latitude=$eachPark->latitude;
				$longitude=$eachPark->longitude;
				
				$userlat=$result->latitude;
				$userlong=$result->longitude;
				
				$distance=$this->distance( $latitude,  $longitude,  $userlat,  $userlong);
				
							 // Add distance to object OR array
				$eachPark->distance_km = round($distance, 2);  // 2 decimal

				// Push to output
				$output[] = $eachPark;
							
							// Example usage: Distance between London and New York
							//$distance1=$this->distance( $latitude,  $longitude,  $userlat,  $userlong);
							//echo getDistance(51.5074, -0.1278, 40.7128, -74.0060, "K") . " km";
							//echo intval($distance1) . " km";echo "<br>";
							
							
						}
					$data['parkings'] = $output;
					if($sort!='' && $sort=='distance'){
										
						$parkings = $data['parkings']; // or wherever your array is

						usort($parkings, function($a, $b) {
							return $a->distance_km <=> $b->distance_km;
						});

						
						$response['parkings'] = $parkings;
						  echo json_encode([
						'status' => true,
						'parking' => $response
					]);
					
					}else{
					
						echo json_encode([
							'status' => true,
							'parking' => $data
						]);
					}
		}else{
			$response['parkings'] = array();
			 echo json_encode([
            'status' => false,
            'parking' => $response
        ]);
		}

    }
    public function get_parking($id)
    {
        $parking = $this->ParkingModel->getByparkingId($id);
        echo json_encode($parking);
        exit;
    }

    // public function update_ajax($id)
    // {
    //     $this->form_validation->set_rules('parking_name', 'Parking Name', 'required');
    //     $this->form_validation->set_rules('parking_price', 'Price', 'required|numeric');
    //     $this->form_validation->set_rules('parking_slots', 'Slots', 'required|numeric');
    //     $this->form_validation->set_rules('parking_location', 'Location', 'required');
    //     $this->form_validation->set_rules('area', 'Area', 'required');
    //     $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric');
    //         $this->form_validation->set_rules('latitude', 'Latitude', 'required|numeric');
    // $this->form_validation->set_rules('longitude', 'Longitude', 'required|numeric');

    //     if ($this->form_validation->run() == FALSE) {
    //         echo json_encode([
    //             'status' => false,
    //             'errors' => validation_errors()
    //         ]);
    //         exit;
    //     }

    //    $data = [
    //     'parking_name'     => $this->input->post('parking_name'),
    //     'parking_price'    => $this->input->post('parking_price'),
    //     'parking_slots'    => $this->input->post('parking_slots'),
    //     'parking_location' => $this->input->post('parking_location'),
    //     'area'             => $this->input->post('area'),
    //     'pincode'          => $this->input->post('pincode'),
    //     'latitude'         => $this->input->post('latitude'),
    //     'longitude'        => $this->input->post('longitude'),
    // ];
    //     if (!empty($_FILES['parking_image']['name'])) {

    //     $config['upload_path']   = './uploads/parking/';
    //     $config['allowed_types'] = 'jpg|jpeg|png';
    //     $config['max_size']      = 2048;
    //     $config['file_name']     = time().'_'.$_FILES['parking_image']['name'];

    //     $this->load->library('upload', $config);

    //     if (!$this->upload->do_upload('parking_image')) {
    //         echo json_encode([
    //             'status' => false,
    //             'errors' => $this->upload->display_errors()
    //         ]);
    //         exit;
    //     }

    //     $data['parking_image'] = $this->upload->data('file_name');
    // }

    // $this->ParkingModel->update($id, $data);
    //     echo json_encode(['status' => true]);
    //     exit;
    // }

public function update_status()
{
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->ParkingModel->update($id, [
        'parking_status' => $status
    ]);

    echo json_encode(['status' => true]);
}


    public function search_location()
    {
        $query = trim($this->input->get('q'));

        if (!$query) {
            echo json_encode([]);
            return;
        }

        $isGeneric = (str_word_count($query) <= 2);

        $url = "https://nominatim.openstreetmap.org/search?"
            . "format=json"
            . "&q=" . urlencode($query)
            . "&addressdetails=1"
            . "&limit=15";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: ParkingApp/1.0 (contact@yourdomain.com)"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        header('Content-Type: application/json');
        echo $response;
    }


    public function reverse_location()
    {
        $lat = $this->input->get('lat');
        $lon = $this->input->get('lon');

        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: ParkingApp/1.0 (contact@yourdomain.com)"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        header('Content-Type: application/json');
        echo $response;
    }
	public function update_location()
	{
		$id=$this->input->post('userid');
		$lat   = $this->input->post('latitude');
		$lng   = $this->input->post('longitude');
		 if ($lat && $lng) {
			$update=$this->UserModel->updateUser($id, [
				'latitude'  => $lat,
				'longitude' => $lng
			]);
		}
		  echo json_encode(['status' => true]);

	}
	public function distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // KM

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    return $earth_radius * $c; // KM
}

function getDistance($lat1, $lon1, $lat2, $lon2, $unit = 'K') {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    }
    
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
        return ($miles * 1.609344); // Kilometers
    } else if ($unit == "N") {
        return ($miles * 0.8684);   // Nautical Miles
    } else {
        return $miles;              // Statute Miles
    }
}

public function get_all_bookings($id)
{
$parking['bookings'] = $this->ParkingModel->getallbooking($id);
        echo json_encode($parking);
        exit;	
}

public function update_profile($user_id)
{
    if (!$user_id) {
        echo json_encode([
            'status' => false,
            'message' => 'User ID required'
        ]);
        return;
    }

    $user = $this->UserModel->getById($user_id);
    if (!$user) {
        echo json_encode([
            'status' => false,
            'message' => 'User not found'
        ]);
        return;
    }

    $data = [
        'name'     => $this->input->post('name'),
        'email'    => $this->input->post('email'),
        'phone'    => $this->input->post('phone'),
        'username' => $this->input->post('username'),
    ];

    $data = array_filter($data, function ($value) {
        return $value !== null && $value !== '';
    });

    if (!empty($_FILES['photo']['name'])) {

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = time().'_'.$_FILES['photo']['name'];

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('photo')) {
            $uploadData = $this->upload->data();
            $data['photo'] = $uploadData['file_name'];
        } else {
            echo json_encode([
                'status' => false,
                'message' => $this->upload->display_errors()
            ]);
            return;
        }
    }

    if (empty($data)) {
        echo json_encode([
            'status' => false,
            'message' => 'No data to update'
        ]);
        return;
    }

    $updated = $this->UserModel->updateById($user_id, $data);

    echo json_encode([
        'status' => $updated ? true : false,
        'message' => $updated ? 'Profile updated successfully' : 'Update failed'
    ]);
}


    public function change_password($user_id)
{
    if (!$user_id) {
        echo json_encode([
            'status' => false,
            'message' => 'User ID required'
        ]);
        return;
    }

    $old_password     = $this->input->post('old_password');
    $new_password     = $this->input->post('new_password');
    $confirm_password = $this->input->post('new_password');

    if (!$old_password || !$new_password || !$confirm_password) {
        echo json_encode([
            'status' => false,
            'message' => 'All fields are required'
        ]);
        return;
    }

    if ($new_password !== $confirm_password) {
        echo json_encode([
            'status' => false,
            'message' => 'New password and confirm password do not match'
        ]);
        return;
    }

    $user = $this->UserModel->getById($user_id);

    if (!$user) {
        echo json_encode([
            'status' => false,
            'message' => 'User not found'
        ]);
        return;
    }

    if (!password_verify($old_password, $user->password)) {
        echo json_encode([
            'status' => false,
            'message' => 'Old password is incorrect'
        ]);
        return;
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $updated = $this->UserModel->updateById($user_id, [
        'password' => $hashed_password
    ]);

    echo json_encode([
        'status' => $updated ? true : false,
        'message' => $updated ? 'Password updated successfully' : 'Password update failed'
    ]);
}







}
?>