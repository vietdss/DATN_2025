// Image handling functionality
export class ImageHandler {
    constructor(options = {}) {
      this.options = {
        dropZoneId: "dropZone",
        imageInputId: "imageInput",
        imagePreviewId: "imagePreview",
        imageCounterId: "imageCounter",
        imageCountId: "imageCount",
        deletedImagesContainerId: "deletedImagesContainer",
        maxImages: 4,
        ...options,
      }
  
      this.dropZone = document.getElementById(this.options.dropZoneId)
      this.imageInput = document.getElementById(this.options.imageInputId)
      this.imagePreview = document.getElementById(this.options.imagePreviewId)
      this.imageCounter = document.getElementById(this.options.imageCounterId)
      this.imageCount = document.getElementById(this.options.imageCountId)
      this.deletedImagesContainer = document.getElementById(this.options.deletedImagesContainerId)
  
      this.imageFiles = []
      this.deletedImageIds = []
  
      this.init()
    }
  
    init() {
      if (!this.dropZone || !this.imageInput || !this.imagePreview) return
  
      // Setup drop zone click
      this.dropZone.addEventListener("click", () => this.imageInput.click())
  
      // Setup drag and drop events
      ;["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        this.dropZone.addEventListener(eventName, this.preventDefaults, false)
      })
      ;["dragenter", "dragover"].forEach((eventName) => {
        this.dropZone.addEventListener(eventName, this.highlight.bind(this), false)
      })
      ;["dragleave", "drop"].forEach((eventName) => {
        this.dropZone.addEventListener(eventName, this.unhighlight.bind(this), false)
      })
  
      // Handle file drop
      this.dropZone.addEventListener("drop", this.handleDrop.bind(this), false)
  
      // Handle file selection
      this.imageInput.addEventListener("change", (event) => {
        this.handleFiles(event.target.files)
      })
  
      // Setup delete buttons for existing images
      this.setupDeleteButtons()
  
