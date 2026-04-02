// ===== SIDEBAR ACTIVE =====
document.querySelectorAll(".sidebar-item").forEach((item) => {
  item.addEventListener("click", function () {
    document
      .querySelectorAll(".sidebar-item")
      .forEach((i) => i.classList.remove("active"));
    this.classList.add("active");
  });
});

// ===== API TỈNH / QUẬN / PHƯỜNG =====
const API_BASE = "https://provinces.open-api.vn/api";

async function loadTinh() {
  try {
    const res = await fetch(`${API_BASE}/?depth=1`);
    const data = await res.json();
    const select = document.getElementById("tinh");
    data.forEach((tinh) => {
      const opt = document.createElement("option");
      opt.value = tinh.code;
      opt.textContent = tinh.name;
      select.appendChild(opt);
    });
  } catch (e) {
    console.error("Không thể tải danh sách tỉnh:", e);
  }
}

async function loadQuan(tinhCode) {
  const quanSelect = document.getElementById("quan");
  const phuongSelect = document.getElementById("phuong");

  quanSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
  phuongSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
  quanSelect.disabled = true;
  phuongSelect.disabled = true;

  if (!tinhCode) return;

  try {
    const res = await fetch(`${API_BASE}/p/${tinhCode}?depth=2`);
    const data = await res.json();
    data.districts.forEach((quan) => {
      const opt = document.createElement("option");
      opt.value = quan.code;
      opt.textContent = quan.name;
      quanSelect.appendChild(opt);
    });
    quanSelect.disabled = false;
  } catch (e) {
    console.error("Không thể tải danh sách quận:", e);
  }
}

async function loadPhuong(quanCode) {
  const phuongSelect = document.getElementById("phuong");
  phuongSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
  phuongSelect.disabled = true;

  if (!quanCode) return;

  try {
    const res = await fetch(`${API_BASE}/d/${quanCode}?depth=2`);
    const data = await res.json();
    data.wards.forEach((phuong) => {
      const opt = document.createElement("option");
      opt.value = phuong.code;
      opt.textContent = phuong.name;
      phuongSelect.appendChild(opt);
    });
    phuongSelect.disabled = false;
  } catch (e) {
    console.error("Không thể tải danh sách phường:", e);
  }
}

loadTinh();

// ===== UPDATE THÔNG TIN =====
function updateInfo() {
  const btn = $("#btnSave");
  btn
    .prop("disabled", true)
    .html(
      '<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...',
    );

  const tinhText =
    document.getElementById("tinh").selectedOptions[0]?.text || "";
  const quanText =
    document.getElementById("quan").selectedOptions[0]?.text || "";
  const phuongText =
    document.getElementById("phuong").selectedOptions[0]?.text || "";
  const diachiCuThe = $("#diachi").val().trim();

  const ignore = [
    "-- Chọn tỉnh/thành --",
    "-- Chọn quận/huyện --",
    "-- Chọn phường/xã --",
    "",
  ];
  const diachiDayDu = [diachiCuThe, phuongText, quanText, tinhText]
    .filter((s) => !ignore.includes(s))
    .join(", ");

  $.ajax({
    url: "../ajax/updateProfile.php",
    type: "POST",
    dataType: "json",
    data: {
      hodocgia: $("#hodocgia").val(),
      tendocgia: $("#tendocgia").val(),
      sdt: $("#sdt_input").val(),
      ngaysinh: $("#ngaysinh").val(),
      diachi: diachiDayDu || diachiCuThe,
    },
    success: function (data) {
      if (data.success) {
        showModal(true, data.message);
        $("#fullname").text(data.user.hodocgia + " " + data.user.tendocgia);
        $("#sdt").text(data.user.sdt || "Chưa có thông tin");
        $("#hodocgia").val(data.user.hodocgia);
        $("#tendocgia").val(data.user.tendocgia);
        $("#sdt_input").val(data.user.sdt);
        $("#ngaysinh").val(data.user.ngaysinh);
        $("#diachi").val(data.user.diachi);
        $("#headerName").html("Xin chào, " + data.user.tendocgia);
        setTimeout(() => {
          bootstrap.Collapse.getOrCreateInstance(
            document.getElementById("updateBox"),
          ).hide();
        }, 2000);
      } else {
        showModal(false, data.message);
      }
    },
    error: function () {
      showModal(false, "Không thể kết nối server");
    },
    complete: function () {
      btn.prop("disabled", false).html("Lưu");
    },
  });
}

// ===== TOGGLE HIỆN/ẨN MẬT KHẨU TRONG FORM =====
function toggleInput(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(iconId);
  if (input.type === "password") {
    input.type = "text";
    icon.className = "bi bi-eye-slash";
  } else {
    input.type = "password";
    icon.className = "bi bi-eye";
  }
}

// ===== ĐỔI MẬT KHẨU =====
function changePassword() {
  const currentPwd = $("#currentPassword").val().trim();
  const newPwd = $("#newPassword").val().trim();
  const confirmPwd = $("#confirmPassword").val().trim();

  if (!currentPwd || !newPwd || !confirmPwd) {
    showModal(false, "Vui lòng điền đầy đủ thông tin");
    return;
  }
  if (newPwd !== confirmPwd) {
    showModal(false, "Mật khẩu mới không khớp");
    return;
  }
  if (newPwd.length < 6) {
    showModal(false, "Mật khẩu mới phải có ít nhất 6 ký tự");
    return;
  }

  const btn = $("#btnSavePassword");
  btn
    .prop("disabled", true)
    .html(
      '<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...',
    );

  $.ajax({
    url: "../ajax/changePass.php",
    type: "POST",
    dataType: "json",
    data: {
      action: "change",
      current_password: currentPwd,
      new_password: newPwd,
    },
    success: function (data) {
      if (data.success) {
        showModal(true, data.message);
        $("#currentPassword, #newPassword, #confirmPassword").val("");
        setTimeout(() => {
          bootstrap.Collapse.getOrCreateInstance(
            document.getElementById("changePasswordBox"),
          ).hide();
        }, 2000);
      } else {
        showModal(false, data.message);
      }
    },
    error: function () {
      showModal(false, "Không thể kết nối server");
    },
    complete: function () {
      btn.prop("disabled", false).html("Lưu mật khẩu");
    },
  });
}

// ===== MODAL NOTICE =====
function showModal(isSuccess, message) {
  const modalContent = document.getElementById("modalContent");
  const loginMessage = document.getElementById("loginMessage");

  modalContent.className =
    "modal-content border-0 shadow-lg " +
    (isSuccess ? "modal-success" : "modal-error");
  loginMessage.innerHTML = `<h4>${message}</h4>`;

  const modalInstance = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("loginModal"),
    {
      backdrop: false,
    },
  );
  modalInstance.show();
  setTimeout(() => modalInstance.hide(), 2000);
}
