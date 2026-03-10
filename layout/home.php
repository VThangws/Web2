<?php

// Header section
require_once '../layout/header.php';

// Main banner section
echo '<section class="d-md-block d-none">
    <div class="slide position-relative">
        <div class="banner-slider" style="width: 100%;">
            <img src="../assets/img/banner/library_banner1.jpg" alt="Library Banner 1" class="img-fluid slide-img">
            <img src="../assets/img/banner/library_banner2.jpg" alt="Library Banner 2" class="img-fluid slide-img">
            <img src="../assets/img/banner/library_banner3.jpg" alt="Library Banner 3" class="img-fluid slide-img">
            <img src="../assets/img/banner/library_banner4.jpg" alt="Library Banner 4" class="img-fluid slide-img">
        </div>
        <div class="position-absolute centerbutton">
            <a href="index.php?page=books" class="btn btn-outline-light rounded-0 fs-5 d-flex align-items-center justify-content-center effect-theloai rounded-1 fw-bold" style="width:150px;height: 50px;">
                Explore Books
            </a>
        </div>
    </div>
</section>';

// Categories section
echo '<section class="py-5 d-md-block d-none">
    <div class="container-md shadow-box p-2 rounded-1">
        <div class="row">
            <div class="col-md-2 d-flex justify-content-center">
                <img src="./assets/img/categories/new_arrivals.jpg" alt="New Arrivals" class="img-fluid effect-theloai" style="height: 110px;">
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <a href="index.php?page=books&category=fiction">
                  <img src="../assets/img/categories/fiction.jpg" alt="Fiction" class="img-fluid effect-theloai" style="height: 110px;">
                </a>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <a href="index.php?page=books&category=nonfiction">
                  <img src="./assets/img/categories/nonfiction.jpg" alt="Non-fiction" class="img-fluid effect-theloai" style="height: 110px;">
                </a>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <a href="index.php?page=books&category=children">
                  <img src="./assets/img/categories/children.jpg" alt="Children" class="img-fluid effect-theloai" style="height: 110px;">
                </a>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <a href="index.php?page=books&category=history">
                  <img src="./assets/img/categories/history.jpg" alt="History" class="img-fluid effect-theloai" style="height: 110px;">
                </a>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <a href="index.php?page=books&category=science">
                  <img src="./assets/img/categories/science.jpg" alt="Science" class="img-fluid effect-theloai" style="height: 110px;">
                </a>
            </div>
        </div>
    </div>
</section>';

// Footer section
require_once '../layout/footer.php';
?>