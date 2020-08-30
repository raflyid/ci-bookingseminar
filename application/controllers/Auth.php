<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index(){
        if($this->session->userdata('email')) {
          redirect('coordinator');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false){
            $data['title'] = 'Login Page';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            //if success
            $this->_login();
        }

    }

    private function _login() {

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('users', ['email' => $email])->row_array();

        if ($user) {

            if ($user['is_active'] == 1) {

                if(password_verify($password, $user['password'])){
                    $data = [
                    'id_user' => $user['id_user'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
                ];
                $this->session->set_userdata($data);
                    if($user['role_id'] == '1'){
                        redirect('admin');
                    }else if ($user['role_id'] == '2'){
                        redirect('coordinator');
                    }else if ($user['role_id'] == '3'){
                        redirect('auth');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered!</div>');
            redirect('auth');
        }
    }

    public function registration() {

        if($this->session->userdata('email')) {
          redirect('coordinator');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
            'is_unique' => 'This email has already registered!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', [
            'matches' => 'Password does not match!',
            'min_length' => 'Password too short!'
            ]);
        $this->form_validation->set_rules('fullname', 'Fullname', 'required|trim');
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim');

        if($this->form_validation->run() == false) {
            $data['title'] = 'User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'email' => htmlspecialchars($email),
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'fullname' => ($this->input->post('fullname')),
                'phone' => $this->input->post('phone'),
                'role_id' => '2',
                'is_active' => '0'
            ];
            
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];

            $this->db->insert('users', $data);
            $this->db->insert('user_token', $user_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Congratulation! Your account has been created. Please activate your account!</div>');
            redirect('auth');
        }
    }

    private function _sendEmail($token, $type) {
        
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'bookingseminarid@gmail.com',
            'smtp_pass' => 'dodolgarut',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];

        $this->email->initialize($config);

        $this->email->from('bookingseminarid@gmail.com', 'Booking Seminar');
        $this->email->to($this->input->post('email'));

        if($type == 'verify') {
            $data = array('token' => $token);
            $email_auth = $this->load->view('templates/email_auth', $data, true);
            $this->email->subject('Email Verification - Booking Seminar');
            $this->email->message($email_auth);
            // $this->email->subject('Email Verification - Booking Seminar');
            // $this->email->message('Click this link to verify your account : <a href="'. base_url() . 'auth/verify?email=' .$this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'forgot') {
            $data = array('token' => $token);
            $forgot_password = $this->load->view('templates/forgot_password', $data, true);
            $this->email->subject('Reset Password - Booking Seminar');
            $this->email->message($forgot_password);
            // $this->email->subject('Reset Password - Booking Seminar');
            // $this->email->message('Click this link to reset your password : <a href="'. base_url() . 'auth/resetpassword?email=' .$this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password!</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify() {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('users', ['email' => $email])->row_array();

        if($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if($user_token){
                if(time() - $user_token['date_created'] < (60*60*24)) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('users');

                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">'. $email .' has been activated! Please login.</div>');
                    redirect('auth');
                } else {
                    $this->db->delete('users', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Token expired.</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong token.</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong email.</div>');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out!</div>');
        redirect('auth');
    }

    public function blocked() {
      $this->load->view('auth/blocked');
    }

    public function forgotPassword() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if($this->form_validation->run() == false) {
        $data['title'] = 'Forgot Password';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/forgot-password');
        $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('users', ['email' => $email, 'is_active' => 1])->row_array();

            if($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Please check your email to reset your password!</div>');
                redirect('auth/forgotpassword');

            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered or activated!</div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetPassword(){
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('users', ['email' => $email])->row_array();

        if($user){
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong token.</div>');
                redirect('auth');
            }
        } else {
             $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong email.</div>');
             redirect('auth');
        }
    }

    public function changePassword() {
        if(!$this->session->userdata('reset_email')){
            redirect('auth');
        }

        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[6]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|min_length[6]|matches[password1]');

        if($this->form_validation->run() == false) {
            $data['title'] = 'Change Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates/auth_footer');
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('users');

            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password has been changed! Please login.</div>');
            redirect('auth');
            
        }
        
    }

}
