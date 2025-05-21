// Item request functionality
let selectedItemId = null

window.openQuantityModal = function(itemId) {
  selectedItemId = itemId
  document.getElementById("quantity-input").value = 1
  document.getElementById("quantity-modal").classList.remove("hidden")
}

window.closeQuantityModal = function() {
  document.getElementById("quantity-modal").classList.add("hidden")
}

window.confirmSendRequest = function() {
  const quantity = parseInt(document.getElementById("quantity-input").value)
  if (!quantity || quantity < 1) {
    showNotification("Vui lòng nhập số lượng hợp lệ.", "error")
    return
  }

  closeQuantityModal()
  sendRequest(selectedItemId, quantity)
}

export async function sendRequest(itemId, quantity) {
  try {
    const response = await fetch(`/item/request/${itemId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
      },
      body: JSON.stringify({ quantity }),
    })

    const data = await response.json()

    if (response.ok) {
      showNotification(data.message, "success")
      changeToCancelButton(itemId)
      location.reload(); 
    } else {
      showNotification(data.message || "Đã xảy ra lỗi.", "error")
      if (response.status == 404) {
        location.reload()
      }
    }
  } catch (e) {
    console.error(e)
    showNotification("Lỗi kết nối.", "error")
  }
}

  
  export async function cancelRequest(itemId) {
    try {
      const response = await fetch(`/item/request/${itemId}`, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
      })
  
      const data = await response.json()
  
      if (response.ok) {
        showNotification(data.message, "success")
        changeToRequestButton(itemId)
        location.reload();

      } else {
        showNotification(data.message || "Không thể hủy yêu cầu.", "error")
      }
    } catch (e) {
      console.error(e)
      showNotification("Lỗi kết nối.", "error")
    }
  }
  
  function changeToCancelButton(itemId) {
    const container = document.getElementById("request-action")
    if (!container) return
  
    container.innerHTML = `
      <button onclick="cancelRequest(${itemId})"
        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md w-full flex justify-center items-center">
        <i class="fas fa-times mr-2"></i> Hủy yêu cầu
      </button>
    `
  }
  
  function changeToRequestButton(itemId) {
    const container = document.getElementById("request-action")
    if (!container) return
  
    container.innerHTML = `
      <button onclick="sendRequest(${itemId})"
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md w-full flex justify-center items-center">
        <i class="fas fa-phone-alt mr-2"></i> Yêu cầu
      </button>
    `
  }
  
  function showNotification(message, type = "info") {
    let container = document.getElementById("notification-container")
    if (!container) {
      container = document.createElement("div")
      container.id = "notification-container"
      container.className = "fixed bottom-4 right-4 z-50 flex flex-col space-y-2"
      document.body.appendChild(container)
    }
  
    const notification = document.createElement("div")
  
    let bgColor, iconClass
    switch (type) {
      case "success":
        bgColor = "bg-green-100 border-l-4 border-green-500 text-green-700"
        iconClass = "fas fa-check-circle text-green-500"
        break
      case "error":
        bgColor = "bg-red-100 border-l-4 border-red-500 text-red-700"
        iconClass = "fas fa-times-circle text-red-500"
        break
      default:
        bgColor = "bg-blue-100 border-l-4 border-blue-500 text-blue-700"
        iconClass = "fas fa-info-circle text-blue-500"
    }
  
    notification.className = `${bgColor} p-4 rounded shadow-md flex items-center transform transition-all duration-300 translate-x-full`
    notification.innerHTML = `
      <i class="${iconClass} mr-3 text-lg"></i>
      <span>${message}</span>
      <button class="ml-auto text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    `
  
    container.appendChild(notification)
  
    setTimeout(() => {
      notification.classList.remove("translate-x-full")
    }, 10)
  
    setTimeout(() => {
      notification.classList.add("translate-x-full")
      setTimeout(() => {
        notification.remove()
      }, 300)
    }, 5000)
  }
  
  // Make functions available globally
  window.sendRequest = sendRequest
  window.cancelRequest = cancelRequest
  window.showNotification = showNotification
  