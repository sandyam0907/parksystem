<?php


require_once APPPATH.'libraries/phpqrcode/qrlib.php';




defined('BASEPATH') OR exit('No direct script access allowed');

class ParkBooking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ParkBookingModel');
		$this->load->model('ParkingModel');
	
    
	}

public function index()
{
		$booking_ref = $this->generateBookingRef();
		 
		 $data = "PARKING_ID=12|SLOT=A1|VEH=KA01AB1234";
 //$filename = 'uploads/qrcode/'.$booking_ref.'.png';
 $filename = 'uploads/qrcode/'.$booking_ref.'.png';
 $fulllink=base_url($filename);
QRcode::png($booking_ref, $filename, QR_ECLEVEL_L, 5);

//echo "<img src='".base_url($filename)."'>";exit;
}
private function generateBookingRef()
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    do {
        $random = '';
        for ($i = 0; $i < 5; $i++) {
            $random .= $chars[rand(0, strlen($chars) - 1)];
        }

        $booking_ref = 'BOOKING' . $random;

    } while ($this->ParkBookingModel->bookingRefExists($booking_ref));

    return $booking_ref;
}
    public function previewbooking()
    {
		
	}

    public function confirmbooking()
    {
        $park_id = $this->input->post('park_id');
		$parking = $this->ParkingModel->getByparkingId($park_id);
		$watchmanparking = $this->ParkingModel->getWatchmanList($park_id);
		$watchmanid = '';

		if(!empty($watchmanparking))
		{
			foreach($watchmanparking as $eachlist){
				$watchmanid .= $eachlist->watchman_id . ',';
			}

			$watchmanid = rtrim($watchmanid, ','); // remove last comma
		}


        $parking_name = $parking->parking_name;
		 $total_slots = $parking->total_slots;
        $parking_price = $parking->parking_price;
        $parking_slots = $parking->parking_slots;
        $user_id = $this->input->post('user_id');
        $booked_date = $this->input->post('booked_date');
        $start_time = $this->input->post('start_time');
        $parking_duration = $this->input->post('parking_duration');
        $total_amount = $parking_duration * $parking_price;
		$parking_location = $parking->parking_location;
		$parking_image = $parking->parking_image;
		$vehicle_name = $this->input->post('vehicle_name');
        $vehicle_model = $this->input->post('vehicle_model');
        $vehicle_number = $this->input->post('vehicle_number');
		
		$posting_status = $this->input->post('confirm');
		
		
		$latitude = $parking->latitude;
		$longitude = $parking->longitude;
		 $end_time = date(
            'H:i:s',
            strtotime($start_time . " +{$parking_duration} hours")
        );
		
		
		//get all ooking on date and time
		
         //$updatedata=$this->ParkBookingModel->getallbookings($booked_date,$start_time);		
		
$booked_slots = $this->ParkBookingModel->getBookedSlots(
    $park_id,
    $booked_date,
    $start_time,
    $end_time
);		
		$remaining_slots = $total_slots - $booked_slots;
		if($remaining_slots <=0){
			echo json_encode([
                'status' => false,
                'message' => 'Slots are not available to book at this time or date.Thank You.'
            ]);
            return;
		}

       /* if (
            !$park_id || !$user_id || !$booked_date ||
            !$start_time || !$parking_duration || !$total_amount || !$vehicle_name || !$vehicle_model || !$vehicle_number
        ) {
            echo json_encode([
                'status' => false,
                'message' => 'All fields are required'
            ]);
            return;
        }*/
		
		$errors = [];

if (!$park_id) {
    $errors[] = 'Park ID is required';
}

if (!$user_id) {
    $errors[] = 'User ID is required';
}

if (!$booked_date) {
    $errors[] = 'Booked Date is required';
}

if (!$start_time) {
    $errors[] = 'Start Time is required';
}

if (!$parking_duration) {
    $errors[] = 'Parking Duration is required';
}

if (!$total_amount) {
    $errors[] = 'Total Amount is required';
}

if (!$vehicle_name) {
    $errors[] = 'Vehicle Name is required';
}

if (!$vehicle_model) {
    $errors[] = 'Vehicle Model is required';
}

if (!$vehicle_number) {
    $errors[] = 'Vehicle Number is required';
}
if (!empty($errors)) {
    echo json_encode([
        'status' => false,
        'errors' => $errors
    ]);
    return;
}

        if (!preg_match('/^\d{2}:\d{2}$/', $start_time)) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid start_time format (HH:MM)'
            ]);
            return;
        }

        if (!is_numeric($parking_duration) || $parking_duration <= 0) {
            echo json_encode([
                'status' => false,
                'message' => 'parking_duration must be positive'
            ]);
            return;
        }

        $end_time = date(
            'H:i:s',
            strtotime($start_time . " +{$parking_duration} hours")
        );


        $booking_ref = $this->generateBookingRef();
		 
		 //$data = "PARKING_ID=12|SLOT=A1|VEH=KA01AB1234";
 $filename = 'uploads/qrcode/'.$booking_ref.'.png';
 $fulllink=base_url($filename);
