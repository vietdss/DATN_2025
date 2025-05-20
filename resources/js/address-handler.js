document.addEventListener('DOMContentLoaded', function() {
    // Xử lý phụ thuộc giữa các select box địa chỉ
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const addressInput = document.getElementById('address');
    
    // Biến để lưu dữ liệu địa chỉ - khai báo ở scope cao nhất để có thể truy cập từ mọi hàm
    let provinces = [];
    let selectedProvinceName = '';
    let selectedDistrictName = '';
    let selectedWardName = '';
    
    // Hàm cập nhật giá trị địa chỉ kết hợp
    function updateCombinedAddress() {
      if (selectedWardName && selectedDistrictName && selectedProvinceName) {
        addressInput.value = `${selectedWardName}, ${selectedDistrictName}, ${selectedProvinceName}`;
        console.log("Địa chỉ đã được cập nhật:", addressInput.value);
      }
    }
    
    // Hàm phân tích địa chỉ hiện tại (nếu có)
    function parseExistingAddress() {
      const address = addressInput.value;
      console.log("Địa chỉ hiện tại:", address);
      if (address) {
        const parts = address.split(', ');
        if (parts.length >= 3) {
          selectedWardName = parts[0];
          selectedDistrictName = parts[1];
          selectedProvinceName = parts[2];
          console.log("Đã phân tích địa chỉ:", selectedWardName, selectedDistrictName, selectedProvinceName);
        }
      }
    }
    
    if (provinceSelect) {
      // Gọi API để lấy danh sách tỉnh/thành phố
      fetch('https://provinces.open-api.vn/api/?depth=1')
        .then(response => response.json())
        .then(data => {
          provinces = data;
          console.log("Đã tải danh sách tỉnh/thành phố:", provinces.length);
          
          // Thêm các tỉnh/thành phố vào select box
          provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.code;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
          });
          
          // Phân tích địa chỉ hiện tại
          parseExistingAddress();
          
          // Nếu có địa chỉ hiện tại, chọn tỉnh/thành phố tương ứng
          if (selectedProvinceName) {
            const province = provinces.find(p => p.name === selectedProvinceName);
            if (province) {
              provinceSelect.value = province.code;
              // Kích hoạt sự kiện change để load quận/huyện
              provinceSelect.dispatchEvent(new Event('change'));
            }
          }
        })
        .catch(error => console.error('Error loading provinces:', error));
    
      // Xử lý sự kiện khi chọn tỉnh/thành phố
      provinceSelect.addEventListener('change', function() {
        const provinceCode = this.value;
        const selectedOption = this.options[this.selectedIndex];
        
        // Lưu tên tỉnh/thành phố đã chọn
        if (provinceCode) {
          // Lấy tên trực tiếp từ option đã chọn thay vì gọi API
          selectedProvinceName = selectedOption.textContent;
          console.log("Đã chọn tỉnh/thành phố:", selectedProvinceName);
        } else {
          selectedProvinceName = '';
        }
        
        // Reset quận/huyện và xã/phường
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn Xã/Phường</option>';
        selectedDistrictName = '';
        selectedWardName = '';
        
        // Cập nhật địa chỉ kết hợp
        updateCombinedAddress();
        
        // Nếu đã chọn tỉnh/thành phố, lấy danh sách quận/huyện
        if (provinceCode) {
          fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
            .then(response => response.json())
            .then(data => {
              const districts = data.districts;
              console.log("Đã tải danh sách quận/huyện:", districts.length);
              
              // Thêm các quận/huyện vào select box
              districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.code;
                option.textContent = district.name;
                districtSelect.appendChild(option);
              });
              parseExistingAddress();
              // Nếu có địa chỉ hiện tại, chọn quận/huyện tương ứng
              if (selectedDistrictName) {
                const district = districts.find(d => d.name === selectedDistrictName);
                if (district) {
                  districtSelect.value = district.code;
                  // Kích hoạt sự kiện change để load xã/phường
                  districtSelect.dispatchEvent(new Event('change'));
                }
              }
            })
            .catch(error => console.error('Error loading districts:', error));
        }
      });
    }
    
    if (districtSelect) {
      // Xử lý sự kiện khi chọn quận/huyện
      districtSelect.addEventListener('change', function() {
        const districtCode = this.value;
        const selectedOption = this.options[this.selectedIndex];
        
        // Lưu tên quận/huyện đã chọn
        if (districtCode) {
          // Lấy tên trực tiếp từ option đã chọn thay vì gọi API
          selectedDistrictName = selectedOption.textContent;
          console.log("Đã chọn quận/huyện:", selectedDistrictName);
          
          // Cập nhật địa chỉ kết hợp
          updateCombinedAddress();
        } else {
          selectedDistrictName = '';
          // Cập nhật địa chỉ kết hợp
          updateCombinedAddress();
        }
        
        // Reset xã/phường
        wardSelect.innerHTML = '<option value="">Chọn Xã/Phường</option>';
        selectedWardName = '';
        
        // Nếu đã chọn quận/huyện, lấy danh sách xã/phường
        if (districtCode) {
          fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
            .then(response => response.json())
            .then(data => {
              const wards = data.wards;
              console.log("Đã tải danh sách xã/phường:", wards.length);
              
              // Thêm các xã/phường vào select box
              wards.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward.code;
                option.textContent = ward.name;
                wardSelect.appendChild(option);
              });
              parseExistingAddress();
  
              // Nếu có địa chỉ hiện tại, chọn xã/phường tương ứng
              if (selectedWardName) {
                const ward = wards.find(w => w.name === selectedWardName);
                if (ward) {
                  wardSelect.value = ward.code;
                  // Kích hoạt sự kiện change
                  wardSelect.dispatchEvent(new Event('change'));
                }
              }
            })
            .catch(error => console.error('Error loading wards:', error));
        }
      });
    }
    
    if (wardSelect) {
      // Xử lý sự kiện khi chọn xã/phường
      wardSelect.addEventListener('change', function() {
        const wardCode = this.value;
        const selectedOption = this.options[this.selectedIndex];
        
        // Lưu tên xã/phường đã chọn
        if (wardCode) {
          // Lấy tên trực tiếp từ option đã chọn thay vì gọi API
          selectedWardName = selectedOption.textContent;
          console.log("Đã chọn xã/phường:", selectedWardName);
          
          // Cập nhật địa chỉ kết hợp
          updateCombinedAddress();
        } else {
          selectedWardName = '';
          // Cập nhật địa chỉ kết hợp
          updateCombinedAddress();
        }
      });
    }
    
    // Xử lý sự kiện submit form
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
      profileForm.addEventListener('submit', function(event) {
        // Đảm bảo địa chỉ đã được cập nhật trước khi submit
        if (selectedProvinceName && selectedDistrictName && selectedWardName) {
          addressInput.value = `${selectedWardName}, ${selectedDistrictName}, ${selectedProvinceName}`;
          console.log("Địa chỉ khi submit:", addressInput.value);
        } else {
          console.log("Thiếu thông tin địa chỉ:", {
            ward: selectedWardName,
            district: selectedDistrictName,
            province: selectedProvinceName
          });
        }
      });
    }
  });
  