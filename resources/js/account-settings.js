document.addEventListener("DOMContentLoaded", () => {
  // Các phần tử modal
  const successModal = document.getElementById("successModal")
  const successModalTitle = document.getElementById("successModalTitle")
  const successModalMessage = document.getElementById("successModalMessage")
  const successModalClose = document.getElementById("successModalClose")

  const confirmModal = document.getElementById("confirmModal")
  const confirmModalCancel = document.getElementById("confirmModalCancel")
  const confirmModalConfirm = document.getElementById("confirmModalConfirm")

  // Kiểm tra nếu có thông báo thành công từ server
  const successMessage = document.querySelector(".bg-green-100")
  if (successMessage) {
    // Lấy nội dung thông báo
    const message = successMessage.querySelector("span").textContent

    // Hiển thị modal thành công thay vì thông báo inline
    if (message.includes("Mật khẩu đã được cập nhật")) {
      showSuccessModal("Đổi mật khẩu thành công", "Mật khẩu của bạn đã được cập nhật thành công!")
      // Ẩn thông báo inline
      successMessage.style.display = "none"
    }
  }

  // Hàm hiển thị modal thành công
  function showSuccessModal(title, message) {
    successModalTitle.textContent = title
    successModalMessage.textContent = message
    successModal.classList.remove("hidden")
  }

  // Đóng modal thành công khi nhấn nút đóng
  if (successModalClose) {
    successModalClose.addEventListener("click", () => {
      successModal.classList.add("hidden")
    })
  }

  // Đóng modal khi click bên ngoài
  window.addEventListener("click", (event) => {
    if (event.target === successModal) {
      successModal.classList.add("hidden")
    }
    if (event.target === confirmModal) {
      confirmModal.classList.add("hidden")
    }
  })

  // Kiểm tra nếu có lỗi liên quan đến mật khẩu, hiển thị tab bảo mật
  const hasPasswordErrors =
    document.querySelector(".bg-red-100") &&
    (document.querySelector('[name="current_password"]') || document.querySelector('[name="new_password"]'))

  if (hasPasswordErrors) {
    // Hiển thị tab bảo mật
    const securityTab = document.getElementById("securityTab")
    const profileTab = document.getElementById("profileTab")
    const securitySettings = document.getElementById("securitySettings")
    const profileSettings = document.getElementById("profileSettings")

    if (securityTab && profileTab && securitySettings && profileSettings) {
      // Cập nhật trạng thái tab
      profileTab.classList.remove("border-b-2", "border-green-600", "text-green-600")
      securityTab.classList.add("border-b-2", "border-green-600", "text-green-600")

      // Hiển thị/ẩn nội dung
      profileSettings.classList.add("hidden")
      securitySettings.classList.remove("hidden")
    }
  }

  // Tab switching
  const profileTab = document.getElementById("profileTab")
  const securityTab = document.getElementById("securityTab")
  const profileSettings = document.getElementById("profileSettings")
  const securitySettings = document.getElementById("securitySettings")

  // Profile tab
  if (profileTab) {
    profileTab.addEventListener("click", () => {
      // Update active tab
      profileTab.classList.add("border-b-2", "border-green-600", "text-green-600")
      securityTab.classList.remove("border-b-2", "border-green-600", "text-green-600")

      // Show/hide content
      profileSettings.classList.remove("hidden")
      securitySettings.classList.add("hidden")
    })
  }

  // Security tab
  if (securityTab) {
    securityTab.addEventListener("click", () => {
      // Update active tab
      profileTab.classList.remove("border-b-2", "border-green-600", "text-green-600")
      securityTab.classList.add("border-b-2", "border-green-600", "text-green-600")

      // Show/hide content
      profileSettings.classList.add("hidden")
      securitySettings.classList.remove("hidden")
    })
  }

  // Delete account confirmation
  const deleteAccountBtn = document.getElementById("deleteAccountBtn")
  if (deleteAccountBtn) {
    deleteAccountBtn.addEventListener("click", () => {
      // Hiển thị modal xác nhận thay vì confirm mặc định
      confirmModal.classList.remove("hidden")

      // Lưu action URL để sử dụng khi xác nhận
      confirmModalConfirm.dataset.action = deleteAccountBtn.dataset.action
    })
  }

  // Xử lý khi nhấn nút hủy trong modal xác nhận
  if (confirmModalCancel) {
    confirmModalCancel.addEventListener("click", () => {
      confirmModal.classList.add("hidden")
    })
  }

  // Xử lý khi nhấn nút xác nhận trong modal xác nhận
  if (confirmModalConfirm) {
    confirmModalConfirm.addEventListener("click", () => {
      // Tạo form để submit
      const form = document.createElement("form")
      form.method = "POST"
      form.action = confirmModalConfirm.dataset.action

      const csrfToken = document.createElement("input")
      csrfToken.type = "hidden"
      csrfToken.name = "_token"
      csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

      const method = document.createElement("input")
      method.type = "hidden"
      method.name = "_method"
      method.value = "DELETE"

      form.appendChild(csrfToken)
      form.appendChild(method)
      document.body.appendChild(form)
      form.submit()
    })
  }

  // Xử lý xem trước ảnh đại diện
  const profileImageInput = document.getElementById("profile_image")
  const profileImage = document.querySelector("img[alt]")
  const removeProfileImageBtn = document.getElementById("removeProfileImage")

  if (profileImageInput && profileImage) {
    profileImageInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader()

        reader.onload = (e) => {
          profileImage.src = e.target.result
        }

        reader.readAsDataURL(this.files[0])
      }
    })
  }

  // Xử lý xóa ảnh đại diện
  if (removeProfileImageBtn) {
    removeProfileImageBtn.addEventListener("click", () => {
      if (confirm("Bạn có chắc chắn muốn xóa ảnh đại diện?")) {
        // Tạo một form ẩn để gửi request xóa ảnh
        const form = document.createElement("form")
        form.method = "POST"
        form.action = removeProfileImageBtn.dataset.action

        const csrfToken = document.createElement("input")
        csrfToken.type = "hidden"
        csrfToken.name = "_token"
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

        const method = document.createElement("input")
        method.type = "hidden"
        method.name = "_method"
        method.value = "DELETE"

        form.appendChild(csrfToken)
        form.appendChild(method)
        document.body.appendChild(form)
        form.submit()
      }
    })
  }

  // Xử lý form đổi mật khẩu
  const securityForm = document.getElementById("securityForm")
  if (securityForm) {
    securityForm.addEventListener("submit", function (event) {
      // Ngăn form submit mặc định
      event.preventDefault()

      // Lấy dữ liệu form
      const formData = new FormData(this)

      // Gửi request AJAX
      fetch(this.action, {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Hiển thị modal thành công
            showSuccessModal("Đổi mật khẩu thành công", data.message || "Mật khẩu của bạn đã được cập nhật thành công!")

            // Reset form
            securityForm.reset()

            // Xóa thông báo lỗi nếu có
            const errorDiv = document.querySelector(".bg-red-100")
            if (errorDiv) {
              errorDiv.style.display = "none"
            }
          } else {
            // Hiển thị lỗi
            let errorMessage = "Đã xảy ra lỗi khi cập nhật mật khẩu."
            if (data.errors) {
              errorMessage = Object.values(data.errors).flat().join("<br>")
            }

            // Tạo hoặc cập nhật div lỗi
            let errorDiv = document.querySelector(".bg-red-100")
            if (!errorDiv) {
              errorDiv = document.createElement("div")
              errorDiv.className = "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
              errorDiv.setAttribute("role", "alert")
              securityForm.parentNode.insertBefore(errorDiv, securityForm)
            }

            errorDiv.innerHTML = `<ul class="list-disc pl-5"><li>${errorMessage}</li></ul>`
            errorDiv.style.display = "block"
          }
        })
        .catch((error) => {
          console.error("Error:", error)
        })
    })
  }
})
