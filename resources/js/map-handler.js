// Map handling functionality
import L from "leaflet"
function debounce(fn, delay) {
  let timeoutId;
  return function (...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => fn.apply(this, args), delay);
  };
}

export class MapHandler {
  constructor(mapElementId, options = {}) {
    this.mapElementId = mapElementId
    this.options = {
      initialLat: 10.7769,
      initialLng: 106.7009,
      initialZoom: 12,
      ...options,
    }

    this.map = null
    this.marker = null
    this.locationInput = document.getElementById(options.locationInputId || "location")
    this.addressInput = document.getElementById(options.addressInputId || "address")
    this.addressSuggestions = document.getElementById(options.addressSuggestionsId || "addressSuggestions")

    // Store map instance in a global registry to prevent duplicates
    if (!window.leafletMaps) {
      window.leafletMaps = {}
    }

    // Clean up existing map with same ID if it exists
    if (window.leafletMaps[this.mapElementId]) {
      console.log(`Cleaning up existing map with ID: ${this.mapElementId}`)
      window.leafletMaps[this.mapElementId].remove()
      window.leafletMaps[this.mapElementId] = null
    }

    // Defer initialization to ensure DOM is fully loaded
    setTimeout(() => this.init(), 100)
  }

  init() {
    // Check if map element exists
    const mapElement = document.getElementById(this.mapElementId)
    if (!mapElement) {
      console.error(`Map element with ID "${this.mapElementId}" not found`)
      return
    }

    // Ensure the map container has explicit dimensions
    if (mapElement.offsetWidth === 0 || mapElement.offsetHeight === 0) {
      console.log(`Map container ${this.mapElementId} has no dimensions, setting explicit height`)
      mapElement.style.height = "400px"
    }

    // Try to get location from hidden input
    let initialLat = this.options.initialLat
    let initialLng = this.options.initialLng
    let initialZoom = this.options.initialZoom

    if (this.locationInput && this.locationInput.value) {
      try {
        // Fix: Handle both string JSON and object formats
        let locationData = this.locationInput.value
        if (typeof locationData === 'string') {
          locationData = JSON.parse(locationData)
        }
        
        if (locationData && locationData.lat && locationData.lng) {
          initialLat = parseFloat(locationData.lat)
          initialLng = parseFloat(locationData.lng)
          initialZoom = 15
        }
      } catch (e) {
        console.error("Error parsing location data:", e)
      }
    }

    try {
      // Initialize map
      this.map = L.map(this.mapElementId).setView([initialLat, initialLng], initialZoom)
      
      // Store in global registry
      window.leafletMaps[this.mapElementId] = this.map

      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
      }).addTo(this.map)

