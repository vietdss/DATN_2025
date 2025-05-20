// Chat functionality module
import { initOnlineStatusUpdates } from "./online-status"
import { resetUnreadBadge } from "./notification-badge"

/**
 * Initialize chat functionality
 */
export function initChat() {
  const userId = document.querySelector('meta[name="user-id"]')?.getAttribute("content")
  const messagesContainer = document.getElementById("messagesContainer")
  const chatForm = document.getElementById("chatForm")
  const messageInput = document.getElementById("messageInput")
  const conversationItems = document.querySelectorAll("#conversationsList .conversation-item")
  const searchInput = document.getElementById("searchInput")
  const toggleConversationsBtn = document.getElementById("toggleConversations")
  const backToConversationsBtn = document.getElementById("backToConversations")
  const conversationsList = document.getElementById("conversationsList")
  const chatArea = document.getElementById("chatArea")
  const receiver = document.getElementById("receiverId")

  // Scroll to the latest message
  if (messagesContainer) {
    messagesContainer.scrollTop = messagesContainer.scrollHeight
  }

  // Handle mobile navigation
  if (toggleConversationsBtn) {
    toggleConversationsBtn.addEventListener("click", () => {
      conversationsList.classList.toggle("hidden")
      chatArea.classList.toggle("hidden")
    })
  }

  if (backToConversationsBtn) {
    backToConversationsBtn.addEventListener("click", () => {
      conversationsList.classList.remove("hidden")
      chatArea.classList.add("hidden")
    })
  }

  // Handle conversation selection
  initConversationSelection(conversationItems)

  // Handle user search
  initUserSearch(searchInput, conversationItems)

  // Handle message sending
  initMessageSending(chatForm, messageInput, messagesContainer)

  // Initialize real-time message receiving
  initRealTimeMessaging(userId, messagesContainer)

  // Initialize user presence
  initOnlineStatusUpdates()

  // Reset unread badge when viewing messages
  if (messagesContainer && receiver) {
    resetUnreadBadge()
  }

  // Initialize activity heartbeat
  initActivityHeartbeat()
}

/**
 * Format time consistently
 */
function formatTime(date) {
  return new Date(date).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", hour12: false })
}

/**
 * Initialize conversation selection
 */
function initConversationSelection(conversationItems) {
  if (!conversationItems || conversationItems.length === 0) return

  conversationItems.forEach((item) => {
    item.addEventListener("click", function () {
      const userId = this.dataset.userId
      if (userId) {
        window.location.href = `/messages/${userId}`
      }
    })
  })
}

/**
 * Initialize user search functionality
 */
function initUserSearch(searchInput, conversationItems) {
  if (!searchInput || !conversationItems) return

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase()
    conversationItems.forEach((item) => {
      const userName = item.querySelector("h3").textContent.toLowerCase()
      item.style.display = userName.includes(searchTerm) ? "block" : "none"
    })
  })
}

/**
 * Initialize message sending functionality
 */
function initMessageSending(chatForm, messageInput, messagesContainer) {
  if (!chatForm || !messageInput || !messagesContainer) return

  chatForm.addEventListener("submit", (e) => {
    e.preventDefault()
    const content = messageInput.value.trim()
    if (!content) return

    const receiverId = document.getElementById("receiverId").value
    const tempMessageId = "temp-" + Date.now()

    // Display temporary message
    displayTempMessage(content, tempMessageId, messagesContainer)
    messageInput.value = ""

    // Send message to server
    sendMessage(receiverId, content, tempMessageId)
  })
}

/**
 * Display a temporary message in the chat
 */
