<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Watchman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('WatchmanModel');
        $this->load->model('ParkingModel');
		$this->load->model('ParkBookingModel');

    }


    public function index()
    {
        // login check
        if (!$this->session->userdata('uid')) {
            redirect('login');
            return;
        }
        $data['watchmen'] = $this->WatchmanModel->getAllWithParking();
        $this->load->view('admin/watchman_list', $data);
    }


    public function add()
    {
        // login check
        if (!$this->session->userdata('uid')) {
            redirect('login');
            return;
        }

        $data['parkings'] = $this->ParkingModel->getAll();

        $this->load->view('admin/watchman_add', $data);
    }

public function store()
{
    // login check
    $userId = $this->session->userdata('uid');
    if (!$userId) {
        redirect('login');
        return;
    }

    // get inputs
    $post = $this->input->post();

    $name          = $post['name'] ?? '';
    $email         = $post['email'] ?? '';
    $password      = $post['password'] ?? '';
    $phone         = $post['phone'] ?? '';
    $date_of_birth = $post['date_of_birth'] ?? '';
    $gender        = $post['gender'] ?? '';
    $salary        = $post['salary'] ?? '';
    $parking_id    = $post['parking_id'] ?? '';

    /* ================= VALIDATIONS ================= */

    if (
        !$name || !$email || !$password || !$phone ||
        !$date_of_birth || !$gender || !$salary || !$parking_id
    ) {
        $this->session->set_flashdata('error', 'All fields are required');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/add');
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->session->set_flashdata('error', 'Invalid email format');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/add');
        return;
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $this->session->set_flashdata('error', 'Phone number must be 10 digits');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/add');
        return;
    }

    if (!is_numeric($salary) || $salary <= 0) {
        $this->session->set_flashdata('error', 'Salary must be a positive number');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/add');
        return;
    }

    if (!strtotime($date_of_birth)) {
        $this->session->set_flashdata('error', 'Invalid date of birth');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/add');
        return;
    }

    /* ================= IMAGE UPLOAD ================= */

    $imageName = null;

    if (!empty($_FILES['watchman_image']['name'])) {

        $config['upload_path']   = FCPATH . 'uploads/watchman/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = time() . '_' . $_FILES['watchman_image']['name'];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('watchman_image')) {
            $this->session->set_flashdata(
                'error',
                strip_tags($this->upload->display_errors())
            );
            $this->session->set_flashdata('old', $post);
            redirect('watchman/add');
            return;
        }

        $imageName = $this->upload->data('file_name');
    }

    /* ================= SAVE ================= */

    $data = [
        'name'           => $name,
        'email'          => $email,
        'password'       => password_hash($password, PASSWORD_DEFAULT),
        'phone'          => $phone,
        'date_of_birth'  => $date_of_birth,
        'gender'         => $gender,
        'salary'         => $salary,
        'watchman_image' => $imageName,
        'status'         => 'active',
        'user_id'        => $userId,
        'parking_id'     => $parking_id,
        'created_at'     => date('Y-m-d H:i:s')
    ];

    if ($this->WatchmanModel->insert($data)) {
        $this->session->set_flashdata('success', 'Watchman created successfully');
        redirect('watchman');
        return;
    }

    $this->session->set_flashdata('error', 'Failed to create watchman');
    redirect('watchman/add');
}


    public function edit($watchman_id)
    {
        $data['watchman'] = $this->WatchmanModel->getById($watchman_id);
        $data['parkings'] = $this->ParkingModel->getAll();

        if (!$data['watchman']) {
            redirect('watchman/list');
            return;
        }

        $this->load->view('admin/watchman_edit', $data);

    }

