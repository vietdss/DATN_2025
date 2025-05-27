document.addEventListener('DOMContentLoaded', function () {
  const transactionsContainer = document.getElementById('requests-list');
  const sentContainer = document.getElementById('sent-requests-list');
  let transactions = [];
  let sentTransactions = [];

  if (transactionsContainer) {
    transactions = JSON.parse(transactionsContainer.dataset.transactions || '[]');
    renderTransactions(transactions);
  }
  if (sentContainer) {
    sentTransactions = JSON.parse(sentContainer.dataset.sentTransactions || '[]');
    renderSentTransactions(sentTransactions);
  }
  
  // Chỉ cập nhật thống kê cho tab đang hiển thị
  if (!document.getElementById('sent-requests-section').classList.contains('hidden')) {
    updateStats(sentTransactions, true);
  } else {
    updateStats(transactions, false);
  }

  // Thêm chức năng tìm kiếm và lọc cho cả hai tab
  const searchInput = document.getElementById('search-input');
  const searchBtn = document.getElementById('search-btn');
  const statusFilter = document.getElementById('status-filter');
  const dateFilter = document.getElementById('date-filter');

  function isSentTabActive() {
    return !document.getElementById('sent-requests-section').classList.contains('hidden');
  }

  function handleFilter() {
    if (isSentTabActive()) {
      filterSentTransactions(sentTransactions);
    } else {
      filterTransactions(transactions);
    }
  }

  if (searchInput) {
    searchInput.addEventListener('keyup', function (e) {
      if (e.key === 'Enter') handleFilter();
    });
  }
  if (searchBtn) {
    searchBtn.addEventListener('click', handleFilter);
  }
  if (statusFilter) {
    statusFilter.addEventListener('change', handleFilter);
  }
  if (dateFilter) {
    dateFilter.addEventListener('change', handleFilter);
  }

  // Tab chuyển đổi: cập nhật thống kê và render lại theo bộ lọc
  const tabReceived = document.getElementById('tab-received');
  const tabSent = document.getElementById('tab-sent');
  const receivedSection = document.getElementById('received-requests-section');
  const sentSection = document.getElementById('sent-requests-section');

  if (tabReceived && tabSent && receivedSection && sentSection) {
    tabReceived.addEventListener('click', function () {
      tabReceived.classList.add('bg-green-500', 'text-white');
      tabReceived.classList.remove('bg-gray-200', 'text-gray-700');
      tabSent.classList.remove('bg-green-500', 'text-white');
      tabSent.classList.add('bg-gray-200', 'text-gray-700');
      receivedSection.classList.remove('hidden');
      sentSection.classList.add('hidden');
      filterTransactions(transactions);
      updateStats(transactions, false);
    });
    tabSent.addEventListener('click', function () {
      tabSent.classList.add('bg-green-500', 'text-white');
      tabSent.classList.remove('bg-gray-200', 'text-gray-700');
      tabReceived.classList.remove('bg-green-500', 'text-white');
      tabReceived.classList.add('bg-gray-200', 'text-gray-700');
      receivedSection.classList.add('hidden');
      sentSection.classList.remove('hidden');
      filterSentTransactions(sentTransactions);
      updateStats(sentTransactions, true);
    });
  }

  // Xử lý các nút xác nhận
  document.addEventListener('click', function (e) {
    if (e.target.matches('[data-action]')) {
      const action = e.target.dataset.action;
      const id = e.target.dataset.id;

      if (action && id) {
        showConfirmModal(id, action);
      }
    }
  });

  // Xử lý nút đóng modal
  const closeModalBtn = document.getElementById('closeDeleteModal');
  const cancelDeleteBtn = document.getElementById('cancelDelete');

  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', function () {
      closeModal('confirm-modal');
    });
  }

  if (cancelDeleteBtn) {
    cancelDeleteBtn.addEventListener('click', function () {
      closeModal('confirm-modal');
    });
  }

  // Xử lý nút xác nhận trong modal
  const confirmButton = document.getElementById('confirm-button');
  if (confirmButton) {
    confirmButton.addEventListener('click', function () {
      const id = this.dataset.id;
      const action = this.dataset.action;

      if (id && action) {
        updateTransaction(id, action);
        closeModal('confirm-modal');
      }
    });
  }
});

