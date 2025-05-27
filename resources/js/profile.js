document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll('#itemsGrid .item-card');
  document.getElementById('noItemsMessage').style.display = items.length === 0 ? '' : 'none';
    const deleteButtons = document.querySelectorAll(".delete-item-btn");
    const modal = document.getElementById("deleteConfirmModal");
    
    if (!modal) return;
    
    const modalBox = modal.querySelector(".p-6");
    const closeModalBtn = document.getElementById("closeDeleteModal");
    const cancelDeleteBtn = document.getElementById("cancelDelete");
    const confirmDeleteBtn = document.getElementById("confirmDelete");
  
    let selectedForm = null;
  
    // Mở modal xác nhận
    deleteButtons.forEach(button => {
      button.addEventListener("click", function () {
        selectedForm = this.closest("form");
        modal.classList.remove("hidden");
        setTimeout(() => {
          modalBox.classList.remove("scale-95", "opacity-0");
          modalBox.classList.add("scale-100", "opacity-100");
        }, 10);
      });
    });
  
    function closeModal() {
      modalBox.classList.remove("scale-100", "opacity-100");
      modalBox.classList.add("scale-95", "opacity-0");
      setTimeout(() => {
        modal.classList.add("hidden");
      }, 150);
    }
  
    if (closeModalBtn) {
      closeModalBtn.addEventListener("click", closeModal);
    }
    
    if (cancelDeleteBtn) {
      cancelDeleteBtn.addEventListener("click", closeModal);
    }
  
    // Xác nhận xóa bằng fetch
    if (confirmDeleteBtn) {
      confirmDeleteBtn.addEventListener("click", function () {
        if (selectedForm) {
          const formData = new FormData(selectedForm);
          const action = selectedForm.getAttribute("action");
  
          fetch(action, {
            method: "POST",
            body: formData,
            headers: {
              "X-Requested-With": "XMLHttpRequest",
              "X-CSRF-TOKEN": formData.get("_token")
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error("Delete failed");
            }
            return response.json().catch(() => ({}));
          })
          .then(() => {
            closeModal();
            showNotification('Xóa thành công', 'success');
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          })
          .catch(() => {
            showNotification('Xóa không thành công. Vui lòng thử lại sau.', 'error');
            closeModal();
          });
        }
      });
    }
  });
  
  // Hiển thị thông báo
  function showNotification(message, type = 'info') {
    let container = document.getElementById('notification-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'notification-container';
      container.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
      document.body.appendChild(container);
    }
  
    const notification = document.createElement('div');
  
    let bgColor, iconClass;
    switch (type) {
      case 'success':
        bgColor = 'bg-green-100 border-l-4 border-green-500 text-green-700';
        iconClass = 'fas fa-check-circle text-green-500';
        break;
      case 'error':
        bgColor = 'bg-red-100 border-l-4 border-red-500 text-red-700';
        iconClass = 'fas fa-times-circle text-red-500';
        break;
      default:
        bgColor = 'bg-blue-100 border-l-4 border-blue-500 text-blue-700';
        iconClass = 'fas fa-info-circle text-blue-500';
    }
  
    notification.className = `${bgColor} p-4 rounded shadow-md flex items-center transform transition-all duration-300 translate-x-full`;
    notification.innerHTML = `
      <i class="${iconClass} mr-3 text-lg"></i>
      <span>${message}</span>
      <button class="ml-auto text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    `;
  
    container.appendChild(notification);
  
    setTimeout(() => {
      notification.classList.remove('translate-x-full');
    }, 10);
  
    setTimeout(() => {
      notification.classList.add('translate-x-full');
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 5000);
  }
  