function displayTempMessage(content, tempMessageId, messagesContainer) {
  const messageEl = document.createElement("div")
  messageEl.id = tempMessageId
  messageEl.className = "flex mb-4 justify-end opacity-70"
  messageEl.innerHTML = `
          <div class="max-w-xs md:max-w-md">
            <div class="bg-green-600 text-white rounded-lg p-3 break-words">
              <p class="whitespace-pre-wrap">${content}</p>
            </div>
            <span class="text-xs text-gray-500 mr-2 text-right block">${formatTime(new Date())}</span>
          </div>
        `
  messagesContainer.appendChild(messageEl)
  messagesContainer.scrollTop = messagesContainer.scrollHeight
}

/**
 * Send a message to the server
 */
function sendMessage(receiverId, content, tempMessageId) {
  fetch("/messages/send", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ receiver_id: receiverId, content }),
  })
    .then((response) => response.json())
    .then((data) => {
      handleMessageSendSuccess(data, tempMessageId, receiverId)
    })
    .catch((error) => {
      handleMessageSendError(error, tempMessageId)
    })
}

/**
 * Handle successful message sending
 */
function handleMessageSendSuccess(data, tempMessageId, receiverId) {
  const tempMessage = document.getElementById(tempMessageId)
  if (tempMessage) {
    tempMessage.classList.remove("opacity-70")
    tempMessage.id = "message-" + data.message.id
  }

  // Update conversations list on sender side
  updateConversationsList(receiverId, data)
}

/**
 * Handle message sending error
 */
function handleMessageSendError(error, tempMessageId) {
  console.error("Lỗi khi gửi tin nhắn:", error)
  const tempMessage = document.getElementById(tempMessageId)
  if (tempMessage) {
    const messageContent = tempMessage.querySelector(".bg-green-600")
    if (messageContent) {
      messageContent.classList.remove("bg-green-600")
      messageContent.classList.add("bg-red-600")
    }
    tempMessage
      .querySelector("p")
      .insertAdjacentHTML(
        "beforeend",
        '<span class="ml-2 text-white text-xs"><i class="fas fa-exclamation-circle"></i> Lỗi</span>',
      )
  }
}

/**
 * Update the conversations list after sending a message
 */
function updateConversationsList(receiverId, data) {
  const conversationsList = document.getElementById("conversationsList")
  if (!conversationsList) return

  const conversationItem = conversationsList.querySelector(`[data-user-id="${receiverId}"]`)

  if (conversationItem) {
    // Update existing conversation
    updateExistingConversation(conversationItem, data)
  } else {
    // Create new conversation
    createNewConversation(conversationsList, data, receiverId)
  }
}

/**
 * Update an existing conversation in the list
 */
function updateExistingConversation(conversationItem, data) {
  // Update message preview with "You: message"
  const messagePreview = conversationItem.querySelector("p")
  if (messagePreview) {
    const truncatedContent =
      data.message.content.length > 30 ? data.message.content.substring(0, 30) + "..." : data.message.content
    messagePreview.textContent = `Bạn: ${truncatedContent}`
    messagePreview.className = "text-sm font-normal text-gray-600 truncate max-w-[180px]"
  }

  const timeSpan = conversationItem.querySelector("span.text-xs.text-gray-500")
  if (timeSpan) {
    timeSpan.textContent = formatTime(data.message.created_at)
  }

  // Move conversation to top of list
  const conversationsContainer = document.getElementById("conversationsContainer")
  if (conversationsContainer && conversationItem.parentNode) {
    conversationItem.remove()
    conversationsContainer.prepend(conversationItem)
  }
}

/**
 * Create a new conversation in the list
 */
