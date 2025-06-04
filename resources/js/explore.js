// Explore page - Visual Fix Version
import { locationUtils } from "./location-utils"
import L from "leaflet"
import "leaflet/dist/leaflet.css"
import { utils } from "./utils" // Declare the utils variable before using it

// Fix Leaflet's default icon paths
delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
  iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
  shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
})

document.addEventListener("DOMContentLoaded", () => {
  // Initialize map if it exists
  const mapElement = document.getElementById("explore-map")
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

    let userMarker
    const itemMarkers = []

    // Get items from data attribute or global variable
    const items = window.exploreItems || []
    console.log("🔍 Total items loaded:", items.length)

    // Force a resize after initialization and setup
    setTimeout(() => {
      map.invalidateSize(true)
      setupMapFunctionality(map, items, userMarker, itemMarkers, initialLat, initialLng)
    }, 200)
  } catch (error) {
    console.error("Error initializing explore map:", error)
  }
})

function setupMapFunctionality(map, items, userMarker, itemMarkers, initialLat, initialLng) {
  console.log("🚀 Setting up map functionality with", items.length, "items")

  // Create item popup with null safety
  function createItemPopup(item) {
    const location = JSON.parse(item.location)
    let userLat = null,
      userLng = null

    if (userMarker) {
      userLat = userMarker.getLatLng().lat
      userLng = userMarker.getLatLng().lng
    }

    const categoryName = item.category?.name || "Không có danh mục"

    const popupContent = document.createElement("div")
    popupContent.innerHTML = `
      <div class="item-popup">
        <img src="${item.images?.[0]?.image_url || "/placeholder.svg?height=144&width=300"}" 
             class="w-full h-36 object-cover" 
             alt="${item.title || "Không có tiêu đề"}"
             onerror="this.src='/placeholder.svg?height=144&width=300'">        
        <span class="category font-bold">${categoryName}</span>
        <h3>${item.title || "Không có tiêu đề"}</h3>
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
      if (locationElement) {
        locationElement.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${locationName} (${distanceText})`

        // Make location clickable
        locationElement.style.cursor = "pointer"
        locationElement.classList.add("hover:text-green-600", "transition", "duration-200")
        locationElement.title = "Nhấn để xem chỉ đường"

        // Add click event to open Google Maps directions
        locationElement.addEventListener("click", (e) => {
          e.preventDefault()
          e.stopPropagation()
          const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${location.lat},${location.lng}`
          window.open(mapsUrl, "_blank")
        })
      }
    })

    const timeElement = popupContent.querySelector(".time")
    if (timeElement && item.created_at) {
      timeElement.innerHTML = `<i class="fas fa-clock"></i> ${utils.timeSince(item.created_at)}`
    }

    return popupContent
  }

  // Create item icon with original styling
  function createItemIcon(icon) {
    const safeIcon = icon || "map-marker"
    
    return L.divIcon({
      html: `<div style="
        width: 30px; 
        height: 30px; 
        background-color: #16a34a; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: white;
        font-size: 14px;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1000;
        cursor: pointer;
      ">
        <i class="fas fa-${safeIcon}"></i>
      </div>`,
      className: "custom-div-icon",
      iconSize: [30, 30],
      iconAnchor: [15, 15],
    })
  }

  // Add item markers with FORCED VISIBILITY
  function addItemMarkers() {
    console.log("📍 Adding item markers with forced visibility...")

    // Clear existing item markers
    itemMarkers.forEach((marker, index) => {
      try {
        map.removeLayer(marker)
      } catch (error) {
        console.warn(`Error removing marker ${index}:`, error)
      }
    })
    itemMarkers.length = 0

    if (!items || items.length === 0) {
      console.warn("⚠️ No items to display on map")
      return
    }

    // Calculate bounds to spread markers across Vietnam
    let minLat = 999,
      maxLat = -999,
      minLng = 999,
      maxLng = -999
    const validItems = []

    // First pass: collect valid items and calculate bounds
    items.forEach((item, index) => {
      try {
        if (!item || !item.id || !item.location) return

        let location
        try {
          location = typeof item.location === "string" ? JSON.parse(item.location) : item.location
        } catch (parseError) {
          return
        }

        if (!location || typeof location.lat !== "number" || typeof location.lng !== "number") return

        validItems.push({ item, location, index })

        minLat = Math.min(minLat, location.lat)
        maxLat = Math.max(maxLat, location.lat)
        minLng = Math.min(minLng, location.lng)
        maxLng = Math.max(maxLng, location.lng)
      } catch (error) {
        console.warn(`Error processing item ${index}:`, error)
      }
    })

    console.log(`📊 Valid items: ${validItems.length}/${items.length}`)
    console.log(`📊 Bounds: lat[${minLat}, ${maxLat}], lng[${minLng}, ${maxLng}]`)

    // Second pass: add markers with spread positioning if they're too close
    let successCount = 0
    const usedPositions = new Set()

    validItems.forEach(({ item, location, index }) => {
      try {
        let finalLat = location.lat
        let finalLng = location.lng

        // If markers are too close, spread them out slightly
        const posKey = `${Math.round(finalLat * 1000)},${Math.round(finalLng * 1000)}`
        if (usedPositions.has(posKey)) {
          // Add small random offset to avoid exact overlap
          finalLat += (Math.random() - 0.5) * 0.01
          finalLng += (Math.random() - 0.5) * 0.01
          console.log(`🔄 Adjusted position for item ${item.id} to avoid overlap`)
        }
        usedPositions.add(posKey)

        console.log(`🎯 Creating marker ${successCount + 1} for item ${item.id} at [${finalLat}, ${finalLng}]`)

        const marker = L.marker([finalLat, finalLng], {
          icon: createItemIcon(item.category?.icon),
          zIndexOffset: 1000 + successCount, // Ensure high z-index
        }).addTo(map)

        marker.bindPopup(createItemPopup(item))
        itemMarkers.push(marker)
        successCount++

        console.log(`✅ Added marker ${successCount} for item ${item.id}`)
      } catch (error) {
        console.error(`❌ Error adding marker for item ${item.id}:`, error)
      }
    })

    console.log(`📊 Final result: ${successCount} markers added successfully`)

    // Update item count in UI
    const itemCountElement = document.getElementById("itemCount")
    if (itemCountElement) {
      itemCountElement.textContent = successCount
    }

    // FIT BOUNDS TO SHOW ALL MARKERS
    if (itemMarkers.length > 0) {
      console.log("🗺️ Fitting bounds to show all markers...")
      try {
        const group = new L.featureGroup(itemMarkers)
        const bounds = group.getBounds()

        if (bounds.isValid()) {
          // Use a wider padding to ensure all markers are visible
          map.fitBounds(bounds, {
            padding: [50, 50],
            maxZoom: 12, // Don't zoom in too much
          })
          console.log("✅ Map bounds fitted to show all markers")

          // Force map refresh after bounds change
          setTimeout(() => {
            map.invalidateSize(true)
            console.log("🔄 Map refreshed after bounds fit")
          }, 100)
        }
      } catch (error) {
        console.warn("Error fitting bounds:", error)
      }
    }

    // FORCE MARKER VISIBILITY CHECK
    setTimeout(() => {
      console.log("🔍 Checking marker visibility...")
      let visibleCount = 0
      itemMarkers.forEach((marker, index) => {
        if (map.hasLayer(marker)) {
          visibleCount++
          const pos = marker.getLatLng()
          const isInBounds = map.getBounds().contains(pos)
          console.log(`👁️ Marker ${index + 1}: visible=${isInBounds}, pos=[${pos.lat}, ${pos.lng}]`)
        } else {
          console.log(`❌ Marker ${index + 1}: NOT ON MAP`)
        }
      })
      console.log(`📊 Visibility check: ${visibleCount}/${itemMarkers.length} markers visible`)
    }, 1000)
  }

  // Update user marker position and related inputs
  function updateUserMarkerPosition(lat, lng, popupText = "Vị trí đã chọn") {
    console.log("👤 Updating user marker position:", lat, lng)

    if (!userMarker) {
      userMarker = L.marker([lat, lng], {
        draggable: true,
        icon: L.divIcon({
          html: `<div style="
            width: 30px; 
            height: 30px; 
            background-color: #2563eb; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white;
            font-size: 14px;
            border: 3px solid white;
            box-shadow: 0 3px 8px rgba(0,0,0,0.4);
            z-index: 2000;
          ">
            <i class="fas fa-user"></i>
          </div>`,
          className: "user-marker",
          iconSize: [30, 30],
          iconAnchor: [15, 15],
        }),
        zIndexOffset: 2000,
      })
        .addTo(map)
        .bindPopup(popupText)
        .openPopup()

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

  // Update location inputs - PREVENT MARKER REFRESH
  function updateLocationInputs(lat, lng) {
    const latitudeInput = document.getElementById("latitude")
    const longitudeInput = document.getElementById("longitude")

    if (latitudeInput) latitudeInput.value = lat
    if (longitudeInput) longitudeInput.value = lng

    const currentLocationElement = document.getElementById("currentLocation")
    if (currentLocationElement) {
      locationUtils.getLocationName(lat, lng).then((locationName) => {
        currentLocationElement.textContent = locationName || "Vị trí đã chọn"
      })
    }

    console.log("📍 Location inputs updated:", lat, lng)
    // DO NOT call addItemMarkers() here!
  }

  // Get user location
  function getUserLocation() {
    if (!navigator.geolocation) {
      console.error("Trình duyệt không hỗ trợ định vị")
      return
    }

    console.log("🌍 Getting user location...")

    navigator.geolocation.getCurrentPosition(
      ({ coords: { latitude: lat, longitude: lng } }) => {
        console.log("✅ Got user location:", lat, lng)
        updateUserMarkerPosition(lat, lng, "Vị trí hiện tại của bạn")

        // DON'T change map view - keep showing all markers
        console.log("🗺️ Keeping current map view to show all markers")

        const currentLocationElement = document.getElementById("currentLocation")
        if (currentLocationElement) {
          currentLocationElement.textContent = "Vị trí hiện tại của bạn"
        }
      },
      (error) => {
        console.error("❌ Cannot get location:", error)
        const currentLocationElement = document.getElementById("currentLocation")
        if (currentLocationElement) {
          currentLocationElement.textContent = "Không thể xác định vị trí của bạn"
        }
      },
    )
  }

  // Add click event to map
  map.on("click", (e) => {
    const { lat, lng } = e.latlng
    updateUserMarkerPosition(lat, lng)
    // Don't change map view
  })

  // Setup event listeners
  const useMyLocationButton = document.getElementById("useMyLocation")
  if (useMyLocationButton) {
    useMyLocationButton.addEventListener("click", () => {
      getUserLocation()
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

  // Initialize with URL coordinates if available
  const urlParams = new URLSearchParams(window.location.search)
  const urlLatitude = Number.parseFloat(urlParams.get("latitude"))
  const urlLongitude = Number.parseFloat(urlParams.get("longitude"))

  if (!isNaN(urlLatitude) && !isNaN(urlLongitude)) {
    console.log("🔗 Using coordinates from URL:", urlLatitude, urlLongitude)
    updateUserMarkerPosition(urlLatitude, urlLongitude, "Vị trí đã chọn")
  } else {
    console.log("🌍 No URL coordinates")
  }

  // Initialize location elements and add markers
  locationUtils.updateLocationElements()

  // Add all item markers
  console.log("🎯 Adding initial markers...")
  addItemMarkers()
}