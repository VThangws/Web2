<?php
  class NhanVien {
    private $manv; // Mã nhân viên
    private $honv; // Họ nhân viên
    private $tennv; // Tên nhân viên
    private $gioitinh; // Giới tính
    private $sdt; // Số điện thoại
    private $ngaysinh; // Ngày sinh

    public function __construct($manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh) {
      $this->manv = $manv;
      $this->honv = $honv;
      $this->tennv = $tennv;
      $this->gioitinh = $gioitinh;
      $this->sdt = $sdt;
      $this->ngaysinh = $ngaysinh;
    }

    // phần getters
    public function getManv() {
      return $this->manv;
    }

    public function getHonv() {
      return $this->honv;
    }

    public function getTennv() {
      return $this->tennv;
    }

    public function getGioitinh() {
      return $this->gioitinh;
    }

    public function getSdt() {
      return $this->sdt;
    }

    public function getNgaysinh() {
      return $this->ngaysinh;
    }

    // hàm chưa hoàn thiện
    // public function showInfo() {
    //   // lấy dữ liệu về thông tin nhân viên
    //   $manv = $this->manv;
    //   $honv = $this->honv;
    //   $tennv = $this->tennv;
    //   $gioitinh = $this->gioitinh;
    //   $sdt = $this->sdt;
    //   $ngaysinh = $this->ngaysinh;

    //   // hiển thị thông tin nhân viên dạng 1 item trong danh sách html
    // }
  }
?>
