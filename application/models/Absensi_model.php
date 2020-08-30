<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {
    public function getAbsensi($id_seminar){
        $this->db->select('absensi.*, seminar.id_seminar, pembayaran.nama_pemesan, users.email, seminar.title, seminar.harga, seminar.pembicara, pembayaran.tanggal_pemesanan, pembayaran.status');
        $this->db->from('absensi');
        $this->db->join('pembayaran', 'pembayaran.id_pembayaran=absensi.id_pembayaran', 'left');
        $this->db->join('users', 'users.id_user=pembayaran.id_user', 'left');
        $this->db->join('seminar', 'seminar.id_seminar=pembayaran.id_seminar', 'left');
        $this->db->where('seminar.id_seminar', $id_seminar);
        $query =  $this->db->get();
        return $query;
    }

    public function getId(){
        $this->db->select('*');
        $this->db->from('absensi');
        $query =  $this->db->get();
        return $query;
    }

    public function uploadImage(){
        $config['upload_path']          = '../adminbookingseminar/assets/img/sertifikat/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = '8192';
        $config['remove_space']         = TRUE; // 1MB

        $this->load->library('upload', $config); // Load konfigurasi uploadnya
        if ($this->upload->do_upload('sertifikat')) { // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        } else {
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    } 

    public function get_image($id_absensi)
    {
        $this->db->select('sertifikat');
        $this->db->from('absensi');
        $this->db->where('id_absensi', $id_absensi);
        return $this->db->get()->row();
    }
}