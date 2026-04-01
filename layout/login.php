<?php
require_once __DIR__. '/../DAO/DocGiaDAO.php';
require_once __DIR__. '/../model/DocGia.php';
require_once __DIR__. '/../database/ConnectDB.php';

$dao = new DocGiaDAO();
$thongbao = '';

if (isset($_POST['btn-register'])) {
    $dg = new DocGia(
        $_POST['hodocgia'],
        $_POST['tendocgia'],
        $_POST['email'],
        null, // sdt
        null, // ngaysinh
        null  // diachi
    );
    $matkhau = $_POST['matkhau'];

    $result = $dao->dangKy($dg, $matkhau);
    if($result['success']){
        $thongbao = 'Đăng ký thành công!';
    } else {
        $thongbao = $result["message"];
    }
}
?>

<link rel="stylesheet" href="/assets/css/login.css">
<script src="https://kit.fontawesome.com/8cf433228b.js" crossorigin="anonymous"></script>

<div class="container-fluid center-full">
    <div class="wrapper">
        <div class="close">
            <i class="fa-solid fa-xmark"></i>
        </div>

        <div class="modal fade" id="loginModal">
            <div class="modal-dialog modal-top-right">
                <div class="modal-content border-0 shadow-lg" id="modalContent">
                    <div class="modal-body text-center p-3" id="loginMessage">
                        <!-- Nội dung sẽ được thêm bằng JS -->
                    </div>
                </div>
            </div>
        </div>

        <div class="form-box login">
            <h2>Đăng Nhập</h2>
            <form id="loginForm" method="POST" action="">
                <div class="input-box">
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <input type="password" name="matkhau" required>
                    <label>Mật Khẩu</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox">Ghi nhớ</label>
                    <a href="#">Quên mật khẩu</a>
                </div>
                <button class="btn btn-custom" type="submit">Đăng Nhập</button>
                <div class="login-register">
                    <p>Không có tài khoản?<a href="#" class="register-link"> Đăng Ký</a></p>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <h2>Đăng Ký</h2>
            <form id="registerForm" method="POST" action="">
               <div class="row g-0 mb-input">
                    <div class="col-6 pe-2">
                        <div class="input-box">
                            <input type="text" name="hodocgia" required>
                            <label>Họ</label>
                        </div>
                    </div>
                    <div class="col-6 ps-2">
                        <div class="input-box">
                            <input type="text" name="tendocgia" required>
                            <label>Tên</label>
                        </div>
                    </div>
                </div>

                <div class="input-box">
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <input type="password" name="matkhau" required>
                    <label>Mật Khẩu</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox">Tôi đồng ý với các điều khoản và điều kiện</label>
                </div>
                <button type="submit" class="btn btn-custom" name="btn-register">Đăng Ký</button>
                <div class="login-register">
                    <p>Đã có tài khoản?<a href="#" class="login-link"> Đăng Nhập</a></p>
                </div>
            </form>
        </div>
    </div>
</div>    
    
<script src="/assets/js/login.js" defer ></script>
