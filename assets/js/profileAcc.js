// ===== SIDEBAR ACTIVE =====
document.querySelectorAll(".sidebar-item").forEach((item) => {
  item.addEventListener("click", function (e) {
    e.preventDefault();

    document
      .querySelectorAll(".sidebar-item")
      .forEach((i) => i.classList.remove("active"));

    this.classList.add("active");

    document
      .querySelectorAll(".info-section")
      .forEach((sec) => sec.classList.add("d-none"));

    const target = this.dataset.target;

    document.getElementById(target).classList.remove("d-none");
  });
});
// ===== API TỈNH / QUẬN / PHƯỜNG =====
const API_BASE = "https://esgoo.net/api-tinhthanh";

async function loadTinh() {
  try {
    const res = await fetch(`${API_BASE}/1/0.htm`);
    const result = await res.json();
    const select = document.getElementById("tinh");
    if (result.error === 0) {
      result.data.forEach((tinh) => {
        const opt = document.createElement("option");
        opt.value = tinh.id;
        opt.textContent = tinh.full_name;
        select.appendChild(opt);
      });
    }
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

  if (!tinhCode || tinhCode === "-- Chọn tỉnh/thành --") return;

  try {
    const res = await fetch(`${API_BASE}/2/${tinhCode}.htm`);
    const result = await res.json();
    if (result.error === 0) {
      result.data.forEach((quan) => {
        const opt = document.createElement("option");
        opt.value = quan.id;
        opt.textContent = quan.full_name;
        quanSelect.appendChild(opt);
      });
      quanSelect.disabled = false;
    }
  } catch (e) {
    console.error("Không thể tải danh sách quận:", e);
  }
}

async function loadPhuong(quanCode) {
  const phuongSelect = document.getElementById("phuong");
  phuongSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
  phuongSelect.disabled = true;

  if (!quanCode || quanCode === "-- Chọn quận/huyện --") return;

  try {
    const res = await fetch(`${API_BASE}/3/${quanCode}.htm`);
    const result = await res.json();
    if (result.error === 0) {
      result.data.forEach((phuong) => {
        const opt = document.createElement("option");
        opt.value = phuong.id;
        opt.textContent = phuong.full_name;
        phuongSelect.appendChild(opt);
      });
      phuongSelect.disabled = false;
    }
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

  // ===== VALIDATE SỐ ĐIỆN THOẠI =====
  const sdtVal = $("#sdt_input").val().trim();
  const sdtRegex = /^0\d{9}$/;
  if (sdtVal && !sdtRegex.test(sdtVal)) {
    showModal(false, "Không tìm thấy số điện thoại");
    btn.prop("disabled", false).html("Lưu");
    return;
  }

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
      diachi: diachiDayDu || diachiCuThe,
    },
    success: function (data) {
      if (data.success) {
        showModal(true, data.message);

        // Cập nhật hiển thị
        $("#fullname").text(data.user.hodocgia + " " + data.user.tendocgia);
        $("#sdt").text(data.user.sdt || "Chưa có thông tin");
        $("#diachi_show").text(data.user.diachi);

        // Cập nhật lại form inputs
        $("#hodocgia").val(data.user.hodocgia);
        $("#tendocgia").val(data.user.tendocgia);
        $("#sdt_input").val(data.user.sdt);
        $("#diachi").val(data.user.diachi);

        //Cập nhật lại data trên button để pre-fill đúng lần sau
        const btn = document.getElementById("btnOpenUpdate");
        btn.dataset.ho = data.user.hodocgia;
        btn.dataset.ten = data.user.tendocgia;
        btn.dataset.sdt = data.user.sdt;
        btn.dataset.diachi = data.user.diachi;

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
// ===== PRE-FILL FORM KHI MỞ COLLAPSE =====
document.getElementById("btnOpenUpdate")?.addEventListener("click", function () {
  $("#hodocgia").val(this.dataset.ho);
  $("#tendocgia").val(this.dataset.ten);
  $("#sdt_input").val(this.dataset.sdt);
  $("#diachi").val(this.dataset.diachi);
});
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

// ===== XEM CHI TIẾT PHIẾU =====
function viewTicket(type, id) {
  $.ajax({
    url: "/ajax/get_ticket_detail.php",
    type: "GET",
    dataType: "json",
    data: { type: type, id: id },
    success: function (res) {
      if (res.success) {
        // Cập nhật tiêu đề
        let titleName = type === 'muon' ? 'Phiếu mượn' : (type === 'tra' ? 'Phiếu trả' : 'Phiếu phạt');
        document.querySelector("#ticketDetailModal .modal-title").innerHTML = `Chi tiết ${titleName}: <span id="td_mamuon" class="text-primary fw-bold">#${id}</span>`;
        
        const thead = document.getElementById("ticketDetailHead");
        const tbody = document.getElementById("ticketDetailBody");
        thead.innerHTML = "";
        tbody.innerHTML = "";
        
        let headerHtml = "<tr><th>Mã cuốn</th><th>Tên sách</th>";
        if (type === 'muon') {
            headerHtml += "<th>Tình trạng giao</th></tr>";
        } else if (type === 'tra') {
            headerHtml += "<th>Tình trạng trả</th><th>Trễ hạn</th><th>Phạt trễ</th><th>Phạt hỏng</th></tr>";
        } else if (type === 'phat') {
            headerHtml += "<th>Lý do phạt</th><th>Trễ hạn</th><th>Tiền phạt</th></tr>";
        }
        thead.innerHTML = headerHtml;
        
        const formatCurrency = (val) => new Intl.NumberFormat('vi-VN').format(val) + " đ";

        if (res.data && res.data.length > 0) {
          res.data.forEach(item => {
            const tr = document.createElement("tr");
            let tdHtml = `<td class="fw-semibold">${item.macuonsach}</td><td>${item.tensach}</td>`;
            
            if (type === 'muon') {
              tdHtml += `<td>${item.tinhtrang_truoc || "Bình thường"}</td>`;
            } else if (type === 'tra') {
              tdHtml += `<td>${item.tinhtrang_sau || "Không rõ"}</td>
                         <td class="text-danger">${item.songayquahan > 0 ? item.songayquahan + " ngày" : "Không"}</td>
                         <td class="text-danger">${formatCurrency(item.tienphatquahan || 0)}</td>
                         <td class="text-danger">${formatCurrency(item.tienphathuha || 0)}</td>`;
            } else if (type === 'phat') {
              tdHtml += `<td>${item.lydo || "Không rõ"}</td>
                         <td class="text-danger">${item.songayquahan > 0 ? item.songayquahan + " ngày" : "Không"}</td>
                         <td class="text-danger fw-bold">${formatCurrency(item.sotienphat || 0)}</td>`;
            }
            tr.innerHTML = tdHtml;
            tbody.appendChild(tr);
          });
        } else {
          tbody.innerHTML = `<tr><td colspan="10" class="text-center text-muted">Không có dữ liệu chi tiết</td></tr>`;
        }
        
        bootstrap.Modal.getOrCreateInstance(document.getElementById("ticketDetailModal")).show();
      } else {
        showModal(false, res.message || "Lỗi khi lấy dữ liệu.");
      }
    },
    error: function () {
      showModal(false, "Không thể kết nối tới server.");
    }
  });
}