      // Force a resize after initialization
      setTimeout(async () => {
        this.map.invalidateSize(true);
    
        // Add marker if location exists
        if (initialLat && initialLng) {
            this.marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(this.map);
    
            // Gọi fetchAddress ngay khi khởi tạo
            if (this.addressInput) {
                this.addressInput.value = await this.fetchAddress(initialLat, initialLng);
            }
    
            // Add drag end event to update location when marker is dragged
            this.marker.on("dragend", async (event) => {
                const marker = event.target;
                const position = marker.getLatLng();
                if (this.addressInput) {
                    this.addressInput.value = await this.fetchAddress(position.lat, position.lng);
                }
                if (this.locationInput) {
                    this.locationInput.value = JSON.stringify({ lat: position.lat, lng: position.lng });
                }
            });
    
            if (this.addressInput && this.addressInput.value) {
                this.marker.bindPopup("Vị trí đã chọn").openPopup();
            } else {
                this.marker.bindPopup("Vị trí đã chọn").openPopup();
            }
        }
        

        // Add my location button
        this.addMyLocationButton()

        // Map click event
        this.map.on("click", async (e) => {
          const { lat, lng } = e.latlng
          if (this.addressInput) {
            this.addressInput.value = await this.fetchAddress(lat, lng)
          }
          this.updateMap(lat, lng, "Vị trí đã chọn")
        })

        // Setup address input if it exists
        if (this.addressInput && this.addressSuggestions) {
          this.setupAddressInput()
        }
      }, 200)
    } catch (error) {
      console.error("Error initializing map:", error)
    }
  }

  addMyLocationButton() {
    if (!this.map) return

    L.Control.MyLocation = L.Control.extend({
      onAdd: () => this.createMyLocationButton(),
    })
    L.control.myLocation = (opts) => new L.Control.MyLocation(opts)
    L.control.myLocation({ position: "topright" }).addTo(this.map)
  }

  createMyLocationButton() {
    const btn = L.DomUtil.create("button", "leaflet-bar leaflet-control leaflet-control-custom")
    btn.innerHTML = "📍 Vị trí của tôi"
    btn.style.backgroundColor = "white"
    btn.style.padding = "5px"
    btn.style.cursor = "pointer"

    L.DomEvent.on(btn, "click", async (e) => {
      L.DomEvent.stopPropagation(e)
      L.DomEvent.preventDefault(e)
      this.getUserLocation()
    })

    return btn
  }

  async fetchAddress(lat, lng) {
    try {
      const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
      const data = await response.json()
      return data.display_name || "Không tìm thấy địa chỉ"
    } catch {
      return "Lỗi khi lấy địa chỉ"
    }
  }

  updateMap(lat, lng, address = "Vị trí của bạn") {
    if (!this.map) return

    if (this.marker) this.marker.remove()
    
    // Fix: Ensure lat and lng are numbers
    lat = parseFloat(lat)
    lng = parseFloat(lng)
    
    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map).bindPopup(address).openPopup()

    // Add drag end event to update location when marker is dragged
    this.marker.on("dragend", async (event) => {
      const marker = event.target
      const position = marker.getLatLng()
      if (this.addressInput) {
        this.addressInput.value = await this.fetchAddress(position.lat, position.lng)
      }
      if (this.locationInput) {
        this.locationInput.value = JSON.stringify({ lat: position.lat, lng: position.lng })
      }
    })

    if (this.locationInput) {
      this.locationInput.value = JSON.stringify({ lat, lng })
    }
    this.map.setView([lat, lng], 15)
  }

  getUserLocation() {
    if (!this.map) return

    if (!navigator.geolocation) {
      console.error("Trình duyệt không hỗ trợ định vị")
      return
    }

    navigator.geolocation.getCurrentPosition(
      async ({ coords: { latitude: lat, longitude: lng } }) => {
        if (this.addressInput) {
          this.addressInput.value = await this.fetchAddress(lat, lng)
        }
        this.updateMap(lat, lng, "Vị trí hiện tại của bạn")

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
      },
    )
  }

 setupAddressInput() {
  if (!this.addressInput || !this.addressSuggestions) return;

  // Nếu đã gán trước đó thì bỏ qua
  if (this.addressInput.dataset.listenerAttached === "true") return;

  const fetchSuggestions = async () => {
    const query = this.addressInput.value.trim();
    if (query.length < 2) {
      this.addressSuggestions.classList.add("hidden");
      return;
    }

    try {
      const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`);
      const data = await response.json();

      this.addressSuggestions.innerHTML = "";
      if (data.length === 0) {
        this.addressSuggestions.classList.add("hidden");
        return;
      }

      data.forEach((place) => {
        const item = document.createElement("div");
        item.textContent = place.display_name;
        item.classList.add("hover:bg-gray-200", "cursor-pointer", "p-2");
        item.addEventListener("click", () => {
          this.addressInput.value = place.display_name;
          this.updateMap(place.lat, place.lon, place.display_name);
          this.addressSuggestions.classList.add("hidden");
        });
        this.addressSuggestions.appendChild(item);
      });

      this.addressSuggestions.classList.remove("hidden");
    } catch (e) {
      console.error("Lỗi khi gọi API:", e);
      this.addressSuggestions.classList.add("hidden");
    }
  };

  const debouncedFetchSuggestions = debounce(fetchSuggestions.bind(this), 800);

  this.addressInput.addEventListener("input", debouncedFetchSuggestions);

  // Đánh dấu đã gán listener
  this.addressInput.dataset.listenerAttached = "true";

  document.addEventListener("click", (event) => {
    if (!this.addressInput.contains(event.target) && !this.addressSuggestions.contains(event.target)) {
      this.addressSuggestions.classList.add("hidden");
    }
  });
}
}

// Initialize map when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  // Clean up any existing maps when navigating between pages
  if (window.leafletMaps) {
    Object.keys(window.leafletMaps).forEach(mapId => {
      const map = window.leafletMaps[mapId];
      if (map) {
        map.remove();
        window.leafletMaps[mapId] = null;
      }
    });
  }
  
  // Initialize maps with unique IDs
  const createMapElement = document.getElementById("create-map");
  const exploreMapElement = document.getElementById("explore-map");
  
  if (createMapElement) {
    window.createMapHandler = new MapHandler("create-map", {
      locationInputId: "location",
      addressInputId: "address",
      addressSuggestionsId: "addressSuggestions"
    });
  }
  
  if (exploreMapElement) {
    // Explore map will be handled by explore.js
  }
  
  // For backward compatibility, check for generic "map" id
  const mapElement = document.getElementById("map");
  if (mapElement && !createMapElement && !exploreMapElement) {
    // Determine which page we're on based on URL or other context
    const isCreatePage = window.location.href.includes("/create");
    const mapId = isCreatePage ? "create-map" : "explore-map";
    
    // Update the ID to make it unique
    mapElement.id = mapId;
    
    window.mapHandler = new MapHandler(mapId);
  }
});

