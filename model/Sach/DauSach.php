<?php
  class DauSach {
    private $madausach;
    private $tensach;
    private $namxuatban;
    private $dongia;
    private $matacgia;
    private $matheloai;
    private $manxb;
    private $mota;
    private $anhbia;

    public function __construct($madausach, $tensach, $namxuatban, $dongia, $matacgia, $matheloai, $manxb, $mota, $anhbia) {
      $this->madausach = $madausach;
      $this->tensach = $tensach;
      $this->namxuatban = $namxuatban;
      $this->dongia = $dongia;
      $this->matacgia = $matacgia;
      $this->matheloai = $matheloai;
      $this->manxb = $manxb;
      $this->mota = $mota;
      $this->anhbia = $anhbia;
    }

    public function getMadausach() {
      return $this->madausach;
    }

    public function getTensach() {
      return $this->tensach;
    }

    public function getNamxuatban() {
      return $this->namxuatban;
    }

    public function getDongia() {
      return $this->dongia;
    }

    public function getMatacgia() {
      return $this->matacgia;
    }

    public function getMatheloai() {
      return $this->matheloai;
    }

    public function getManxb() {
      return $this->manxb;
    }

    public function getMota() {
      return $this->mota;
    }

    public function getAnhbia() {
      return $this->anhbia;
    }
  }
?>