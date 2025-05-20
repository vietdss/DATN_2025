/**
 * online-status.js
 *
 * This module handles updating user online status.
 */

/**
 * Initializes online status updates.
 */
export function initOnlineStatusUpdates() {
    if (!window.Echo) {
      console.error("Echo is not defined. Please ensure Laravel Echo is properly initialized.")
      return
    }
  
    // Join the presence channel to track online users
    window.Echo.join("presence.chat")
      .here((users) => {
        updateOnlineStatus(users)
      })
      .joining((user) => {
        updateUserStatus(user.id, true)
      })
      .leaving((user) => {
        updateUserStatus(user.id, false)
      })
  
    // Also send periodic updates to the server
    updateOnlineStatusOnServer() // Initial update
    setInterval(updateOnlineStatusOnServer, 60000) // Update every minute
  }
  
  /**
   * Updates the online status of multiple users.
   */
  function updateOnlineStatus(users) {
    if (!users || !users.length) return
  
    // Get all user IDs from the presence channel
    const onlineUserIds = users.map((user) => user.id)
  
    // Update status indicators for all conversation items
    const conversationItems = document.querySelectorAll(".conversation-item")
    conversationItems.forEach((item) => {
      const userId = item.dataset.userId
      const isOnline = onlineUserIds.includes(Number.parseInt(userId))
      updateStatusIndicator(item, isOnline)
    })
  
    // Update status in chat header if applicable
    const receiverId = document.getElementById("receiverId")?.value
    if (receiverId) {
      const isReceiverOnline = onlineUserIds.includes(Number.parseInt(receiverId))
      updateChatHeaderStatus(isReceiverOnline)
    }
  }
  
  /**
   * Updates the status for a single user.
   */
  function updateUserStatus(userId, isOnline) {
    // Update in conversation list
    const conversationItem = document.querySelector(`.conversation-item[data-user-id="${userId}"]`)
    if (conversationItem) {
      updateStatusIndicator(conversationItem, isOnline)
    }
  
    // Update in chat header if this is the current receiver
    const receiverId = document.getElementById("receiverId")?.value
    if (receiverId && Number.parseInt(receiverId) === Number.parseInt(userId)) {
      updateChatHeaderStatus(isOnline)
    }
  }
  
  /**
   * Updates the status indicator in a conversation item.
   */
  function updateStatusIndicator(conversationItem, isOnline) {
    const statusIndicator = conversationItem.querySelector(".relative span")
    if (statusIndicator) {
      if (isOnline) {
        statusIndicator.classList.remove("bg-gray-300")
        statusIndicator.classList.add("bg-green-500")
      } else {
        statusIndicator.classList.remove("bg-green-500")
        statusIndicator.classList.add("bg-gray-300")
      }
    }
  }
  
  /**
   * Updates the status in the chat header.
   */
  function updateChatHeaderStatus(isOnline) {
    const statusText = document.querySelector(".chat-header-status")
    if (statusText) {
      statusText.textContent = isOnline ? "Đang hoạt động" : "Không hoạt động"
    }
  }
  
  /**
   * Sends an update to the server to mark the user as online.
   */
  function updateOnlineStatusOnServer() {
    fetch("/user/update-activity", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    }).catch((error) => console.error("Error updating online status:", error))
  }