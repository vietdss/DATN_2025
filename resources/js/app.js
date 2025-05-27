import "./bootstrap"
import Alpine from "alpinejs"
import { initChat } from "./chat"

// Import our modules for the item functionality
import "./utils"
import "./location-utils"
import "./gallery"
import "./item-request"
import "./map-handler"
import "./image-handler"
import "./explore"
import "./chat"
import "./menu" // Added menu.js import
import "./notification-badge" // Added notification badge import
import "./transaction-notification" // Added transaction notification import

import "./account-settings"
import "./address-handler"
import "./profile"
import "./statistics"
import "./transactions"
// Initialize Alpine.js
window.Alpine = Alpine
Alpine.start()

// Initialize application when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  console.log("Application initialized")

  // Initialize chat functionality if needed
  initChat()
})
