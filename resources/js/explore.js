// Explore page specific functionality
import { locationUtils } from "./location-utils"
import { utils } from "./utils"
import L from "leaflet"
import "leaflet/dist/leaflet.css"

// Fix Leaflet's default icon paths
delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
  iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
  shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
})

document.addEventListener("DOMContentLoaded", () => {
  // Initialize map if it exists
  const mapElement = document.getElementById("map") || document.getElementById("explore-map")
  if (!mapElement) return

  // Store map instances in a global registry to prevent duplicates
  if (!window.leafletMaps) {
    window.leafletMaps = {}
  }

  // Clean up existing map with same ID if it exists
  const mapId = mapElement.id
  if (window.leafletMaps[mapId]) {
    console.log(`Cleaning up existing explore map with ID: ${mapId}`)
    window.leafletMaps[mapId].remove()
    window.leafletMaps[mapId] = null
  }

  // Update ID to ensure uniqueness
  if (mapElement.id === "map") {
    mapElement.id = "explore-map"
  }

  // Ensure the map container has explicit dimensions
  if (mapElement.offsetWidth === 0 || mapElement.offsetHeight === 0) {
    console.log(`Map container ${mapElement.id} has no dimensions, setting explicit height`)
    mapElement.style.height = "400px"
  }

  // Get coordinates from URL if available
  const urlParams = new URLSearchParams(window.location.search)
  const urlLatitude = Number.parseFloat(urlParams.get("latitude"))
  const urlLongitude = Number.parseFloat(urlParams.get("longitude"))

  // Set initial coordinates - use URL params if valid, otherwise default
  const initialLat = !isNaN(urlLatitude) ? urlLatitude : 10.7769
  const initialLng = !isNaN(urlLongitude) ? urlLongitude : 106.7009
  const initialZoom = !isNaN(urlLatitude) && !isNaN(urlLongitude) ? 14 : 13

  try {
    // Initialize map with coordinates from URL if available
    const map = L.map(mapElement.id).setView([initialLat, initialLng], initialZoom)

    // Store in global registry
    window.leafletMaps[mapElement.id] = map

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map)

    let userMarker,
      itemMarkers = []

    // Get items from data attribute or global variable
    const items = window.exploreItems || []

    // Force a resize after initialization
    setTimeout(() => {
      map.invalidateSize(true)
      setupMapFunctionality(map, items, userMarker, itemMarkers, initialLat, initialLng)
    }, 200)
  } catch (error) {
    console.error("Error initializing explore map:", error)
  }
})

