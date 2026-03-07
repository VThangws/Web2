<?php
  class CTPhieuNhap {
    private $maphieunhap;
    private $madausach;
    private $donggianhap;
    private $soluong;

    public function __construct($maphieunhap, $madausach, $donggianhap, $soluong) {
      $this->maphieunhap = $maphieunhap;
      $this->madausach = $madausach;
      $this->donggianhap = $donggianhap;
      $this->soluong = $soluong;
    }

    public function getMaphieunhap() {
      return $this->maphieunhap;
    }

    public function getMadausach() {
      return $this->madausach;
    }

    public function getDonggianhap() {
      return $this->donggianhap;
    }

    public function getSoluong() {
      return $this->soluong;
    }
  }
?>