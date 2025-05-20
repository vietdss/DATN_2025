// Image gallery functionality
export function changeImage(clickedImg, newSrc) {
    const mainImage = document.getElementById("mainImage")
    if (!mainImage) return
  
    mainImage.src = newSrc
  
    document.querySelectorAll("#thumbnails img").forEach((img) => {
      img.classList.remove("border-2", "border-green-500")
      img.classList.add("hover:opacity-80")
    })
  
    clickedImg.classList.remove("hover:opacity-80")
    clickedImg.classList.add("border-2", "border-green-500")
  }
  
  // Make function available globally
  window.changeImage = changeImage
  