function setupMapFunctionality(map, items, userMarker, itemMarkers, initialLat, initialLng) {
  // Create item popup
  function createItemPopup(item) {
    const location = JSON.parse(item.location)
    const placeholder = "Đang tải..."
    let userLat = null,
      userLng = null

    if (userMarker) {
      userLat = userMarker.getLatLng().lat
      userLng = userMarker.getLatLng().lng
    }

    const popupContent = document.createElement("div")
    popupContent.innerHTML = `
      <div class="item-popup">
<img src="${item.images?.[0]?.image_url || '/placeholder.svg?height=144&width=300'}" 
         class="w-full h-36 object-cover" 
         alt="${item.title}"
         onerror="this.src='/placeholder.svg?height=144&width=300'">        <span class="category font-bold">${item.category.name}</span>
        <h3>${item.title}</h3>
        <div class="footer flex gap-x-2">
          <span class="location"><i class="fas fa-map-marker-alt"></i>Đang tải... (0 km)</span>
          <span class="time"><i class="fas fa-clock"></i> Đang tính thời gian...</span>
        </div>
        <a href="/item/${item.id}" class="block text-center bg-green-600 hover:bg-green-700 !text-white font-medium py-1 px-4 rounded-md mt-2">Xem chi tiết</a>
      </div>`

    locationUtils.getLocationName(location.lat, location.lng).then((locationName) => {
      let distanceText = "Không xác định"
      if (userLat && userLng) {
        const distance = locationUtils.calculateDistance(userLat, userLng, location.lat, location.lng)
        distanceText = `${distance} km`
      }

      const locationElement = popupContent.querySelector(".location")
      locationElement.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${locationName} (${distanceText})`

      // Make location clickable
      locationElement.style.cursor = "pointer"
      locationElement.classList.add("hover:text-green-600", "transition", "duration-200")
      locationElement.title = "Nhấn để xem chỉ đường"

      // Add click event to open Google Maps directions
      locationElement.addEventListener("click", (e) => {
        e.preventDefault()
        e.stopPropagation() // Prevent triggering other click events
        const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${location.lat},${location.lng}`
        window.open(mapsUrl, "_blank")
      })
    })

    popupContent.querySelector(".time").innerHTML = `<i class="fas fa-clock"></i> ${utils.timeSince(item.created_at)}`

    return popupContent
  }

  // Create item icon
  function createItemIcon(icon) {
    return L.divIcon({
      html: `<div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-600 text-white"><i class="fas fa-${icon}"></i></div>`,
      className: "custom-div-icon",
      iconSize: [30, 30],
      iconAnchor: [15, 15],
    })
  }

  // Add item markers to map
  function addItemMarkers() {
    itemMarkers.forEach((marker) => map.removeLayer(marker))
    itemMarkers = items.map((item) => {
      const location = JSON.parse(item.location)
      const marker = L.marker([location.lat, location.lng], {
        icon: createItemIcon(item.category.icon),
      }).addTo(map)
      marker.bindPopup(createItemPopup(item))
      return marker
    })
  }

  // Update user marker position and related inputs
  function updateUserMarkerPosition(lat, lng, popupText = "Vị trí đã chọn") {
    if (!userMarker) {
      userMarker = L.marker([lat, lng], {
        draggable: true,
        icon: L.divIcon({
          html: '<div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center"><i class="fas fa-user"></i></div>',
          className: "custom-div-icon",
          iconSize: [30, 30],
          iconAnchor: [15, 15],
        }),
      })
        .addTo(map)
        .bindPopup(popupText)
        .openPopup()

      // Add drag end event
      userMarker.on("dragend", (event) => {
        const marker = event.target
        const position = marker.getLatLng()
        updateLocationInputs(position.lat, position.lng)
      })
    } else {
      userMarker.setLatLng([lat, lng])
      userMarker.bindPopup(popupText).openPopup()
    }

    updateLocationInputs(lat, lng)
  }

  // Update location inputs
  function updateLocationInputs(lat, lng) {
    const latitudeInput = document.getElementById("latitude")
    const longitudeInput = document.getElementById("longitude")

    if (latitudeInput) latitudeInput.value = lat
    if (longitudeInput) longitudeInput.value = lng

    // Update current location text
    const currentLocationElement = document.getElementById("currentLocation")
    if (currentLocationElement) {
      locationUtils.getLocationName(lat, lng).then((locationName) => {
        currentLocationElement.textContent = locationName || "Vị trí đã chọn"
      })
    }

    // Log to confirm values are updated
    console.log("Vị trí đã cập nhật:", lat, lng)

    // Update item distances if needed
    addItemMarkers()
  }

  // Get user location
  function getUserLocation() {
    if (!navigator.geolocation) {
      console.error("Trình duyệt không hỗ trợ định vị")
      return
    }

    navigator.geolocation.getCurrentPosition(
      ({ coords: { latitude: lat, longitude: lng } }) => {
        updateUserMarkerPosition(lat, lng, "Vị trí hiện tại của bạn")
        map.setView([lat, lng], 14)

        const currentLocationElement = document.getElementById("currentLocation")
        if (currentLocationElement) {
          currentLocationElement.textContent = "Vị trí hiện tại của bạn"
        }
      },
      () => {
        console.error("Không thể lấy vị trí")
        const currentLocationElement = document.getElementById("currentLocation")
        if (currentLocationElement) {
          currentLocationElement.textContent = "Không thể xác định vị trí của bạn"
        }
        addItemMarkers()
      },
    )
  }

  // Add click event to map
  map.on("click", (e) => {
    const { lat, lng } = e.latlng
    updateUserMarkerPosition(lat, lng)
    map.setView([lat, lng], 14)
  })

  // Setup event listeners
  const useMyLocationButton = document.getElementById("useMyLocation")
  if (useMyLocationButton) {
    useMyLocationButton.addEventListener("click", () => {
      getUserLocation()
      // Add visual feedback
      useMyLocationButton.classList.add("bg-blue-700")
      setTimeout(() => {
        useMyLocationButton.classList.remove("bg-blue-700")
      }, 300)
    })
  }

  const resetButton = document.querySelector('button[type="reset"]')
  if (resetButton) {
    resetButton.addEventListener("click", () => {
      window.location.href = resetButton.dataset.resetUrl || "/"
    })
  }

  const categoryButtons = document.querySelectorAll("[data-category]")
  categoryButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const category = button.dataset.category
      window.location.href = `/item?category_id=${encodeURIComponent(category)}`
    })
  })

  // Initialize with URL coordinates if available, otherwise get user location
  const urlParams = new URLSearchParams(window.location.search)
  const urlLatitude = Number.parseFloat(urlParams.get("latitude"))
  const urlLongitude = Number.parseFloat(urlParams.get("longitude"))

  if (!isNaN(urlLatitude) && !isNaN(urlLongitude)) {
    // Use coordinates from URL
    updateUserMarkerPosition(urlLatitude, urlLongitude, "Vị trí đã chọn")

    // Update location name
    locationUtils.getLocationName(urlLatitude, urlLongitude).then((locationName) => {
      const currentLocationElement = document.getElementById("currentLocation")
      if (currentLocationElement) {
        currentLocationElement.textContent = locationName || "Vị trí đã chọn"
      }
    })
  } else {
    // No coordinates in URL, try to get user location
    getUserLocation()
  }

  // Always add item markers
  locationUtils.updateLocationElements()
  addItemMarkers()
}
