<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_model extends CI_Model {
  public function getPembayaran(){
        $this->db->select('pembayaran.*, seminar.title, seminar.harga, seminar.tempat, rekening.nama_bank, rekening.no_rek, rekening.atas_nama');
        $this->db->from('pembayaran');
        $this->db->join('seminar', 'seminar.id_seminar = pembayaran.id_seminar', 'left');
        $this->db->join('rekening', 'rekening.id_seminar = pembayaran.id_seminar', 'left');
        $this->db->order_by('tanggal_pemesanan', 'DESC');
        $query =  $this->db->get();
        return $query;
  }
  
  public function getPembayaranCoord($id_user){
        $this->db->select('pembayaran.*, seminar.id_seminar, seminar.id_user, seminar.title, seminar.short_desc, seminar.harga, seminar.slot, seminar.pembicara, seminar.tempat, seminar.tanggal_seminar, seminar.image, rekening.nama_bank, rekening.no_rek, rekening.atas_nama');
        $this->db->from('pembayaran');
        $this->db->join('seminar', 'seminar.id_seminar = pembayaran.id_seminar', 'left');
        $this->db->join('rekening', 'rekening.id_seminar = seminar.id_seminar', 'left');
        $this->db->where('seminar.id_user', $id_user);
        $this->db->order_by('tanggal_pemesanan', 'DESC');
        $query =  $this->db->get();
        return $query;
  }

}