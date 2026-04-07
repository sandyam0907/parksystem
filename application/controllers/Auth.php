<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->library(['form_validation','session']);
        require APPPATH.'libraries/PHPMailer/src/PHPMailer.php';
        require APPPATH.'libraries/PHPMailer/src/SMTP.php';
        require APPPATH.'libraries/PHPMailer/src/Exception.php';

    }

    public function register(){
        $this->load->view('auth/register');
    }

    public function register_save(){

        $this->form_validation->set_rules('email','Email','required|valid_email');
        if(!$this->form_validation->run()){
            $this->register();
            return;
        }

        if($this->UserModel->emailExists($this->input->post('email'))){
            $this->session->set_flashdata('error','Email already exists');
            redirect('register');
        }

        $data = [
            'name' => $this->input->post('name'),
            'email'=> $this->input->post('email'),
            'password'=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role_id'=> $this->input->post('role_id')
        ];

        $this->UserModel->insertUser($data);
        redirect('login');
    }

    public function login(){
        $this->load->view('auth/login');
    }

public function login_check()
{
    $email = $this->input->post('email');
    $pass  = $this->input->post('password');

    $user = $this->UserModel->login($email);

    if(!$user || !password_verify($pass,$user->password)){
        $this->session->set_flashdata('error','Invalid email or password');
        redirect('login');
    }

    if($user->role_id != 1){
        $this->session->set_flashdata('error','Only Admin Can online here');
        redirect('login');
    }

  
    $this->session->set_userdata([
        'uid'       => $user->id,
        'role_id'   => $user->role_id,
        'logged_in' => true
    ]);

    redirect('admin');
}

    
// public function parking_login()
// {
//     $email = $this->input->post('email');
//     $pass  = $this->input->post('password');

//     if(!$email || !$pass){
//         echo json_encode(['status'=>false,'message'=>'Email and password required']);
//         return;
//     }

//     $user = $this->UserModel->login($email);

//     if(!$user){
//         echo json_encode(['status'=>false,'message'=>'User not found']);
//         return;
//     }

//     if(!password_verify($pass,$user->password)){
//         echo json_encode(['status'=>false,'message'=>'Wrong password']);
//         return;
//     }

//     // session is set but NO redirect
//     $this->session->set_userdata([
//         'uid'=>$user->id,
//         'role_id'=>$user->role_id,
//         'logged_in'=>true
//     ]);

//     echo json_encode([
//         'status'=>true,
//         'message'=>'Login success',
//         'user'=>$user
//     ]);
// }



    
    public function logout(){
        $this->session->sess_destroy();
        redirect('login');
    }

    public function forgot(){
    $this->load->view('auth/forgot_password');
}

public function send_otp(){

    $email = trim($this->input->post('email'));
    if(!$email) redirect('forgot');

    $user = $this->UserModel->getUserByEmail($email);
    if(!$user){
        $this->session->set_flashdata('error','Email not registered');
        redirect('forgot');
    }

    $otp = rand(100000,999999);
    $this->UserModel->saveOTP($email,$otp);

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'ritugangavati22@gmail.com';
$mail->Password = 'quku zkxi klts cirt';   
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('ritugangavati22@gmail.com','CI System');
$mail->addAddress($email);
$mail->Subject = 'Password Reset OTP';
$mail->Body = 'Your OTP is: '.$otp;

$mail->send();


     $this->session->set_userdata('reset_email',$email);
    redirect('verify_otp');
}

public function verify_otp(){

    $email = $this->session->userdata('reset_email');
    if(!$email) redirect('forgot');

    $user = $this->UserModel->getUserByEmail($email);

    if(!$user || !$user->otp_expire || strtotime($user->otp_expire) < time()){
        $this->UserModel->clearOTP($email);
        $data['expired'] = true;
    }else{
        $data['expired'] = false;
        $data['expiry'] = strtotime($user->otp_expire);
    }

    $this->load->view('auth/verify_otp',$data);
}


public function check_otp(){

    $email = $this->session->userdata('reset_email');
    if(!$email) redirect('forgot');

    $otp = trim($this->input->post('otp'));

    $user = $this->UserModel->verifyOTP($email,$otp);
    if(!$user){
        $this->session->set_flashdata('error','Invalid or expired OTP');
        redirect('verify_otp');
    }

    redirect('new_password');
}


public function new_password(){
    $this->load->view('auth/new_password');
}

public function save_new_password(){

    $email = $this->session->userdata('reset_email');
    if(!$email) redirect('forgot');

    $pass = password_hash($this->input->post('password'),PASSWORD_DEFAULT);
    $this->UserModel->updatePassword($email,$pass);

    $this->session->unset_userdata('reset_email');
    redirect('login');
}



}
?>