QRcode::png($booking_ref, $filename, QR_ECLEVEL_L, 5);


        $data = [
            'booking_ref' => $booking_ref,
            'park_id' => $park_id,
            'parking_name ' => $parking_name,
            'parking_price' => $parking_price,
            'parking_slots' => $parking->parking_slots,
            'user_id' => $user_id,
            'booked_date' => $booked_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'parking_duration' => $parking_duration,
            'total_amount' => $total_amount,
            'booking_status' => 'Confirmed',
			'parking_booking_status' => 'ongoing',
			'parking_location' => $parking_location,
			'parking_image' => $parking_image,
            'latitude' => $latitude,
            'longitude' => $longitude,
				'vehicle_name' => $vehicle_name,
            'vehicle_model' => $vehicle_model,
            'vehicle_number' => $vehicle_number,
            'created_at' => date('Y-m-d H:i:s'),
			'scanner_qr_code'=>$filename,
			'watchman_id'=>$watchmanid
        ];
		
		if($posting_status=='confirmed'){

        if ($this->ParkBookingModel->insert($data)) {
			
			//update list
			$remaining = $parking->parking_slots - 1;
			 $data1 = [
            'parking_slots' => $remaining,
			'booked_slots' => $parking->booked_slots + 1,
			];
			//$updatedata=$this->ParkBookingModel->updateslots($park_id,$data1);
            echo json_encode([
                'status' => true,
                'message' => 'Booking stored successfully',
                'booking_ref' => $booking_ref,
                'end_time' => $end_time
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Failed to store booking'
            ]);
        }
		}else{
			echo json_encode([
                'status' => true,
                'message' => 'Booking status is not confirmed to book.'
            ]);
		}
    }


    public function getByBookingRef($booking_ref)
    {
        if (!$booking_ref) {
            echo json_encode([
                'status' => false,
                'message' => 'booking_ref is required'
            ]);
            return;
        }

        $booking = $this->ParkBookingModel->getByBookingRef($booking_ref);

        if ($booking) {
            echo json_encode([
                'status' => true,
                'data' => $booking
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Booking not found'
            ]);
        }
    }



    public function getParkBooking()
    {
        echo json_encode($this->ParkBookingModel->getAll());
    }
	
	 public function Bookings()
    {
        $data['bookings'] = $this->ParkBookingModel->getBookingWithUserDetails();
        $this->load->view('admin/my_bookings', $data);
    }
    public function viewBookingDetail($booking_id)
    {
        if (!$this->session->userdata('uid')) {
            redirect('login');
            return;
        }

        $booking = $this->ParkBookingModel->getFullBookingDetails($booking_id);
        $data['booking'] = $booking;
        $this->load->view('admin/booking_view', $data);
    }
    
public function update_status($booking_id)
{
    $status = $this->input->post('status'); 

    if (!$booking_id || !$status) {
        echo json_encode([
            'status' => false,
            'message' => 'booking_id and status are required'
        ]);
        return;
    }

    $allowed_status = ['ongoing', 'completed', 'cancelled'];

    if (!in_array($status, $allowed_status)) {
        echo json_encode([
            'status' => false,
            'message' => 'Invalid status'
        ]);
      return;
    }

    // check booking exists
    $booking = $this->ParkBookingModel->getByBookingId($booking_id);

    if (!$booking) {
        echo json_encode([
            'status' => false,
            'message' => 'Booking not found'
        ]);
        return;
    }

    $updateData = [
        'parking_booking_status' => $status,
    ];

    $updated = $this->ParkBookingModel->updateById($booking_id, $updateData);

    if ($updated) {
        echo json_encode([
            'status' => true,
            'message' => 'Status updated to ' . $status
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Failed to update'
        ]);
    }

}

public function cancelBookingByUser($booking_id)
{
    $user_id = $this->input->post('user_id');

    if (!$booking_id || !$user_id) {
        echo json_encode([
            'status' => false,
            'message' => 'booking_id and user_id are required'
        ]);
        return;
    }

    $booking = $this->ParkBookingModel->getByBookingId($booking_id);

    if (!$booking) {
        echo json_encode([
            'status' => false,
            'message' => 'Booking not found'
        ]);
        return;
    }

    if ($booking->user_id != $user_id) {
        echo json_encode([
            'status' => false,
            'message' => 'Unauthorized user'
        ]);
        return;
    }

    if ($booking->parking_booking_status == 'completed') {
        echo json_encode([
            'status' => false,
            'message' => 'Cannot cancel completed booking'
        ]);
        return;
    }

    if ($booking->parking_booking_status == 'cancelled') {
        echo json_encode([
            'status' => false,
            'message' => 'Booking already cancelled'
        ]);
        return;
    }

    $updateData = [
        'parking_booking_status' => 'cancelled',
        'booking_status' => 'cancelled',
    ];

    $updated = $this->ParkBookingModel->updateById($booking_id, $updateData);

    if ($updated) {
        echo json_encode([
            'status' => true,
            'message' => 'Booking cancelled successfully'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Failed to cancel booking'
        ]);
    }


}

}



?>