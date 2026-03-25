<?php
  class TaiKhoan {
    private $tendangnhap;
    private $matkhau;
    private $manhomquyen;
    private $manv;
    private $madocgia;

    public function __construct($tendangnhap, $matkhau, $manhomquyen,
      $manv = NULL, 
      $madocgia = NULL
    ) {
      $this->tendangnhap = $tendangnhap;
      $this->matkhau = $matkhau;
      $this->manhomquyen = $manhomquyen;
      $this->manv = $manv;
      $this->madocgia = $madocgia;
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

    public function getMadocgia(){
      return $this->madocgia;
    }
  }
?>