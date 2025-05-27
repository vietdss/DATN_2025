// Transaction notification functionality for unread requests
document.addEventListener("DOMContentLoaded", () => {
    // Initialize unread transaction count
    initUnreadTransactionCount()
  
    // Listen for new transaction requests
    setupTransactionNotifications()
  })
  
  /**
   * Initialize unread transaction count
   */
  function initUnreadTransactionCount() {
    // Get unread count from server
    fetch("/transactions/unread-count", {
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content,
        Accept: "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        updateUnreadTransactionBadge(data.count)
      })
      .catch((error) => {
        console.error("Error fetching unread transaction count:", error)
      })
  }
  
  /**
   * Update the unread transaction badge in the header
   */
  function updateUnreadTransactionBadge(count) {
    const desktopBadge = document.getElementById("unreadTransactionBadgeDesktop")
    const mobileBadge = document.getElementById("unreadTransactionBadgeMobile")
  
    if (desktopBadge && mobileBadge) {
      if (count > 0) {
        // Update count and show badges
        desktopBadge.textContent = count > 99 ? "99+" : count
        mobileBadge.textContent = count > 99 ? "99+" : count
  
        desktopBadge.classList.remove("hidden")
        mobileBadge.classList.remove("hidden")
      } else {
        // Hide badges when no unread requests
        desktopBadge.classList.add("hidden")
        mobileBadge.classList.add("hidden")
      }
    }
  }
  
  /**
   * Setup real-time notifications for new transaction requests
   */
  function setupTransactionNotifications() {
    const userId = document.querySelector('meta[name="user-id"]')?.getAttribute("content")
  
    if (!userId || !window.Echo) return
  
    // Listen for new transaction requests on the user's private channel
    window.Echo.private(`transactions.${userId}`).listen("TransactionRequestSent", (e) => {
      // Only update badge if the request is for the current user (as giver)
      if (e.transaction.giver_id == userId) {
        // Get current count and increment
        const desktopBadge = document.getElementById("unreadTransactionBadgeDesktop")
        let currentCount = Number.parseInt(desktopBadge?.textContent || "0")
  
        if (isNaN(currentCount)) currentCount = 0
        updateUnreadTransactionBadge(currentCount + 1)
  
        // Show browser notification if enabled
        showTransactionNotification(e.transaction)
      }
    })
  
    // Listen for transaction status updates
    window.Echo.private(`transactions.${userId}`).listen("TransactionStatusUpdated", (e) => {
      // Show notification for status updates
      showTransactionStatusNotification(e.transaction)
    })
  }
  
  /**
   * Show browser notification for new transaction request
   */
  function showTransactionNotification(transaction) {
    if ("Notification" in window && Notification.permission === "granted") {
      new Notification(`Yêu cầu mới từ ${transaction.receiver.name}`, {
        body: `Yêu cầu ${transaction.quantity} ${transaction.post.title}`,
        icon: transaction.receiver.profile_image || "/images/logo.png",
      })
    }
  }
  
  /**
   * Show browser notification for transaction status update
   */
  function showTransactionStatusNotification(transaction) {
    if ("Notification" in window && Notification.permission === "granted") {
      let statusText = ""
      switch (transaction.status) {
        case "accepted":
          statusText = "đã được chấp nhận"
          break
        case "rejected":
          statusText = "đã bị từ chối"
          break
        case "completed":
          statusText = "đã hoàn thành"
          break
        default:
          return
      }
  
      new Notification(`Yêu cầu ${statusText}`, {
        body: `Yêu cầu ${transaction.post.title} ${statusText}`,
        icon: transaction.giver.profile_image || "/images/logo.png",
      })
    }
  }
  
  /**
   * Mark transaction requests as read when viewing transactions page
   */
  export function markTransactionRequestsAsRead() {
    fetch("/transactions/mark-as-read", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        // Reset the notification badge
        updateUnreadTransactionBadge(0)
      })
      .catch((error) => console.error("Error marking transaction requests as read:", error))
  }
  
  // Make functions available globally
  window.transactionNotification = {
    update: updateUnreadTransactionBadge,
    markAsRead: markTransactionRequestsAsRead,
  }
  