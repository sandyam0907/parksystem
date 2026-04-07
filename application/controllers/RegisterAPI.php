<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegisterAPI extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function register()
    {
        header('Content-Type:application/json');

        $firstname = trim($this->input->post('firstname'));
        $lastname = trim($this->input->post('lastname'));
        $email = trim($this->input->post('email'));
        $mobile = trim($this->input->post('mobile'));
        $password = trim($this->input->post('password'));

        if (!$firstname && !$lastname && !$email && !$mobile && !$password) {
            echo json_encode([
                'status' => false,
                'message' => 'All fields are required'
            ]);
            return;
        }

        if (!$firstname) {
            echo json_encode([
                'status' => false,
                'message' => 'First name is required'
            ]);
            return;
        }

        if (!$lastname) {
            echo json_encode([
                'status' => false,
                'message' => 'Last name is required'
            ]);
            return;
        }

        if (!$email) {
            echo json_encode([
                'status' => false,
                'message' => 'Email is required'
            ]);
            return;
        }

        if (!$mobile) {
            echo json_encode([
                'status' => false,
                'message' => 'Mobile is required'
            ]);
            return;
        }

        if (!$password) {
            echo json_encode([
                'status' => false,
                'message' => 'Password is required'
            ]);
            return;
        }
        if ($this->UserModel->emailExists($email)) {
            echo json_encode([
                'status' => false,
                'message' => 'Email already exists'
            ]);
            return;
        }

        /*if ($this->UserModel->usernameExists($username)) {
            echo json_encode([
                'status' => false,
                'message' => 'Username already exists'
            ]);
            return;
        }*/
        $data = [
            'name' => $firstname . ' ' . $lastname,
            'email' => $email,
            'mobile' => $mobile,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role_id' => 3, // user
            'status' => 1
        ];

        $this->UserModel->insertUser($data);

        echo json_encode([
            'status' => true,
            'message' => 'User registered successfully'
        ]);

    }

    public function getUsers()
    {
        header('Content-Type: application/json');

        $users = $this->UserModel->getActiveUsersForApi();

        echo json_encode([
            'status' => true,
            'data' => $users
        ]);
    }

}


?>