<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

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

    public function index() {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    // ROLE CONT

    public function role() {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }

    public function roleAccess($role_id) {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', '1');
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function changeAccess() {
      $menu_id = $this->input->post('menuId');
      $role_id = $this->input->post('roleId');

      $data = [
        'role_id' => $role_id,
        'menu_id' => $menu_id
      ];

      $result = $this->db->get_where('user_access_menu', $data);

      if($result->num_rows() < 1) {
        $this->db->insert('user_access_menu', $data);
      } else {
        $this->db->delete('user_access_menu', $data);
      }

      $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access Changed!</div>');
    }

    //SEMINAR CONT

     public function seminar() {
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
        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required');
        $this->form_validation->set_rules('no_rek', 'No. Rek', 'required');
        $this->form_validation->set_rules('atas_nama', 'Atas Nama', 'required');
        
        if($this->form_validation->run() == false){
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('admin/seminar', $data);
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

          $rek = [
            'id_seminar' => $id_seminar,
            'id_user' => $this->input->post('id_user'),
            'nama_bank' => $this->input->post('nama_bank'),
            'no_rek' => $this->input->post('no_rek'),
            'atas_nama' => $this->input->post('atas_nama')
          ];
        
          $this->db->insert('rekening', $rek);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Seminar berhasil ditambahkan!</div>');
          redirect('admin/seminar');
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

            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('templates/topbar', $data);
                $this->load->view('admin/seminar', $data);
                $this->load->view('templates/footer');
            } else {
                $server_ip = gethostbyname(gethostname());
                $id_seminar = $this->input->post('id_seminar');
                $upload = $this->Seminar_model->uploadImage();
                  if (empty($upload['file']['file_name'])) {
                      $target_file = $this->Seminar_model->get_image($id_seminar);
                      $image = $target_file->image;
                  } else {
                      $target_file = $this->Seminar_model->get_image($id_seminar);
                      $file = '../bookingseminar/seminar_img/' . $target_file->image;
                      // unlink($file);
                      $image = $upload['file']['file_name'];
                  }
                $data = [
                'title' => $this->input->post('title'),
                'short_desc' => $this->input->post('short_desc'),
                'harga' => $this->input->post('harga'),
                'slot' => $this->input->post('slot'),
                'pembicara' => $this->input->post('pembicara'),
                'tempat' => $this->input->post('tempat'),
                'tanggal_seminar' => $this->input->post('tanggal_seminar')
                // 'image' => $image
          ];
          
          // print_r($data);
          // die;
          
          $this->db->where('id_seminar',$id_seminar);
          $this->db->update('seminar', $data);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Seminar berhasil diedit!</div>');
          redirect('admin/seminar');
          }
    }

    public function deleteSeminar() {
        $id_seminar = $this->uri->segment(3);
        $this->db->where('id_seminar',$id_seminar);
        $this->db->delete('seminar');
        $this->db->where('id_seminar',$id_seminar);
        $this->db->delete('rekening');
        $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Seminar berhasil dihapus!</div>');

        redirect('admin/seminar');
    }

    //PEMBAYARAN CONT

      public function pembayaran() {
        $data['title'] = 'Pembayaran';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $data['pembayaran'] = $this->Pembayaran_model->getPembayaran()->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/pembayaran', $data);
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
                $this->load->view('admin/pembayaran', $data);
                $this->load->view('templates/footer');
            } else {
                $id_pembayaran = $this->input->post('id_pembayaran');
                $data = [
                'status' => $this->input->post('status'),
                'qrcode' => $uploadtodb
          ];
          $this->db->where('id_pembayaran',$id_pembayaran);
          $this->db->update('pembayaran', $data);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Status berhasil diubah!</div>');
          redirect('admin/pembayaran');
            }
    }

    public function deletePembayaran() {
        $id_pembayaran = $this->uri->segment(3);
        $this->db->where('id_pembayaran',$id_pembayaran);
        $this->db->delete('pembayaran');
        $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Pembayaran berhasil dihapus!</div>');

        redirect('admin/pembayaran');
    }

     //REKENING CONTROLLER

     public function rekening() {
        $data['title'] = 'Rekening';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $data['rekening'] = $this->Rekening_model->getRekening()->result_array();

        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required');
        $this->form_validation->set_rules('no_rek', 'No. Rek', 'required');
        $this->form_validation->set_rules('atas_nama', 'Atas Nama', 'required');
        
        if($this->form_validation->run() == false){
          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar', $data);
          $this->load->view('templates/topbar', $data);
          $this->load->view('admin/rekening', $data);
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
        redirect('admin/rekening');
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
                $this->load->view('admin/rekening', $data);
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
          redirect('admin/rekening');
            }
    }

    public function deleteRekening() {
        $id_pembayaran = $this->uri->segment(3);
        $this->db->where('id_rekening',$id_rekening);
        $this->db->delete('rekening');
        $this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Rekening berhasil dihapus!</div>');

        redirect('admin/rekening');
    }

    public function dataabsensi() {
        $data['title'] = 'Data Absensi';
        $id_seminar = $this->uri->segment(3);
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $data['dataabsensi'] = $this->Absensi_model->getAbsensi($id_seminar)->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/dataabsensi', $data);
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
            'email' => $this->input->post('email'),
            'sertifikat' => $image
          ];

          $this->db->where('id_absensi', $id_absensi);
          $this->db->update('absensi', $data);
          $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil upload sertifikat!</div>');
          redirect('admin/dataabsensi/'.$id_seminar);
    }

}