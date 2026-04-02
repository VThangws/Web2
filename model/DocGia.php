<?php
class DocGia {
    private $madocgia;
    private $hodocgia;
    private $tendocgia;
    private $email;
    private $sdt;
    private $ngaysinh;
    private $diachi;

    // Các field không bắt buộc để null làm default
    public function __construct(
    $hodocgia, $tendocgia, $email,
    $sdt      = null,   // optional
    $ngaysinh = null,   // optional
    $diachi   = null,   // optional
    $madocgia = null    // null khi tạo mới, có giá trị sau khi lấy từ DB
    ){
        $this->madocgia  = $madocgia;
        $this->hodocgia  = $hodocgia;
        $this->tendocgia = $tendocgia;
        $this->email     = $email;
        $this->sdt       = $sdt;
        $this->ngaysinh  = $ngaysinh;
        $this->diachi    = $diachi;
    }

    // --- Getters ---
    public function getMadocgia()  { return $this->madocgia; }
    public function getHodocgia()  { return $this->hodocgia; }
    public function getTendocgia() { return $this->tendocgia; }
    public function getEmail()     { return $this->email; }
    public function getSdt()       { return $this->sdt; }
    public function getNgaysinh()  { return $this->ngaysinh; }
    public function getDiachi()    { return $this->diachi; }

    // --- Setters (để cập nhật sau) ---
    public function setHodocgia($hodocgia) { $this->hodocgia = $hodocgia; }
    public function setTendocgia($tendocgia) { $this->tendocgia = $tendocgia; }
    public function setSdt($sdt)           { $this->sdt = $sdt; }
    public function setNgaysinh($ngaysinh) { $this->ngaysinh = $ngaysinh; }
    public function setDiachi($diachi)     { $this->diachi = $diachi; }
}
?>