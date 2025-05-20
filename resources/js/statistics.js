document.addEventListener('DOMContentLoaded', function() {
    // Date range filter functionality
    const dateRangeSelect = document.getElementById('dateRange');
    const customDateContainer = document.getElementById('customDateContainer');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const applyDateFilterBtn = document.getElementById('applyDateFilter');
    
    if (!dateRangeSelect) return;
    
    // Hiển thị/ẩn container tùy chỉnh ngày dựa trên giá trị đã chọn
    dateRangeSelect.addEventListener('change', function() {
      if (this.value === 'custom') {
        customDateContainer.classList.remove('hidden');
      } else {
        customDateContainer.classList.add('hidden');
        
        // Set date range based on selection
        const today = new Date();
        let startDate = new Date();
        
        switch(this.value) {
          case '7days':
            startDate.setDate(today.getDate() - 7);
            break;
          case '30days':
            startDate.setDate(today.getDate() - 30);
            break;
          case '3months':
            startDate.setMonth(today.getMonth() - 3);
            break;
          case '6months':
            startDate.setMonth(today.getMonth() - 6);
            break;
          case '1year':
            startDate.setFullYear(today.getFullYear() - 1);
            break;
        }
        
        startDateInput.valueAsDate = startDate;
        endDateInput.valueAsDate = today;
      }
    });
    
    if (applyDateFilterBtn) {
      applyDateFilterBtn.addEventListener('click', function() {
        // Update export form hidden fields
        document.getElementById('exportStartDate').value = startDateInput.value;
        document.getElementById('exportEndDate').value = endDateInput.value;
        document.getElementById('exportStartDateCsv').value = startDateInput.value;
        document.getElementById('exportEndDateCsv').value = endDateInput.value;
        document.getElementById('exportStartDatePdf').value = startDateInput.value;
        document.getElementById('exportEndDatePdf').value = endDateInput.value;
        
        // Reload page with date parameters
        const url = new URL(window.location);
        url.searchParams.set('date_range', dateRangeSelect.value);
        url.searchParams.set('start_date', startDateInput.value);
        url.searchParams.set('end_date', endDateInput.value);
        window.location = url.toString();
      });
    }
    
    // Initialize charts if they exist
    if (document.getElementById('transactionTimeChart')) {
      initTransactionTimeChart();
    }
    
    if (document.getElementById('categoryChart')) {
      initCategoryChart();
    }
  });
  
  // Các hàm khởi tạo biểu đồ sẽ được định nghĩa bên ngoài để có thể gọi từ blade
  function initTransactionTimeChart() {
    const ctx = document.getElementById('transactionTimeChart').getContext('2d');
    const labels = JSON.parse(ctx.canvas.dataset.labels || '[]');
    const sharedData = JSON.parse(ctx.canvas.dataset.shared || '[]');
    const receivedData = JSON.parse(ctx.canvas.dataset.received || '[]');
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Đã chia sẻ',
            data: sharedData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
          },
          {
            label: 'Đã nhận',
            data: receivedData,
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            tension: 0.4,
            fill: true
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            mode: 'index',
            intersect: false,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        }
      }
    });
  }
  
  function initCategoryChart() {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    const labels = JSON.parse(ctx.canvas.dataset.labels || '[]');
    const data = JSON.parse(ctx.canvas.dataset.values || '[]');
    
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: [
            'rgba(59, 130, 246, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(107, 114, 128, 0.8)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
          }
        }
      }
    });
  }
  