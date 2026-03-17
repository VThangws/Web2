<?php
  class NhaXuatBan {
    private $manxb;
    private $tennxb;
    private $diachi;
    private $sdt;
    private $email;

    public function __construct($manxb, $tennxb, $diachi, $sdt, $email) {
      $this->manxb = $manxb;
      $this->tennxb = $tennxb;
      $this->diachi = $diachi;
      $this->sdt = $sdt;
      $this->email = $email;
    }

    public function getManxb() {
      return $this->manxb;
    }

    public function getTennxb() {
      return $this->tennxb;
    }

    public function getDiachi() {
      return $this->diachi;
    }

    public function getSdt() {
      return $this->sdt;
    }

    public function getEmail() {
      return $this->email;
    }
  }
?>