// Utility functions
export const utils = {
    // Format time since a given date
    timeSince: (createdAt) => {
      const createdDate = new Date(createdAt)
      const now = new Date()
      const seconds = Math.floor((now - createdDate) / 1000)
  
      const intervals = {
        năm: 31536000,
        tháng: 2592000,
        tuần: 604800,
        ngày: 86400,
        giờ: 3600,
        phút: 60,
        giây: 1,
      }
  
      for (const [unit, value] of Object.entries(intervals)) {
        const count = Math.floor(seconds / value)
        if (count >= 1) {
          return `${count} ${unit} trước`
        }
      }
      return "Vừa xong"
    },
  
    // Show toast notification
    showToast: (message, type = "success") => {
      const container =
        document.getElementById("toast-container") ||
        (() => {
          const el = document.createElement("div")
          el.id = "toast-container"
          el.className = "fixed top-4 right-4 z-50"
          document.body.appendChild(el)
          return el
        })()
  
      // Create toast element
      const toast = document.createElement("div")
      toast.className = `flex items-center p-4 mb-3 max-w-md rounded-lg shadow-lg transition-all transform translate-x-0 opacity-100 ${
        type === "success"
          ? "bg-green-50 text-green-800 border-l-4 border-green-500"
          : type === "error"
            ? "bg-red-50 text-red-800 border-l-4 border-red-500"
            : type === "warning"
              ? "bg-yellow-50 text-yellow-800 border-l-4 border-yellow-500"
              : "bg-blue-50 text-blue-800 border-l-4 border-blue-500"
      }`
  
      // Set icon based on type
      const iconClass =
        type === "success"
          ? "check-circle"
          : type === "error"
            ? "times-circle"
            : type === "warning"
              ? "exclamation-triangle"
              : "info-circle"
  
      toast.innerHTML = `
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 mr-3 ${
          type === "success"
            ? "text-green-500"
            : type === "error"
              ? "text-red-500"
              : type === "warning"
                ? "text-yellow-500"
                : "text-blue-500"
        }">
          <i class="fas fa-${iconClass}"></i>
        </div>
        <div class="ml-3 text-sm font-normal">${message}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 text-gray-500 hover:text-gray-700 focus:outline-none">
          <i class="fas fa-times"></i>
        </button>
      `
  
      // Add to container
      container.appendChild(toast)
  
      // Remove after 5 seconds
      setTimeout(() => {
        toast.classList.add("opacity-0")
        setTimeout(() => {
          toast.remove()
        }, 300)
      }, 5000)
  
      // Close button functionality
      toast.querySelector("button").addEventListener("click", () => {
        toast.classList.add("opacity-0")
        setTimeout(() => {
          toast.remove()
        }, 300)
      })
    },
  }
  
  // Make utils available globally
  window.utils = utils
  