// Notification badge functionality for unread messages
document.addEventListener("DOMContentLoaded", () => {
    // Initialize unread message count
    initUnreadMessageCount()
  
    // Listen for new messages
    setupMessageNotifications()
  })
  
  /**
   * Initialize unread message count
   */
  function initUnreadMessageCount() {
    // Get unread count from server
    fetch("/messages/unread-count", {
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content,
        Accept: "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        updateUnreadBadge(data.count)
      })
      .catch((error) => {
        console.error("Error fetching unread count:", error)
      })
  }
  
  /**
   * Update the unread message badge in the header
   */
  function updateUnreadBadge(count) {
    const desktopBadge = document.getElementById("unreadBadgeDesktop")
    const mobileBadge = document.getElementById("unreadBadgeMobile")
  
    if (desktopBadge && mobileBadge) {
      if (count > 0) {
        // Update count and show badges
        desktopBadge.textContent = count > 99 ? "99+" : count
        mobileBadge.textContent = count > 99 ? "99+" : count
  
        desktopBadge.classList.remove("hidden")
        mobileBadge.classList.remove("hidden")
      } else {
        // Hide badges when no unread messages
        desktopBadge.classList.add("hidden")
        mobileBadge.classList.add("hidden")
      }
    }
  }
  
  /**
   * Setup real-time notifications for new messages
   */
  function setupMessageNotifications() {
    const userId = document.querySelector('meta[name="user-id"]')?.getAttribute("content")
  
    if (!userId || !window.Echo) return
  
    // Listen for new messages on the user's private channel
    window.Echo.private(`chat.${userId}`).listen("MessageSent", (e) => {
      // Only update badge if the message is from someone else
      if (e.message.sender_id != userId) {
        // Check if we're currently viewing this conversation
        const currentReceiverId = document.getElementById("receiverId")?.value
  
        // If we're not in the conversation with this sender, increment the badge
        if (!currentReceiverId || currentReceiverId != e.message.sender_id) {
          // Get current count and increment
          const desktopBadge = document.getElementById("unreadBadgeDesktop")
          let currentCount = Number.parseInt(desktopBadge?.textContent || "0")
  
          if (isNaN(currentCount)) currentCount = 0
          updateUnreadBadge(currentCount + 1)
        }
      }
    })
  }
  
  /**
   * Reset notification badge when viewing messages
   */
  export function resetUnreadBadge() {
    // Don't reset the badge just by visiting the messages page
    // It will be reset by markMessagesAsRead when viewing a specific conversation
  }
  
  // Make functions available globally
  window.notificationBadge = {
    update: updateUnreadBadge,
    reset: resetUnreadBadge,
  }
  