function createNewConversation(conversationsList, data, receiverId) {
  const newUserEl = document.createElement("div")
  newUserEl.className = "p-4 border-b hover:bg-gray-50 cursor-pointer conversation-item"
  newUserEl.dataset.userId = receiverId
  newUserEl.innerHTML = `
      <div class="flex items-center">
        <div class="relative">
          <img src="${data.receiver.profile_image || "/placeholder.svg?height=50&width=50"}" alt="${data.receiver.name}" class="w-12 h-12 rounded-full">
          <span class="absolute bottom-0 right-0 w-3 h-3 ${data.receiver.is_online ? "bg-green-500" : "bg-gray-300"} rounded-full border-2 border-white"></span>
        </div>
        <div class="ml-4 flex-1">
          <div class="flex justify-between items-center">
            <h3 class="font-semibold text-gray-700 truncate max-w-[150px]">${data.receiver.name}</h3>
            <span class="text-xs text-gray-500">${formatTime(data.message.created_at)}</span>
          </div>
          <p class="text-sm font-normal text-gray-600 truncate max-w-[180px]">Bạn: ${
            data.message.content.length > 30 ? data.message.content.substring(0, 30) + "..." : data.message.content
          }</p>
        </div>
      </div>
    `
  newUserEl.addEventListener("click", () => {
    window.location.href = `/messages/${receiverId}`
  })

  // Insert at the beginning of the conversations container
  const conversationsContainer = document.getElementById("conversationsContainer")
  if (conversationsContainer) {
    conversationsContainer.prepend(newUserEl)
  } else if (conversationsList) {
    conversationsList.prepend(newUserEl)
  }
}

/**
 * Initialize real-time message receiving
 */
function initRealTimeMessaging(userId, messagesContainer) {
  if (!userId || !window.Echo) return

  window.Echo.private(`chat.${userId}`).listen("MessageSent", (e) => {
    // Always get a fresh reference to the messages container
    const currentMessagesContainer = document.getElementById("messagesContainer")
    handleIncomingMessage(e.message, userId, currentMessagesContainer)
  })
}

/**
 * Handle incoming message from Echo
 */
function handleIncomingMessage(message, userId, messagesContainer) {
  const currentReceiverId = document.getElementById("receiverId")?.value
  const conversationsList = document.getElementById("conversationsList")

  // If the current chat is open with the sender and messagesContainer exists
  if (messagesContainer && currentReceiverId && message.sender_id == currentReceiverId) {
    displayIncomingMessage(message, messagesContainer)
    // Only mark messages as read if we're viewing this specific conversation
    if (window.location.pathname.includes(`/messages/${message.sender_id}`)) {
      markMessagesAsRead(currentReceiverId)
    }
  }

  // If the message is from someone else to the current user
  if (message.sender_id != userId) {
    updateConversationsForIncomingMessage(message, conversationsList)
    showNotificationIfEnabled(message)
  }
}

/**
 * Display an incoming message in the chat
 */
function displayIncomingMessage(message, messagesContainer) {
  if (!messagesContainer) {
    console.error("Message container not found")
    return
  }

  const messageEl = document.createElement("div")
  messageEl.id = "message-" + message.id
  messageEl.className = "flex mb-4"
  messageEl.innerHTML = `
          <img src="${message.sender.profile_image || "/placeholder.svg?height=40&width=40"}" alt="${message.sender.name}" class="w-8 h-8 rounded-full mr-2">
          <div class="max-w-xs md:max-w-md">
            <div class="bg-gray-100 rounded-lg p-3 break-words">
              <p class="text-gray-800 whitespace-pre-wrap">${message.content}</p>
            </div>
            <span class="text-xs text-gray-500 ml-2">${formatTime(message.created_at)}</span>
          </div>
        `
  messagesContainer.appendChild(messageEl)
  messagesContainer.scrollTop = messagesContainer.scrollHeight
}

/**
 * Mark messages as read
 */
function markMessagesAsRead(senderId) {
  // Only mark as read if we're on the specific conversation page
  if (!window.location.pathname.includes(`/messages/${senderId}`)) {
    return
  }

  fetch(`/messages/mark-as-read/${senderId}`, {
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
      if (window.notificationBadge) {
        window.notificationBadge.update(data.remainingUnread || 0)
      }
    })
    .catch((error) => console.error("Error marking messages as read:", error))
}

/**
 * Update conversations list for incoming message
 */
