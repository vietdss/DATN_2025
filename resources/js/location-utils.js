// Location utilities for handling addresses and coordinates
export const locationUtils = {
  // Cache for location names to reduce API calls
  locationCache: new Map(),

  // Get location name from coordinates
  getLocationName: function (lat, lng) {
    const key = `${lat},${lng}`

    if (this.locationCache.has(key)) {
      return Promise.resolve(this.locationCache.get(key))
    }

    return fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10`)
      .then((response) => response.json())
      .then((data) => {
        const locationName = data.display_name || "Không xác định"
        this.locationCache.set(key, locationName)
        return locationName
      })
      .catch(() => "Không thể lấy địa điểm")
  },

  // Calculate distance between two coordinates
  calculateDistance: (lat1, lon1, lat2, lon2) => {
    const R = 6371 // Radius of the earth in km
    const toRad = (deg) => (deg * Math.PI) / 180

    const dLat = toRad(lat2 - lat1)
    const dLon = toRad(lon2 - lon1)
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2)

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
    return (R * c).toFixed(2)
  },

  // Update location text elements with actual location names
  updateLocationElements: function () {
    document.querySelectorAll(".location-text").forEach((element) => {
      try {
        const locationData = JSON.parse(element.dataset.location)

        // Add clickable styling
        element.style.cursor = "pointer"
        element.classList.add("hover:text-green-600", "transition", "duration-200")

        this.getLocationName(locationData.lat, locationData.lng)
          .then((locationName) => {
            element.innerHTML = `${locationName}`

            // Add click event to open Google Maps directions
            element.addEventListener("click", (e) => {
              e.preventDefault()
              const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${locationData.lat},${locationData.lng}`
              window.open(mapsUrl, "_blank")
            })
          })
          .catch(() => {
            element.innerHTML = `Không xác định`
          })
      } catch (error) {
        console.error("Lỗi đọc vị trí:", error)
        element.innerHTML = `Không xác định`
      }
    })
  },
}

// Initialize location elements when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  locationUtils.updateLocationElements()
})