function renderTransactions(transactions) {
  const tbody = document.getElementById('requests-list');
  if (!tbody) return;

  tbody.innerHTML = '';

  let pendingCount = 0, acceptedCount = 0, rejectedCount = 0, completedCount = 0;

  if (transactions.length === 0) {
    document.getElementById('no-requests')?.classList.remove('hidden');
    updateStats([], false);
    return;
  }

  transactions.forEach(tr => {
    let statusClass = {
      pending: 'bg-yellow-100 text-yellow-800',
      accepted: 'bg-green-100 text-green-800',
      rejected: 'bg-red-100 text-red-800',
      completed: 'bg-blue-100 text-blue-800'
    }[tr.status];

    let statusText = {
      pending: 'Đang chờ',
      accepted: 'Đã chấp nhận',
      rejected: 'Đã từ chối',
      completed: 'Đã hoàn thành'
    }[tr.status];

    if (tr.status === 'pending') pendingCount++;
    if (tr.status === 'accepted') acceptedCount++;
    if (tr.status === 'rejected') rejectedCount++;
    if (tr.status === 'completed') completedCount++;

    const row = document.createElement('tr');
    row.id = `transaction-${tr.id}`;
    row.dataset.raw = JSON.stringify(tr);

    row.className = 'hover:bg-gray-50 transition-colors';
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm font-medium text-gray-900">#${tr.id}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <a href="/user/${tr.receiver.id}" class="hover:text-green-600 transition duration-150 ease-in-out">
              ${tr.receiver.name}
          </a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <a href="/item/${tr.post_id}" class="hover:text-green-600 transition duration-150 ease-in-out">
              ${tr.post.title}
          </a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm text-gray-900">${tr.quantity}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm text-gray-900">${formatDate(tr.created_at)}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
            ${statusText}
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          <div class="flex space-x-2">
            ${renderActionButtons(tr)}
          </div>
        </td>`;

    tbody.appendChild(row);
  });

  document.getElementById('pending-count').textContent = pendingCount;
  document.getElementById('accepted-count').textContent = acceptedCount;
  document.getElementById('rejected-count').textContent = rejectedCount;
  document.getElementById('completed-count').textContent = completedCount;

  document.getElementById('no-requests')?.classList.toggle('hidden', transactions.length > 0);
}

function renderSentTransactions(transactions) {
  const tbody = document.getElementById('sent-requests-list');
  if (!tbody) return;

  tbody.innerHTML = '';

  let pendingCount = 0, acceptedCount = 0, rejectedCount = 0, completedCount = 0;

  if (transactions.length === 0) {
    document.getElementById('no-sent-requests')?.classList.remove('hidden');
    updateStats([], true);
    return;
  }

  transactions.forEach(tr => {
    let statusClass = {
      pending: 'bg-yellow-100 text-yellow-800',
      accepted: 'bg-green-100 text-green-800',
      rejected: 'bg-red-100 text-red-800',
      completed: 'bg-blue-100 text-blue-800'
    }[tr.status];

    let statusText = {
      pending: 'Đang chờ',
      accepted: 'Đã chấp nhận',
      rejected: 'Đã từ chối',
      completed: 'Đã hoàn thành'
    }[tr.status];

    if (tr.status === 'pending') pendingCount++;
    if (tr.status === 'accepted') acceptedCount++;
    if (tr.status === 'rejected') rejectedCount++;
    if (tr.status === 'completed') completedCount++;

    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50 transition-colors';
    row.innerHTML = `
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">#${tr.id}</div>
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        <a href="/item/${tr.post_id}" class="hover:text-green-600 transition duration-150 ease-in-out">
          ${tr.post?.title || ''}
        </a>
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">${tr.quantity}</div>
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">${formatDate(tr.created_at)}</div>
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
          ${statusText}
        </span>
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <div class="flex space-x-2">
          ${(tr.status === 'pending' || tr.status === 'accepted')
            ? `<button data-id="${tr.post_id}" data-action="cancel" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-times mr-1.5"></i> Hủy yêu cầu
              </button>`
            : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-800">
                <i class="fas fa-lock-alt mr-1.5 text-gray-400"></i> Đã hoàn thành
              </span>`
          }
        </div>
      </td>
    `;
    tbody.appendChild(row);
  });

  document.getElementById('no-sent-requests')?.classList.toggle('hidden', transactions.length > 0);

  document.getElementById('pending-count').textContent = pendingCount;
  document.getElementById('accepted-count').textContent = acceptedCount;
  document.getElementById('rejected-count').textContent = rejectedCount;
  document.getElementById('completed-count').textContent = completedCount;
}

function formatDate(dateString) {
  const options = {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(dateString).toLocaleDateString('vi-VN', options);
}

function renderActionButtons(tr) {
  if (tr.status === 'pending') {
    return `
        <button data-id="${tr.id}" data-action="reject" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
          <i class="fas fa-times mr-1.5"></i> Từ chối
        </button>
        <button data-id="${tr.id}" data-action="accept" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          <i class="fas fa-check mr-1.5"></i> Chấp nhận
        </button>`;
  } else if (tr.status === 'accepted') {
    return `
        <button  data-id="${tr.id}" data-action="pending" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
          <i class="fas fa-undo mr-1.5"></i> Hủy
        </button>
        <button data-id="${tr.id}" data-action="completed" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <i class="fas fa-check-double mr-1.5"></i> Hoàn thành
        </button>`;
  } else if (tr.status === 'rejected') {
    return `
          <button data-id="${tr.id}" data-action="pending" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
            <i class="fas fa-redo-alt mr-1.5"></i> Mở lại yêu cầu
          </button>`;
  } else {
    return `
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-800">
            <i class="fas fa-lock-alt mr-1.5 text-gray-400"></i> Đã khóa
          </span>`;
  }
}

function filterTransactions(transactions, searchTerm = '') {
  const statusFilter = document.getElementById('status-filter')?.value || 'all';
  const dateFilter = document.getElementById('date-filter')?.value || 'all';
  const searchInput = document.getElementById('search-input')?.value.toLowerCase() || '';

  const term = searchTerm || searchInput;

  const filteredTransactions = transactions.filter(tr => {
    // Lọc theo từ khóa tìm kiếm
    const matchesSearch =
      tr.post.title.toLowerCase().includes(term) ||
      tr.receiver.name.toLowerCase().includes(term);

    if (!matchesSearch) return false;

    // Lọc theo trạng thái
    if (statusFilter !== 'all' && tr.status !== statusFilter) return false;

    // Lọc theo thời gian
    if (dateFilter !== 'all') {
      const trDate = new Date(tr.created_at);
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      if (dateFilter === 'today') {
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        return trDate >= today && trDate < tomorrow;
      } else if (dateFilter === 'week') {
        const weekStart = new Date(today);
        weekStart.setDate(today.getDate() - today.getDay());
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekStart.getDate() + 7);
        return trDate >= weekStart && trDate < weekEnd;
      } else if (dateFilter === 'month') {
        const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
        const monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        return trDate >= monthStart && trDate <= monthEnd;
      }
    }

    return true;
  });

  renderTransactions(filteredTransactions);
  updateStats(filteredTransactions, false);
}

// Lọc cho yêu cầu đã gửi
function filterSentTransactions(transactions, searchTerm = '') {
  const statusFilter = document.getElementById('status-filter')?.value || 'all';
  const dateFilter = document.getElementById('date-filter')?.value || 'all';
  const searchInput = document.getElementById('search-input')?.value.toLowerCase() || '';
  const term = searchTerm || searchInput;

  const filtered = transactions.filter(tr => {
    // Lọc theo từ khóa tìm kiếm
    const matchesSearch =
      (tr.post?.title?.toLowerCase() || '').includes(term);

    if (!matchesSearch) return false;

    // Lọc theo trạng thái
    if (statusFilter !== 'all' && tr.status !== statusFilter) return false;

    // Lọc theo thời gian
    if (dateFilter !== 'all') {
      const trDate = new Date(tr.created_at);
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      if (dateFilter === 'today') {
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        return trDate >= today && trDate < tomorrow;
      } else if (dateFilter === 'week') {
        const weekStart = new Date(today);
        weekStart.setDate(today.getDate() - today.getDay());
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekStart.getDate() + 7);
        return trDate >= weekStart && trDate < weekEnd;
      } else if (dateFilter === 'month') {
        const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
        const monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        return trDate >= monthStart && trDate <= monthEnd;
      }
    }

    return true;
  });

  renderSentTransactions(filtered);
  updateStats(filtered, true);
}

