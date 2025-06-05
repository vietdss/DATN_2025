export const locationUtils = {
  // Cache to reduce redundant requests
  locationCache: new Map(),

  // Get location name using Laravel backend proxy
  getLocationName: function (lat, lng) {
    const key = `${lat},${lng}`

    if (this.locationCache.has(key)) {
      return Promise.resolve(this.locationCache.get(key))
    }

    return fetch(`/reverse-geocode?lat=${lat}&lon=${lng}`)
      .then((response) => {
        if (!response.ok) throw new Error("Không thể lấy địa điểm")
        return response.json()
      })
      .then((data) => {
        const locationName = data.display_name || "Không xác định"
        this.locationCache.set(key, locationName)
        return locationName
      })
      .catch(() => "Không thể lấy địa điểm")
  },

  // Calculate distance between two geo coordinates
  calculateDistance: (lat1, lon1, lat2, lon2) => {
    const R = 6371 // Earth radius in km
    const toRad = (deg) => (deg * Math.PI) / 180

    const dLat = toRad(lat2 - lat1)
    const dLon = toRad(lon2 - lon1)
    const a =
      Math.sin(dLat / 2) ** 2 +
      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))

    return (R * c).toFixed(2)
  },

  // Replace all .location-text elements with their location names
  updateLocationElements: function () {
    document.querySelectorAll(".location-text").forEach((element) => {
      try {
        const locationData = JSON.parse(element.dataset.location)

        element.style.cursor = "pointer"
        element.classList.add("hover:text-green-600", "transition", "duration-200")

        this.getLocationName(locationData.lat, locationData.lng)
          .then((locationName) => {
            element.innerHTML = locationName

            // Make it clickable to open Google Maps
            element.addEventListener("click", (e) => {
              e.preventDefault()
              const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${locationData.lat},${locationData.lng}`
              window.open(mapsUrl, "_blank")
            })
          })
          .catch(() => {
            element.innerHTML = "Không xác định"
          })
      } catch (error) {
        console.error("Lỗi đọc vị trí:", error)
        element.innerHTML = "Không xác định"
      }
    })
  },
}

// Auto-run on DOM ready
document.addEventListener("DOMContentLoaded", () => {
  locationUtils.updateLocationElements()
})