      // Initialize counter
      this.updateImageCounter()
    }
  
    preventDefaults(e) {
      e.preventDefault()
      e.stopPropagation()
    }
  
    highlight() {
      this.dropZone.classList.add("border-green-500", "bg-green-50")
    }
  
    unhighlight() {
      this.dropZone.classList.remove("border-green-500", "bg-green-50")
    }
  
    handleDrop(e) {
      const dt = e.dataTransfer
      const files = dt.files
      this.handleFiles(files)
    }
  
    handleFiles(files) {
      files = Array.from(files)
  
      // Count existing images (not deleted)
      const existingImagesCount = document.querySelectorAll(".existing-image").length
  
      if (existingImagesCount + this.imageFiles.length + files.length > this.options.maxImages) {
        this.showToast(
          `Bạn chỉ có thể có tối đa ${this.options.maxImages} ảnh. Hiện tại đã có ${existingImagesCount + this.imageFiles.length} ảnh.`,
          "warning",
        )
        return
      }
  
      files.forEach((file) => {
        if (!file.type.startsWith("image/")) return
  
        const reader = new FileReader()
        reader.onload = (e) => {
          this.addImagePreview(e.target.result, file)
        }
        reader.readAsDataURL(file)
        this.imageFiles.push(file)
      })
  
      this.updateImageCounter()
      this.updateHiddenInput()
    }
  
    addImagePreview(src, file) {
      const div = document.createElement("div")
      div.classList.add(
        "relative",
        "group",
        "rounded-lg",
        "overflow-hidden",
        "shadow-sm",
        "border",
        "border-gray-200",
        "new-image",
      )
      div.style.aspectRatio = "1/1"
  
      const img = document.createElement("img")
      img.src = src
      img.classList.add("w-full", "h-full", "object-cover")
  
      const overlay = document.createElement("div")
      overlay.classList.add(
        "absolute",
        "inset-0",
        "bg-black",
        "bg-opacity-0",
        "group-hover:bg-opacity-30",
        "transition-all",
        "duration-200",
      )
  
      const removeBtn = document.createElement("button")
      removeBtn.type = "button"
      removeBtn.innerHTML = '<i class="fas fa-trash"></i>'
      removeBtn.classList.add(
        "absolute",
        "top-2",
        "right-2",
        "bg-red-500",
        "text-white",
        "rounded-full",
        "w-8",
        "h-8",
        "flex",
        "items-center",
        "justify-center",
        "opacity-0",
        "group-hover:opacity-100",
        "transition-opacity",
        "shadow-md",
        "transform",
        "hover:scale-110",
      )
  
      removeBtn.addEventListener("click", () => {
        const index = this.imageFiles.indexOf(file)
        if (index > -1) {
          this.imageFiles.splice(index, 1)
        }
        div.style.transition = "all 0.3s"
        div.style.opacity = "0"
        div.style.transform = "scale(0.8)"
  
        setTimeout(() => {
          div.remove()
          this.updateImageCounter()
          this.updateHiddenInput()
          this.showToast("Đã xóa hình ảnh", "success")
        }, 300)
      })
  
      div.appendChild(img)
      div.appendChild(overlay)
      div.appendChild(removeBtn)
      this.imagePreview.appendChild(div)
    }
  
    setupDeleteButtons() {
      const deleteButtons = document.querySelectorAll(".delete-existing-image")
      deleteButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const imageContainer = button.closest(".existing-image")
          const imageId = imageContainer.getAttribute("data-id")
  
          // Add to deleted images
          this.deletedImageIds.push(imageId)
  
          // Create hidden input for deleted image
          if (this.deletedImagesContainer) {
            const input = document.createElement("input")
            input.type = "hidden"
            input.name = "deleted_images[]"
            input.value = imageId
            this.deletedImagesContainer.appendChild(input)
          }
  
          // Remove from DOM with animation
          imageContainer.style.transition = "all 0.3s"
          imageContainer.style.opacity = "0"
          imageContainer.style.transform = "scale(0.8)"
  
          setTimeout(() => {
            imageContainer.remove()
            this.updateImageCounter()
            this.showToast("Đã xóa hình ảnh", "success")
          }, 300)
        })
      })
    }
  
    updateImageCounter() {
      if (!this.imageCounter || !this.imageCount) return
  
      const existingImagesCount = document.querySelectorAll(".existing-image").length
      const newImagesCount = this.imageFiles.length
      const totalCount = existingImagesCount + newImagesCount
  
      this.imageCount.textContent = totalCount
  
      // Show counter if there are images
      if (totalCount > 0) {
        this.imageCounter.classList.remove("hidden")
      } else {
        this.imageCounter.classList.add("hidden")
      }
  
      // Update dropzone visibility
      if (totalCount >= this.options.maxImages) {
        this.dropZone.classList.add("opacity-50", "pointer-events-none")
      } else {
        this.dropZone.classList.remove("opacity-50", "pointer-events-none")
      }
    }
  
    updateHiddenInput() {
      // This function is needed for browsers that don't support the DataTransfer API
      if (typeof DataTransfer === "undefined") {
        console.warn("DataTransfer API not supported")
        return
      }
  
      const dataTransfer = new DataTransfer()
      this.imageFiles.forEach((file) => dataTransfer.items.add(file))
      this.imageInput.files = dataTransfer.files
    }
  
    showToast(message, type = "success") {
      if (window.utils && window.utils.showToast) {
        window.utils.showToast(message, type)
        return
      }
  
      let container = document.getElementById("toast-container")
      if (!container) {
        container = document.createElement("div")
        container.id = "toast-container"
        container.className = "fixed bottom-4 right-4 z-50 flex flex-col space-y-2"
        document.body.appendChild(container)
      }
  
      const toast = document.createElement("div")
  
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
        case "warning":
          bgColor = "bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700"
          iconClass = "fas fa-exclamation-triangle text-yellow-500"
          break
        default:
          bgColor = "bg-blue-100 border-l-4 border-blue-500 text-blue-700"
          iconClass = "fas fa-info-circle text-blue-500"
      }
  
      toast.className = `${bgColor} p-4 rounded shadow-md flex items-center transform transition-all duration-300 translate-x-full`
      toast.innerHTML = `
        <i class="${iconClass} mr-3 text-lg"></i>
        <span>${message}</span>
        <button class="ml-auto text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
          <i class="fas fa-times"></i>
        </button>
      `
  
      container.appendChild(toast)
  
      setTimeout(() => {
        toast.classList.remove("translate-x-full")
      }, 10)
  
      setTimeout(() => {
        toast.classList.add("translate-x-full")
        setTimeout(() => {
          toast.remove()
        }, 300)
      }, 5000)
    }
  }
  
  // Initialize image handler when DOM is loaded
  document.addEventListener("DOMContentLoaded", () => {
    const dropZone = document.getElementById("dropZone")
    if (dropZone) {
      window.imageHandler = new ImageHandler()
    }
  })
  