function updateConversationsForIncomingMessage(message, conversationsList) {
  if (!conversationsList) return

  const conversationItem = conversationsList.querySelector(`[data-user-id="${message.sender_id}"]`)

  if (conversationItem) {
    // Update existing conversation
    updateExistingConversationForIncoming(conversationItem, message)
  } else {
    // Create new conversation
    createNewConversationForIncoming(conversationsList, message)
  }
}

/**
 * Update existing conversation for incoming message
 */
function updateExistingConversationForIncoming(conversationItem, message) {
  // Update message preview with "Name: message"
  const messagePreview = conversationItem.querySelector("p")
  if (messagePreview) {
    const truncatedContent = message.content.length > 30 ? message.content.substring(0, 30) + "..." : message.content
    messagePreview.textContent = `${message.sender.name}: ${truncatedContent}`
    // Add unread styling
    messagePreview.className = "text-sm font-medium text-black truncate max-w-[180px]"
  }

  const nameEl = conversationItem.querySelector("h3")
  if (nameEl) {
    nameEl.className = "font-semibold text-black"
  }

  conversationItem.classList.add("bg-gray-100")

  const timeSpan = conversationItem.querySelector("span.text-xs.text-gray-500")
  if (timeSpan) {
    timeSpan.textContent = formatTime(message.created_at)
  }

  // Move conversation to top of list
  const conversationsContainer = document.getElementById("conversationsContainer")
  if (conversationsContainer && conversationItem.parentNode) {
    conversationItem.remove()
    conversationsContainer.prepend(conversationItem)
  }
}

/**
 * Create new conversation for incoming message
 */
function createNewConversationForIncoming(conversationsList, message) {
  const newUserEl = document.createElement("div")
  newUserEl.className = "p-4 border-b hover:bg-gray-50 cursor-pointer conversation-item bg-gray-100"
  newUserEl.dataset.userId = message.sender_id
  newUserEl.innerHTML = `
      <div class="flex items-center">
        <div class="relative">
          <img src="${message.sender.profile_image || "/placeholder.svg?height=50&width=50"}" alt="${message.sender.name}" class="w-12 h-12 rounded-full">
          <span class="absolute bottom-0 right-0 w-3 h-3 ${message.sender.is_online ? "bg-green-500" : "bg-gray-300"} rounded-full border-2 border-white"></span>
        </div>
        <div class="ml-4 flex-1">
          <div class="flex justify-between items-center">
            <h3 class="font-semibold text-black truncate max-w-[150px]">${message.sender.name}</h3>
            <span class="text-xs text-gray-500">${formatTime(message.created_at)}</span>
          </div>
          <p class="text-sm font-medium text-black truncate max-w-[180px]">${message.sender.name}: ${
            message.content.length > 30 ? message.content.substring(0, 30) + "..." : message.content
          }</p>
        </div>
      </div>
    `
  newUserEl.addEventListener("click", () => {
    window.location.href = `/messages/${message.sender_id}`
  })

  // Insert at the beginning of the conversations container
  const conversationsContainer = document.getElementById("conversationsContainer")
  if (conversationsContainer) {
    conversationsContainer.prepend(newUserEl)
  } else if (conversationsList) {
    conversationsList.prepend(newUserEl)
  }
}

/**
 * Show browser notification if enabled
 */
function showNotificationIfEnabled(message) {
  if ("Notification" in window && Notification.permission === "granted") {
    new Notification(`Tin nhắn mới từ ${message.sender.name}`, {
      body: message.content,
      icon: message.sender.profile_image || "/images/logo.png",
    })
  }
}

/**
 * Send heartbeat to update user's activity status
 */
function initActivityHeartbeat() {
  // Update activity status every minute
  setInterval(
    () => {
      fetch("/user/update-activity", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).catch((error) => console.error("Error updating activity status:", error))
    },
    60 * 1000, // 1 minute
  )

  // Also update on page load
  fetch("/user/update-activity", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).catch((error) => console.error("Error updating activity status:", error))
}
