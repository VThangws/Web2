<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
</style>
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@latest/dist/dotlottie-wc.js" type="module"></script>

<style>
  /* Áp dụng font Oswald cho toàn bộ trang lỗi */
  .error-page-wrapper {
    font-family: 'Oswald', sans-serif; /* Ép font Oswald tại đây */
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px 0;
    margin: 0;
  }

  .error-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    width: 100%;
  }

  /* Chỉnh lại độ lớn và kiểu chữ cho đẹp với font Oswald */
  .error-content h1 {
    font-weight: 700;
    letter-spacing: 2px;
  }

  .error-content p {
    font-weight: 400;
    text-transform: uppercase; /* Font Oswald cực đẹp khi viết hoa */
  }

  .lottie-animation-wrapper {
    margin-bottom: 5px;
    margin-top:-20px;
  }
</style>

<div class="error-page-wrapper">
    <div class="error-container">
        <div class="lottie-animation-wrapper">
            <dotlottie-wc
                src="https://lottie.host/7a06aa7e-c2c2-42f6-bdf0-ee3c2a2e5aab/txlMCyPY2n.lottie" 
                style="width: 600px; height: 400px;" 
                autoplay
                loop>
            </dotlottie-wc>
        </div>
        
        <div class="error-content">
          <p class="fs-3" style="font-family: 'Oswald', sans-serif;">Không tìm thấy sản phẩm!</p>
          <div class="mt-4">
              <a href="index.php?page=books" class="btn btn-primary btn-lg px-5 shadow-sm"style="background-color: #20c997; color: white; border: none; font-family: 'Oswald', sans-serif;">
                Quay về trang sản phẩm
              </a>
          </div>
        </div>
    </div>
</div>