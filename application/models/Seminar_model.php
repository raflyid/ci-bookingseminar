<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seminar_model extends CI_Model {
    public function getSeminar(){
        $this->db->select('seminar.*, users.fullname, rekening.nama_bank, rekening.no_rek, rekening.atas_nama');
        $this->db->from('seminar');
        $this->db->join('users', 'users.id_user=seminar.id_user', 'left');
        $this->db->join('rekening', 'rekening.id_seminar=seminar.id_seminar', 'left');
        $query =  $this->db->get();
        return $query;
    }

    public function getSeminarCoord($id_user){
        $this->db->select('seminar.*, users.fullname');
        $this->db->from('seminar');
        $this->db->join('users', 'users.id_user=seminar.id_user', 'left');
        $this->db->where('users.id_user', $id_user);
        $query = $this->db->get();
        return $query;
    }

    public function uploadImage(){
        $config['upload_path']          = '../bookingseminar/seminar_img/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = '8192';
        $config['remove_space']         = TRUE; // 1MB

        $this->load->library('upload', $config); // Load konfigurasi uploadnya
        if ($this->upload->do_upload('image')) { // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        } else {
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    }

    public function get_image($id_seminar)
    {
        $this->db->select('image');
        $this->db->from('seminar');
        $this->db->where('id_seminar', $id_seminar);
        return $this->db->get()->row();
    }

    public function getSeminarScan($id_seminar){
        $this->db->select('seminar.*');
        $this->db->from('seminar');
        $this->db->where('id_seminar', $id_seminar);
        $query = $this->db->get();
        return $query;
    }
}