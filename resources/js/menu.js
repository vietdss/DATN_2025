// Enhanced Menu handling functionality for header
document.addEventListener("DOMContentLoaded", () => {
    // Desktop user menu
    const userMenuButton = document.getElementById("userMenuButton")
    const userDropdown = document.getElementById("userDropdown")
  
    if (userMenuButton && userDropdown) {
      userMenuButton.addEventListener("click", (event) => {
        event.stopPropagation()
        userDropdown.classList.toggle("hidden")
  
        // Add transition classes
        if (!userDropdown.classList.contains("hidden")) {
          userDropdown.classList.add("opacity-100", "translate-y-0")
          userDropdown.classList.remove("opacity-0", "translate-y-1")
        }
  
        // Update aria-expanded attribute for accessibility
        const isExpanded = !userDropdown.classList.contains("hidden")
        userMenuButton.setAttribute("aria-expanded", isExpanded.toString())
      })
  
      // Support keyboard navigation
      userMenuButton.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault()
          userMenuButton.click()
        } else if (event.key === "Escape" && !userDropdown.classList.contains("hidden")) {
          userDropdown.classList.add("hidden")
          userMenuButton.setAttribute("aria-expanded", "false")
        }
      })
  
      // Close dropdown when clicking outside
      document.addEventListener("click", (event) => {
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
          userDropdown.classList.add("hidden")
          userMenuButton.setAttribute("aria-expanded", "false")
        }
      })
    }
  
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById("mobileMenuButton")
    const mobileMenu = document.getElementById("mobileMenu")
  
    if (mobileMenuButton && mobileMenu) {
      mobileMenuButton.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden")
  
        // Toggle between menu and close icons
        const menuIcon = mobileMenuButton.querySelector(".fa-bars")
        const closeIcon = mobileMenuButton.querySelector(".fa-times")
  
        if (menuIcon && closeIcon) {
          menuIcon.classList.toggle("hidden")
          closeIcon.classList.toggle("hidden")
        }
  
        // Prevent body scrolling when menu is open
        if (!mobileMenu.classList.contains("hidden")) {
          document.body.style.overflow = "hidden"
        } else {
          document.body.style.overflow = ""
        }
  
        // Update aria-expanded attribute
        const isExpanded = !mobileMenu.classList.contains("hidden")
        mobileMenuButton.setAttribute("aria-expanded", isExpanded.toString())
      })
  
      // Support keyboard navigation
      mobileMenuButton.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault()
          mobileMenuButton.click()
        } else if (event.key === "Escape" && !mobileMenu.classList.contains("hidden")) {
          mobileMenu.classList.add("hidden")
          mobileMenuButton.setAttribute("aria-expanded", "false")
          document.body.style.overflow = ""
  
          // Reset icons
          const menuIcon = mobileMenuButton.querySelector(".fa-bars")
          const closeIcon = mobileMenuButton.querySelector(".fa-times")
  
          if (menuIcon && closeIcon) {
            menuIcon.classList.remove("hidden")
            closeIcon.classList.add("hidden")
          }
        }
      })
    }
  
    // Mobile account dropdown
    const mobileAccountToggle = document.getElementById("mobileAccountToggle")
    const mobileAccountDropdown = document.getElementById("mobileAccountDropdown")
  
    if (mobileAccountToggle && mobileAccountDropdown) {
      mobileAccountToggle.addEventListener("click", (event) => {
        event.stopPropagation()
        mobileAccountDropdown.classList.toggle("hidden")
  
        // Toggle chevron direction
        const chevron = mobileAccountToggle.querySelector(".fa-chevron-down")
        if (chevron) {
          chevron.classList.toggle("rotate-180")
        }
  
        // Update aria-expanded attribute
        const isExpanded = !mobileAccountDropdown.classList.contains("hidden")
        mobileAccountToggle.setAttribute("aria-expanded", isExpanded.toString())
      })
  
      // Support keyboard navigation
      mobileAccountToggle.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault()
          mobileAccountToggle.click()
        } else if (event.key === "Escape" && !mobileAccountDropdown.classList.contains("hidden")) {
          mobileAccountDropdown.classList.add("hidden")
          mobileAccountToggle.setAttribute("aria-expanded", "false")
  
          // Reset chevron
          const chevron = mobileAccountToggle.querySelector(".fa-chevron-down")
          if (chevron) {
            chevron.classList.remove("rotate-180")
          }
        }
      })
    }
  
    // Close mobile menu when clicking on a link
    const mobileMenuLinks = mobileMenu?.querySelectorAll("a")
    if (mobileMenuLinks) {
      mobileMenuLinks.forEach((link) => {
        link.addEventListener("click", () => {
          if (!mobileMenu.classList.contains("hidden")) {
            mobileMenu.classList.add("hidden")
            mobileMenuButton.setAttribute("aria-expanded", "false")
            document.body.style.overflow = ""
  
            // Reset icons
            const menuIcon = mobileMenuButton.querySelector(".fa-bars")
            const closeIcon = mobileMenuButton.querySelector(".fa-times")
  
            if (menuIcon && closeIcon) {
              menuIcon.classList.remove("hidden")
              closeIcon.classList.add("hidden")
            }
          }
        })
      })
    }
  
    // Mobile search button
    const mobileSearchButton = document.getElementById("mobileSearchButton")
    const mobileSearchForm = document.getElementById("mobileSearchForm")
  
    if (mobileSearchButton && mobileSearchForm) {
      mobileSearchButton.addEventListener("click", () => {
        mobileSearchForm.classList.toggle("hidden")
  
        if (!mobileSearchForm.classList.contains("hidden")) {
          mobileSearchForm.querySelector("input")?.focus()
        }
      })
    }
  
    // Handle window resize
    window.addEventListener("resize", () => {
      // If window is resized to desktop size, reset mobile menu state
      if (window.innerWidth >= 768 && mobileMenu && !mobileMenu.classList.contains("hidden")) {
        mobileMenu.classList.add("hidden")
        document.body.style.overflow = ""
  
        if (mobileMenuButton) {
          mobileMenuButton.setAttribute("aria-expanded", "false")
  
          // Reset icons
          const menuIcon = mobileMenuButton.querySelector(".fa-bars")
          const closeIcon = mobileMenuButton.querySelector(".fa-times")
  
          if (menuIcon && closeIcon) {
            menuIcon.classList.remove("hidden")
            closeIcon.classList.add("hidden")
          }
        }
      }
    })
  })
  