<?php
  class Sach {
    private $macuonsach;
    private $madausach;
    private $mavitri;
    private $trangthai;
    private $tinhtrang;

    public function __construct($macuonsach, $madausach, $mavitri, $trangthai, $tinhtrang) {
      $this->macuonsach = $macuonsach;
      $this->madausach = $madausach;
      $this->mavitri = $mavitri;
      $this->trangthai = $trangthai;
      $this->tinhtrang = $tinhtrang;
    }

    // getters
    public function getMacuonsach() {
      return $this->macuonsach;
    }

    public function getMadausach() {
      return $this->madausach;
    }

    public function getMavitri() {
      return $this->mavitri;
    }

    public function getTrangthai() {
      return $this->trangthai;
    }

    public function getTinhtrang() {
      return $this->tinhtrang;
    }

    // vì sách hiển thị theo từng block nên dùng cách này
    public function showInfo() {
      // trả về kết quả một cuốn sách theo dạng html
      return `
        <div>
          <h6>Mã cuốn sách: $this->macuonsach</h6>
          <h4>Mã đầu sách: $this->madausach</h4>
          <p>Vị trí: $this->mavitri</p>
          <p>Trạng thái: $this->trangthai</p>
          <p>Tình trạng: $this->tinhtrang</p>
        </div>
      `;
    }
  }
?>
