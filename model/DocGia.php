<?php
  class DocGia {
    private $madocgia;
    private $hodocgia;
    private $tendocgia;
    private $email;
    private $sdt;
    private $ngaysinh;
    private $diachi;

    public function __construct($madocgia, $hodocgia, $tendocgia, $email, $sdt, $ngaysinh, $diachi) {
      $this->madocgia = $madocgia;
      $this->hodocgia = $hodocgia;
      $this->tendocgia = $tendocgia;
      $this->email = $email;
      $this->sdt = $sdt;
      $this->ngaysinh = $ngaysinh;
      $this->diachi = $diachi;
    }

    public function getMadocgia() {
      return $this->madocgia;
    }

    public function getHodocgia() {
      return $this->hodocgia;
    }

    public function getTendocgia() {
      return $this->tendocgia;
    }

    public function getEmail() {
      return $this->email;
    }

    public function getSdt() {
      return $this->sdt;
    }

    public function getNgaysinh() {
      return $this->ngaysinh;
    }

    public function getDiachi() {
      return $this->diachi;
    }
  }
?>