// Cập nhật thống kê
function updateStats(transactions, isSent = false) {
  let pending = 0, accepted = 0, rejected = 0, completed = 0;
  transactions.forEach(tr => {
    if (tr.status === 'pending') pending++;
    if (tr.status === 'accepted') accepted++;
    if (tr.status === 'rejected') rejected++;
    if (tr.status === 'completed') completed++;
  });
  document.getElementById('pending-count').textContent = pending;
  document.getElementById('accepted-count').textContent = accepted;
  document.getElementById('rejected-count').textContent = rejected;
  document.getElementById('completed-count').textContent = completed;
}

// Hiển thị modal xác nhận
function showConfirmModal(id, action) {
  const transaction = JSON.parse(document.getElementById(`transaction-${id}`)?.dataset.raw || '{}');

  const modal = document.getElementById('confirm-modal');
  if (!modal) return;

  const modalTitle = document.getElementById('modal-title');
  const modalMessage = document.getElementById('modal-message');
  const modalIcon = document.getElementById('modal-icon');
  const confirmButton = document.getElementById('confirm-button');

  // Cấu hình modal dựa trên hành động
  let title, message, iconClass, buttonClass, buttonText;

  switch (action) {
    case 'reject':
      title = 'Xác nhận từ chối';
      message = 'Bạn có chắc chắn muốn từ chối yêu cầu này? Hành động này không thể hoàn tác.';
      iconClass = 'bg-red-100 text-red-600';
      buttonClass = 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
      buttonText = 'Từ chối';
      break;
    case 'accept':
      title = 'Xác nhận chấp nhận';
      message = 'Bạn có chắc chắn muốn chấp nhận yêu cầu này?';
      iconClass = 'bg-green-100 text-green-600';
      buttonClass = 'bg-green-600 hover:bg-green-700 focus:ring-green-500';
      buttonText = 'Chấp nhận';
      break;
    case 'pending':
      if (transaction.status === 'rejected') {
        title = 'Xác nhận mở lại yêu cầu';
        message = 'Bạn có chắc chắn muốn mở lại yêu cầu này? Trạng thái sẽ quay về Đang chờ.';
        iconClass = 'bg-yellow-100 text-yellow-600';
        buttonClass = 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500';
        buttonText = 'Mở lại';
      } else {
        title = 'Xác nhận hủy chấp nhận';
        message = 'Bạn có chắc chắn muốn hủy chấp nhận yêu cầu này?';
        iconClass = 'bg-yellow-100 text-yellow-600';
        buttonClass = 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-500';
        buttonText = 'Hủy chấp nhận';
      }
      break;
    case 'completed':
      title = 'Xác nhận hoàn thành';
      message = 'Bạn có chắc chắn muốn đánh dấu yêu cầu này là đã hoàn thành? Hành động này không thể hoàn tác.';
      iconClass = 'bg-blue-100 text-blue-600';
      buttonClass = 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500';
      buttonText = 'Hoàn thành';
      break;
    case 'cancel':
      title = 'Xác nhận hủy yêu cầu';
      message = 'Bạn có chắc chắn muốn hủy yêu cầu này? Hành động này không thể hoàn tác.';
      iconClass = 'bg-red-100 text-red-600';
      buttonClass = 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
      buttonText = 'Hủy yêu cầu';
      break;
  }

  // Cập nhật nội dung modal
  modalTitle.textContent = title;
  modalMessage.textContent = message;

  // Cập nhật icon
  modalIcon.className = `mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10 ${iconClass}`;

  // Thêm icon phù hợp
  let iconHTML = '';
  switch (action) {
    case 'reject':
      iconHTML = '<i class="fas fa-times"></i>';
      break;
    case 'accept':
      iconHTML = '<i class="fas fa-check"></i>';
      break;
    case 'pending':
      iconHTML = '<i class="fas fa-undo"></i>';
      break;
    case 'completed':
      iconHTML = '<i class="fas fa-check-double"></i>';
      break;
    case 'cancel':
      iconHTML = '<i class="fas fa-times"></i>';
      break;
  }
  modalIcon.innerHTML = iconHTML;

  // Cập nhật nút xác nhận
  confirmButton.className = `w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm ${buttonClass}`;
  confirmButton.textContent = buttonText;

  // Lưu thông tin hành động vào nút xác nhận
  confirmButton.dataset.id = id;
  confirmButton.dataset.action = action;

  // Hiển thị modal
  modal.classList.remove('hidden');
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  modal.classList.add('hidden');
}

