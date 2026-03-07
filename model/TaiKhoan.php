<?php
  class TaiKhoan {
    private $tendangnhap;
    private $matkhau;
    private $manhomquyen;
    private $manv;

    public function __construct($tendangnhap, $matkhau, $manhomquyen, $manv) {
      $this->tendangnhap = $tendangnhap;
      $this->matkhau = $matkhau;
      $this->manhomquyen = $manhomquyen;
      $this->manv = $manv;
    }

    // getters
    public function getTendangnhap() {
      return $this->tendangnhap;
    }

    public function getMatkhau() {
      return $this->matkhau;
    }

    public function getManhomnuyen() {
      return $this->manhomquyen;
    }

    public function getManv() {
      return $this->manv;
    }
  }
?>