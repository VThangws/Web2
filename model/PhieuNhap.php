<?php
  class PhieuNhap {
    private $maphieunhap;
    private $thoigiantao;
    private $tongtien;
    private $manv;
    private $mancc;

    public function __construct($maphieunhap, $thoigiantao, $tongtien, $manv, $mancc) {
      $this->maphieunhap = $maphieunhap;
      $this->thoigiantao = $thoigiantao;
      $this->tongtien = $tongtien;
      $this->manv = $manv;
      $this->mancc = $mancc;
    }

    public function getMaphieunhap() {
      return $this->maphieunhap;
    }

    public function getThoigiantao() {
      return $this->thoigiantao;
    }

    public function getTongtien() {
      return $this->tongtien;
    }

    public function getManv() {
      return $this->manv;
    }

    public function getMancc() {
      return $this->mancc;
    }
  }
?>