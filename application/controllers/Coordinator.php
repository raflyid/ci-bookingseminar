<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coordinator extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();
        $this->load->model('Seminar_model');
        $this->load->model('Pembayaran_model');
        $this->load->model('Rekening_model');
        $this->load->model('Absensi_model');
        $this->load->helper('form');
        $this->load->helper('url');
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('coordinator/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit() {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('fullname', 'Fullname', 'required|trim');

        if($this->form_validation->run() == false) {
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('coordinator/edit', $data);
          $this->load->view('templates/footer');
        } else {
          $fullname = $this->input->post('fullname');
          $email = $this->input->post('email');
          
          $this->db->set('fullname', $fullname);
          $this->db->where('email', $email);
          $this->db->update('users');
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profil berhasil di update!</div>');
          redirect('coordinator');
        }
    }

    public function changePassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[6]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[6]|matches[new_password1]');

        if($this->form_validation->run() == false) {
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('coordinator/changepassword', $data);
          $this->load->view('templates/footer');
        } else {
          $current_password = $this->input->post('current_password');
          $new_password = $this->input->post('new_password1');

          if(!password_verify($current_password, $data['user']['password'])){
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password!</div>');
            redirect('coordinator/changepassword');
          } else {
            if($current_password == $new_password) {
              $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password!</div>');
              redirect('coordinator/changepassword');
            } else {
              //password ok
              $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

              $this->db->set('password', $password_hash);
              $this->db->where('email', $this->session->userdata('email'));
              $this->db->update('users');

              $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password changed!</div>');
              redirect('coordinator/changepassword');
            }
          }
        }
    }

    //SEMINAR CONT

     public function seminar() {
        $data['title'] = 'Seminar';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $id_user = $this->session->userdata('id_user');
        $data['seminar'] = $this->Seminar_model->getSeminarCoord($id_user)->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('short_desc', 'Description', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required');
        $this->form_validation->set_rules('slot', 'Slot', 'required');
        $this->form_validation->set_rules('pembicara', 'Pembicara', 'required');
        $this->form_validation->set_rules('tempat', 'Tempat', 'required');
        $this->form_validation->set_rules('tanggal_seminar', 'Tanggal Seminar', 'required');
        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required');
        $this->form_validation->set_rules('no_rek', 'No. Rek', 'required');
        $this->form_validation->set_rules('atas_nama', 'Atas Nama', 'required');
        
        if($this->form_validation->run() == false){
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('coordinator/seminar', $data);
          $this->load->view('templates/footer');
        } else {
          $server_ip = gethostbyname(gethostname());
          $upload = $this->Seminar_model->uploadImage();
          $image = $upload['file']['file_name'];
          $uploadimg =  'http://' . $server_ip . '/bookingseminarapp/bookingseminar/seminar_img/' . $image;
          $data = [
            'id_user' => $this->input->post('id_user'),
            'title' => $this->input->post('title'),
            'short_desc' => $this->input->post('short_desc'),
            'harga' => $this->input->post('harga'),
            'slot' => $this->input->post('slot'),
            'pembicara' => $this->input->post('pembicara'),
            'tempat' => $this->input->post('tempat'),
            'tanggal_seminar' => $this->input->post('tanggal_seminar'),
            'image' => $uploadimg
          ];
          $this->db->insert('seminar', $data);
          $id_seminar = $this->db->insert_id();

          // var_dump($data);
          // die;
          
          $rek = [
            'id_seminar' => $id_seminar,
            'id_user' => $this->input->post('id_user'),
            'nama_bank' => $this->input->post('nama_bank'),
            'no_rek' => $this->input->post('no_rek'),
            'atas_nama' => $this->input->post('atas_nama')
          ];
        $this->db->insert('rekening', $rek);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Seminar berhasil ditambahkan!</div>');
        redirect('coordinator/seminar');
        }
      }

    public function editSeminar() {
        $data['title'] = 'Seminar';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $data['seminar'] = $this->Seminar_model->getSeminar()->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('short_desc', 'Description', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required');
        $this->form_validation->set_rules('slot', 'Slot', 'required');
        $this->form_validation->set_rules('pembicara', 'Pembicara', 'required');
        $this->form_validation->set_rules('tempat', 'Tempat', 'required');
        $this->form_validation->set_rules('tanggal_seminar', 'Tanggal Seminar', 'required');
        // $this->form_validation->set_rules('image', 'Image', 'required');

            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('templates/topbar', $data);
                $this->load->view('coordinator/seminar', $data);
                $this->load->view('templates/footer');
            } else {
                $id_seminar = $this->input->post('id_seminar');
                $data = [
                'title' => $this->input->post('title'),
                'short_desc' => $this->input->post('short_desc'),
                'harga' => $this->input->post('harga'),
                'slot' => $this->input->post('slot'),
                'pembicara' => $this->input->post('pembicara'),
                'tempat' => $this->input->post('tempat'),
                'tanggal_seminar' => $this->input->post('tanggal_seminar')
                // 'image' => $this->input->post('image')
          ];
          $this->db->where('id_seminar',$id_seminar);
          $this->db->update('seminar', $data);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Seminar berhasil diedit!</div>');
          redirect('coordinator/seminar');
            }
    }

    public function deleteSeminar() {
        $id_seminar = $this->uri->segment(3);
        $this->db->where('id_seminar',$id_seminar);
        $this->db->delete('seminar');
        $this->db->where('id_seminar',$id_seminar);
        $this->db->delete('rekening');
        $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Seminar berhasil dihapus!</div>');

        redirect('coordinator/seminar');
    }

    //PEMBAYARAN CONT

      public function pembayaran() {
        $data['title'] = 'Pembayaran';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $id_user = $this->session->userdata('id_user');
        $data['pembayaran'] = $this->Pembayaran_model->getPembayaranCoord($id_user)->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('coordinator/pembayaran', $data);
        $this->load->view('templates/footer');
    }
      
     public function editStatus() {
        $data['title'] = 'Pembayaran';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $data['pembayaran'] = $this->Pembayaran_model->getPembayaran()->result_array();

        $this->form_validation->set_rules('status', 'Status', 'required');

        $this->load->library('ciqrcode'); //pemanggilan library QR CODE

        $config['cacheable']    = true; //boolean, the default is true
        $config['cachedir']     = './assets/'; //string, the default is application/cache/
        $config['errorlog']     = './assets/'; //string, the default is application/logs/
        $config['imagedir']     = '../bookingseminar/qrcode/'; //direktori penyimpanan qr code
        $config['quality']      = true; //boolean, the default is true
        $config['size']         = '1024'; //interger, the default is 1024
        $config['black']        = array(224,255,255); // array, default is array(255,255,255)
        $config['white']        = array(70,130,180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);

        $id_pembayaran = $this->input->post('id_pembayaran');

        $server_ip = gethostbyname(gethostname());

        $qrcode=$id_pembayaran.'.png'; //buat name dari qr code sesuai dengan nim

        $uploadtodb =  'http://' . $server_ip . '/bookingseminarapp/bookingseminar/qrcode/' . $qrcode;
 
        $params['data'] = $id_pembayaran; //data yang akan di jadikan QR CODE
        $params['level'] = 'H'; //H=High
        $params['size'] = 10;
        $params['savename'] = FCPATH.$config['imagedir'].$qrcode; //simpan image QR CODE ke folder assets/images/
        $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('templates/topbar', $data);
                $this->load->view('coordinator/pembayaran', $data);
                $this->load->view('templates/footer');
            } else {
                $id_pembayaran = $this->input->post('id_pembayaran');
                $data = [
                'status' => $this->input->post('status'),
                'qrcode' => $uploadtodb
          ];
          $this->db->where('id_pembayaran',$id_pembayaran);
          $this->db->update('pembayaran', $data);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Status berhasil diedit!</div>');
          redirect('coordinator/pembayaran');
            }
    }

    //REKENING CONTROLLER

     public function rekening() {
        $data['title'] = 'Rekening';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $id_user = $this->session->userdata('id_user');
        $data['rekening'] = $this->Rekening_model->getRekeningCoord($id_user)->result_array();

        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required');
        $this->form_validation->set_rules('no_rek', 'No. Rek', 'required');
        $this->form_validation->set_rules('atas_nama', 'Atas Nama', 'required');
        
        if($this->form_validation->run() == false){
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('coordinator/rekening', $data);
          $this->load->view('templates/footer');
        } else {
          $data = [
            'fullname' => $this->input->post('fullname'),
            'title' => $this->input->post('title'),
            'nama_bank' => $this->input->post('nama_bank'),
            'no_rek' => $this->input->post('no_rek'),
            'atas_nama' => $this->input->post('atas_nama'),
          ];
        
        $this->db->insert('rekening', $data);      
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Seminar berhasil ditambahkan!</div>');
        redirect('coordinator/rekening');
        }
      }

       public function editRekening() {
        $data['title'] = 'Rekening';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $data['rekening'] = $this->Rekening_model->getRekening()->result_array();

        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required');
        $this->form_validation->set_rules('no_rek', 'No. Rek', 'required');
        $this->form_validation->set_rules('atas_nama', 'Atas Nama', 'required');

            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('templates/topbar', $data);
                $this->load->view('coordinator/rekening', $data);
                $this->load->view('templates/footer');
            } else {
                $id_rekening = $this->input->post('id_rekening');
                $data = [
                'nama_bank' => $this->input->post('nama_bank'),
                'no_rek' => $this->input->post('no_rek'),
                'atas_nama' => $this->input->post('atas_nama')
          ];
          $this->db->where('id_rekening',$id_rekening);
          $this->db->update('rekening', $data);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Rekening berhasil diedit!</div>');
          redirect('coordinator/rekening');
            }
    }

    public function scanner() {
        $data['title'] = 'QR-Code Scanner';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $id_seminar = $this->uri->segment(3);
        $data['seminar'] = $this->Seminar_model->getSeminarScan($id_seminar)->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('coordinator/scanner', $data);
        $this->load->view('templates/footer');
    }

    public function resultscan()
    {
        $data['title'] = 'QR-Code Scanner';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

         $this->form_validation->set_rules('id_pembayaran', 'ID Pembayaran', 'required|is_unique[absensi.id_pembayaran]', [
            'is_unique' => 'Berhasil melakukan scan QR Code!'
        ]);

          if ($this->form_validation->run() == false) {
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('coordinator/resultscan', $data);
          $this->load->view('templates/footer');
          } else {
          $tambah = [
            'id_pembayaran' => $this->input->post('id_pembayaran')
          ];
          $this->db->insert('absensi', $tambah);
          // redirect('coordinator/resultscan');
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('coordinator/resultscan', $data);
          $this->load->view('templates/footer');
        }
    }

    public function dataabsensi() {
        $data['title'] = 'Data Absensi';
        $id_seminar = $this->uri->segment(3);
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $data['dataabsensi'] = $this->Absensi_model->getAbsensi($id_seminar)->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('coordinator/dataabsensi', $data);
        $this->load->view('templates/footer');
    }

    public function editdataabsensi() {
        $data['title'] = 'Data Absensi';
        $id_seminar = $this->input->post('id_seminar');
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

          $id_absensi = $this->input->post('id_absensi');
          $upload = $this->Absensi_model->uploadImage();
                if (empty($upload['file']['file_name'])) {
                    $target_file = $this->Absensi_model->get_image($id_absensi);
                    $image = $target_file->sertifikat;
                } else {
                    $target_file = $this->Absensi_model->get_image($id_absensi);
                    $file = '../adminbookingseminar/assets/img/sertifikat/' . $target_file->sertifikat;
                    unlink($file);
                    $image = $upload['file']['file_name'];
                }
          $data = [
            'sertifikat' => $image
          ];

          $this->db->where('id_absensi', $id_absensi);
          $this->db->update('absensi', $data);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil upload sertifikat!</div>');
          redirect('coordinator/dataabsensi/'.$id_seminar);
    }
}