public function update($watchman_id)
{
    // Login check
    $userId = $this->session->userdata('uid');
    if (!$userId) {
        redirect('login');
        return;
    }

    // Get POST data
    $post = $this->input->post();

    $name          = $post['name'] ?? '';
    $email         = $post['email'] ?? '';
    $phone         = $post['phone'] ?? '';
    $date_of_birth = $post['date_of_birth'] ?? '';
    $gender        = $post['gender'] ?? '';
    $salary        = $post['salary'] ?? '';
    $parking_id    = $post['parking_id'] ?? '';

    /* ================= VALIDATIONS ================= */

    if (
        !$name || !$email || !$phone ||
        !$date_of_birth || !$gender || !$salary || !$parking_id
    ) {
        $this->session->set_flashdata('error', 'All fields are required');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/edit/' . $watchman_id);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->session->set_flashdata('error', 'Invalid email format');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/edit/' . $watchman_id);
        return;
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $this->session->set_flashdata('error', 'Phone number must be 10 digits');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/edit/' . $watchman_id);
        return;
    }

    if (!is_numeric($salary) || $salary <= 0) {
        $this->session->set_flashdata('error', 'Salary must be positive');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/edit/' . $watchman_id);
        return;
    }

    if (!strtotime($date_of_birth)) {
        $this->session->set_flashdata('error', 'Invalid date of birth');
        $this->session->set_flashdata('old', $post);
        redirect('watchman/edit/' . $watchman_id);
        return;
    }

    /* ================= IMAGE UPDATE ================= */

    $watchman  = $this->WatchmanModel->getById($watchman_id);
    $imageName = $watchman->watchman_image ?? null;

    if (!empty($_FILES['watchman_image']['name'])) {

        $config['upload_path']   = FCPATH . 'uploads/watchman/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = time() . '_' . $_FILES['watchman_image']['name'];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('watchman_image')) {
            $this->session->set_flashdata(
                'error',
                strip_tags($this->upload->display_errors())
            );
            $this->session->set_flashdata('old', $post);
            redirect('watchman/edit/' . $watchman_id);
            return;
        }

        // delete old image
        if ($imageName && file_exists(FCPATH . 'uploads/watchman/' . $imageName)) {
            unlink(FCPATH . 'uploads/watchman/' . $imageName);
        }

        $imageName = $this->upload->data('file_name');
    }

    /* ================= UPDATE DATA ================= */

    $data = [
        'name'           => $name,
        'email'          => $email,
        'phone'          => $phone,
        'date_of_birth'  => $date_of_birth,
        'gender'         => $gender,
        'salary'         => $salary,
        'parking_id'     => $parking_id,
        'watchman_image' => $imageName,
        'updated_at'     => date('Y-m-d H:i:s')
    ];

    if ($this->WatchmanModel->update($watchman_id, $data)) {
        $this->session->set_flashdata('success', 'Watchman updated successfully');
        redirect('watchman');
        return;
    }

    $this->session->set_flashdata('error', 'Failed to update watchman');
    redirect('watchman/edit/' . $watchman_id);
}


    public function delete($watchman_id)
    {
        $watchman = $this->WatchmanModel->getById($watchman_id);

        if ($watchman && $watchman->watchman_image) {
            $path = FCPATH . 'uploads/watchman/' . $watchman->watchman_image;
            if (file_exists($path))
                unlink($path);
        }

        $this->WatchmanModel->delete($watchman_id);
        $this->session->set_flashdata('success', 'Watchman deleted successfully');
        redirect('watchman');
    }

    public function update_status()
    {
        $watchman_id = $this->input->post('watchman_id');
        $status = $this->input->post('status');

        if (!$watchman_id || !$status) {
            echo json_encode([
                'status' => false,
                'message' => 'Invalid data'
            ]);
            return;
        }

        $this->WatchmanModel->updateStatus($watchman_id, $status);

        echo json_encode([
            'status' => true,
            'message' => 'Status updated successfully'
        ]);
    }
	public function watchman_login()
    {
        $email = $this->input->post('email');
        $pass = $this->input->post('password');

        if (!$email || !$pass) {
            echo json_encode(['status' => false, 'message' => 'Email & password required']);
            return;
        }

        $watchman = $this->WatchmanModel->getByEmail($email);
        if (!$watchman || !password_verify($pass, $watchman->password)) {
            echo json_encode(['status' => false, 'message' => 'Invalid credentials']);
            return;
        }


        $this->session->set_userdata([
            'watchman_logged_in' => true,
            'watchman_id' => $watchman->watchman_id,
            'parking_id' => $watchman->parking_id,
            'watchman_name' => $watchman->name
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'Login success'
        ]);
    }
    public function todays_bookings($watchman_id)
    {
		$today = date('Y-m-d');
        // watchman login check
        if (!$watchman_id) {
            echo json_encode([
                'status' => false,
                'message' => 'Watchman Id Not Recognized.'
            ]);
            return;
        }
		$datewise=$this->input->post('datewise');
		$status=$this->input->post('status');
		if(isset($datewise) && $datewise!=''){
			$date=$datewise;
		}else{
			//$date=$today;
		}
		
		if(isset($status) && $status!=''){
			$parkstatus=$status;
		}else{
			//$parkstatus='ongoing';
		}

        $bookings = $this->ParkBookingModel
            ->getBookingsByWatchman($watchman_id,$date,$parkstatus);

        if (empty($bookings)) {
            echo json_encode([
                'status' => false,
                'message' => 'No bookings for the day'
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'date' => date('Y-m-d'),
            'count' => count($bookings),
            'data' => $bookings
        ]);
    }





}
