document.addEventListener('DOMContentLoaded', function () {
    
    // 1. ĐỒNG BỘ HÓA SỐ LƯỢNG
    function syncCartBadge() {
        fetch('/ajax/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            let badge = document.getElementById('cart-badge');
            let miniCount = document.getElementById('mini-cart-count');
            
            if (badge) {
                badge.innerText = data.total_items;
                if (data.total_items > 0) {
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            }
            if (miniCount) {
                miniCount.innerText = data.total_items;
            }
        })
        .catch(err => console.log('Lỗi đồng bộ:', err));
    }

    syncCartBadge();

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) syncCartBadge();
    });
    window.addEventListener('focus', syncCartBadge);

    // 2. XỬ LÝ MINI CART
    var miniCartEl = document.getElementById('miniCart');
    var miniCartBody = document.getElementById('mini-cart-body');

    function loadMiniCart(showLoading = true) {
        if(!miniCartBody) return;
        
        if (showLoading) {
            miniCartBody.innerHTML = '<div class="text-center mt-5"><i class="fa-solid fa-spinner fa-spin fs-3 text-secondary"></i><p class="mt-2 text-secondary small">Đang tải...</p></div>';
        }

        // === BƯỚC 1: LƯU VỊ TRÍ CUỘN TRƯỚC KHI LOAD HTML MỚI ===
        let scrollBox = document.querySelector('.custom-cart-scroll');
        let currentScrollPosition = scrollBox ? scrollBox.scrollTop : 0;
        // ========================================================

        fetch('/ajax/get_mini_cart.php')
        .then(response => response.json())
        .then(data => {
            miniCartBody.innerHTML = data.html; // Cập nhật thẳng HTML
            
            // === BƯỚC 2: TRẢ LẠI VỊ TRÍ CUỘN SAU KHI LOAD XONG ===
            // (Chỉ trả lại cuộn nếu không phải là lúc mở giỏ hàng lần đầu)
            let newScrollBox = document.querySelector('.custom-cart-scroll');
            if (newScrollBox && !showLoading) {
                newScrollBox.scrollTop = currentScrollPosition;
            }
            // =======================================================

            syncCartBadge(); 
        });
    }

    if(miniCartEl) {
        // Khi mở offcanvas mới cho hiện loading
        miniCartEl.addEventListener('show.bs.offcanvas', function() {
            loadMiniCart(true); 
        });
    }

    document.body.addEventListener('click', function(e) {
        let btn = e.target.closest('.btn-mini-update');
        if (!btn) return;

        let madausach = btn.getAttribute('data-id');
        let action = btn.getAttribute('data-action');

        let formData = new FormData();
        formData.append('madausach', madausach);
        formData.append('action', action);

        btn.disabled = true;

        fetch('/ajax/update_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                loadMiniCart(false); // False để nó biết là đang update, giữ nguyên cuộn chuột

                if (window.location.href.includes('page=cart')) {
                    location.reload();
                }
            } else {
                btn.disabled = false;
                Swal.fire({
                    title: 'Thông báo',
                    text: data.message, // Lấy đúng câu "Sách này trong kho chỉ còn..." từ add_to_cart.php
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7a52d0'
                });
            }
        })
        .catch(err => {
            console.error("Lỗi cập nhật giỏ:", err);
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.style.width = 'auto';
        });
    });
});