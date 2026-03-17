<?php
  class TacGia {
    private $matacgia;
    private $tentacgia;

    public function __construct($matacgia, $tentacgia) {
      $this->matacgia = $matacgia;
      $this->tentacgia = $tentacgia;
    }

    public function getMatTacGia() {
      return $this->matacgia;
    }

    public function getTenTacGia() {
      return $this->tentacgia;
    }
  }
?>