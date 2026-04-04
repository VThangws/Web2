<?php
require_once __DIR__ . "/../model/DocGia.php";
require_once __DIR__ . "/../DAO/DocGiaDAO.php";

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if(!isset($_SESSION['docgia'])){

    echo json_encode([
        "success"=>false,
        "message"=>"Chưa đăng nhập"
    ]);

    exit();
}

$docgia = $_SESSION['docgia'];

$docgia->setHodocgia($_POST['hodocgia'] ?? "");
$docgia->setTendocgia($_POST['tendocgia'] ?? "");
$docgia->setSdt($_POST['sdt'] ?? "");
$docgia->setDiachi($_POST['diachi'] ?? "");

$dao = new DocGiaDAO();

$result = $dao->capNhatThongTin($docgia);

if($result['success']){

    $_SESSION['docgia']=$docgia;

    echo json_encode([
        "success"=>true,
        "message"=>$result['message'],
        "user"=>[
            "hodocgia"=>$docgia->getHodocgia(),
            "tendocgia"=>$docgia->getTendocgia(),
            "sdt"=>$docgia->getSdt(),
            "diachi"=>$docgia->getDiachi()
        ]
    ]);

}
else{

    echo json_encode($result);

}