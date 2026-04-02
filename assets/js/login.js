const wrapper = document.querySelector('.wrapper');
const containerLogin = document.querySelector('.container-fluid.center-full'); 
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnOpen = document.getElementById('openLogin');
const btnX=document.querySelector('.close');

//switch between login and register form
registerLink?.addEventListener('click', (e)=>{
    e.preventDefault();
    wrapper.classList.add('active');
});
//switch between login and register form
loginLink?.addEventListener('click', (e)=>{
    e.preventDefault();
    wrapper.classList.remove('active');
}); 
//open form login
btnOpen?.addEventListener('click',()=>{
    wrapper.classList.add('active-popup')
    containerLogin.classList.add('active-popup');
});
//close form login
btnX?.addEventListener('click',()=>{
    wrapper.classList.remove('active-popup');
    containerLogin.classList.remove('active-popup');
});

// ===== PHẦN LOGIN AJAX =====
const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm?.addEventListener('submit', (e) => {
        e.preventDefault();
        
        fetch('./ajax/loginAjax.php', {
            method: 'POST',
            body: new FormData(loginForm)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                showModal(true, data.message);
                // Chuyển trang sau 2 giây
                setTimeout(() => {
                    window.location.replace("/index.php");
                }, 2000);
            } else {
                showModal(false, data.message);
            }
        })
        .catch(() => {
            showModal(false, "Lỗi kết nối, vui lòng thử lại!");
        });
    });
}

// ===== PHẦN Register AJAX =====
const registerForm = document.getElementById('registerForm');

if (registerForm) {
    registerForm?.addEventListener('submit', (e) => {
        e.preventDefault();

        const email = registerForm.querySelector('input[name="email"]').value.trim();
        if (!isValidEmail(email)) {
            showModal(false, 'Email không đúng định dạng!');
            return; // dừng lại, không fetch
        }

        fetch('./ajax/registerAjax.php', {
            method: 'POST',
            body: new FormData(registerForm)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Hiện thông báo thành công
                showModal('success', 'Đăng ký thành công! Mã của bạn: ' + data.madocgia);
                
                // Chuyển sang form đăng nhập sau 2 giây
                setTimeout(() => {
                    wrapper.classList.remove('active');
                }, 2000);
            } else {
                showModal('error', data.message);
            }
        })
        .catch(err => console.error('Lỗi:', err));
    });
}

// ===== MODAL NOTICE =====
function showModal(isSuccess, message) {
    const modalContent = document.getElementById("modalContent");
    const loginMessage = document.getElementById("loginMessage");

    if(isSuccess){
        modalContent.className = "modal-content border-0 shadow-lg modal-success";
        loginMessage.innerHTML = `<h4>${message}</h4>`;
    } else {
        modalContent.className = "modal-content border-0 shadow-lg modal-error";
        loginMessage.innerHTML = `<h4>${message}</h4>`;
    }
    
    const modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal'), {
        backdrop: false
    });
    modalInstance.show();

    // Tự động đóng sau X giây
    setTimeout(() => {
        modalInstance.hide();
    }, 2000);
}

//===== HELPER =====
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}