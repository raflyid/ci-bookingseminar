<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening_model extends CI_Model {
    public function getRekening(){
        $this->db->select('rekening.*, users.fullname, seminar.title, rekening.nama_bank, rekening.no_rek, rekening.atas_nama');
        $this->db->from('rekening');
        $this->db->join('seminar', 'seminar.id_seminar=rekening.id_seminar', 'left');
        $this->db->join('users', 'users.id_user=rekening.id_user', 'left');
        $query =  $this->db->get();
        return $query;
    }

    public function getRekeningCoord($id_user){
        $this->db->select('rekening.*, users.fullname, seminar.title, rekening.nama_bank, rekening.no_rek, rekening.atas_nama');
        $this->db->from('rekening');
        $this->db->join('seminar', 'seminar.id_seminar=rekening.id_seminar', 'left');
        $this->db->join('users', 'users.id_user=rekening.id_user', 'left');   
        $this->db->where('seminar.id_user', $id_user);
        $query =  $this->db->get();
        return $query;
    }
}