<?php
  class TheLoai {
    private $matheloai;
    private $tentheloai;

    public function __construct($matheloai, $tentheloai) {
      $this->matheloai = $matheloai;
      $this->tentheloai = $tentheloai;
    }

    public function getMatheloai() {
      return $this->matheloai;
    }

    public function getTentheloai() {
      return $this->tentheloai;
    }
  }
?>