function updateTransaction(id, action) {
  // Hiển thị hiệu ứng loading
  const row = document.getElementById(`transaction-${id}`);
  if (row) {
    row.classList.add('opacity-50');
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  let url = `/transactions/${id}`;
  let method = 'PUT';
  let body = { action };

  // Nếu là hủy yêu cầu đã gửi (cancel), dùng DELETE và đúng route
  if (action === 'cancel') {
    url = `/item/request/${id}`; // Sửa lại URL cho đúng route
    method = 'DELETE';
    body = {};
  }

  fetch(url, {
    method: method === 'DELETE' ? 'DELETE' : 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: method === 'DELETE' ? JSON.stringify({ _method: 'DELETE' }) : JSON.stringify({ _method: 'PUT', action })
  })
    .then(response => {
      if (response.ok) {
        showNotification('Cập nhật trạng thái thành công', 'success');
        setTimeout(() => location.reload(), 1000);
      } else {
        showNotification('Đã xảy ra lỗi khi cập nhật', 'error');
        if (row) {
          row.classList.remove('opacity-50');
        }
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Đã xảy ra lỗi khi cập nhật', 'error');
      if (row) {
        row.classList.remove('opacity-50');
      }
    });
}

function showNotification(message, type = 'info') {
  let container = document.getElementById('notification-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'notification-container';
    container.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
    document.body.appendChild(container);
  }

  const notification = document.createElement('div');

  let bgColor, iconClass;
  switch (type) {
    case 'success':
      bgColor = 'bg-green-100 border-l-4 border-green-500 text-green-700';
      iconClass = 'fas fa-check-circle text-green-500';
      break;
    case 'error':
      bgColor = 'bg-red-100 border-l-4 border-red-500 text-red-700';
      iconClass = 'fas fa-times-circle text-red-500';
      break;
    default:
      bgColor = 'bg-blue-100 border-l-4 border-blue-500 text-blue-700';
      iconClass = 'fas fa-info-circle text-blue-500';
  }

  notification.className = `${bgColor} p-4 rounded shadow-md flex items-center transform transition-all duration-300 translate-x-full`;
  notification.innerHTML = `
      <i class="${iconClass} mr-3 text-lg"></i>
      <span>${message}</span>
      <button class="ml-auto text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    `;

  container.appendChild(notification);

  setTimeout(() => {
    notification.classList.remove('translate-x-full');
  }, 10);

  setTimeout(() => {
    notification.classList.add('translate-x-full');
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 5000);
}