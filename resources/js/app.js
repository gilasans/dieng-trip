import './bootstrap';

// ===== AJAX Helper =====
window.ajax = async function(url, options = {}) {
    const defaults = {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Accept': 'application/json',
        },
    };

    if (!(options.body instanceof FormData)) {
        defaults.headers['Content-Type'] = 'application/json';
        if (options.body && typeof options.body === 'object') {
            options.body = JSON.stringify(options.body);
        }
    }

    const config = { ...defaults, ...options, headers: { ...defaults.headers, ...options.headers } };
    if (options.body instanceof FormData) {
        delete config.headers['Content-Type'];
    }

    try {
        const response = await fetch(url, config);
        const data = await response.json();
        if (!response.ok) {
            throw { status: response.status, data };
        }
        return data;
    } catch (error) {
        if (error.data) throw error;
        throw { status: 500, data: { message: 'Koneksi gagal. Coba lagi.' } };
    }
};

// ===== Toast Notification =====
window.showToast = function(message, type = 'success', duration = 3000) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="flex items-center gap-2">
            <span>${getToastIcon(type)}</span>
            <span>${message}</span>
        </div>
    `;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

function getToastIcon(type) {
    switch (type) {
        case 'success': return '✅';
        case 'error': return '❌';
        case 'warning': return '⚠️';
        case 'info': return 'ℹ️';
        default: return '✅';
    }
}

// ===== Modal System =====
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
};

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay') && e.target.classList.contains('active')) {
        // Jangan close otomatis untuk modal konfirmasi
        if (e.target.id === 'custom-confirm-modal') return;
        
        e.target.classList.remove('active');
        document.body.style.overflow = '';
    }
});

// ===== Custom Confirm Modal =====
window.customConfirm = function(message, title = 'Konfirmasi') {
    return new Promise((resolve) => {
        document.getElementById('confirm-title').innerText = title;
        document.getElementById('confirm-message').innerText = message;
        
        openModal('custom-confirm-modal');
        
        const closeConfirm = () => {
            closeModal('custom-confirm-modal');
        };
        
        document.getElementById('confirm-ok-btn').onclick = function() {
            closeConfirm();
            resolve(true);
        };
        
        document.getElementById('confirm-cancel-btn').onclick = function() {
            closeConfirm();
            resolve(false);
        };
    });
};

// ===== Format Currency =====
window.formatRupiah = function(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(number);
};

// ===== Loading Skeleton =====
window.showSkeleton = function(containerId) {
    const container = document.getElementById(containerId);
    if (container) container.classList.remove('hidden');
};

window.hideSkeleton = function(containerId) {
    const container = document.getElementById(containerId);
    if (container) container.classList.add('hidden');
};

// ===== Notification Reminder =====
window.checkUpcomingItinerary = async function() {
    try {
        const data = await ajax('/itinerary/upcoming');
        if (data.upcoming) {
            const time = new Date(data.upcoming.scheduled_time);
            const now = new Date();
            const diffMinutes = (time - now) / (1000 * 60);

            if (diffMinutes > 0 && diffMinutes <= 60) {
                showReminderModal(data.upcoming);
            }
        }
    } catch (e) {
        // Silently fail
    }
};

window.showReminderModal = function(itinerary) {
    // Don't show if already dismissed
    const dismissedKey = `dismissed_${itinerary.id}`;
    if (sessionStorage.getItem(dismissedKey)) return;

    let modal = document.getElementById('reminder-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'reminder-modal';
        modal.className = 'modal-overlay';
        document.body.appendChild(modal);
    }

    const time = new Date(itinerary.scheduled_time);
    const timeStr = time.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

    modal.innerHTML = `
        <div class="modal-content" style="border-radius: 1.25rem; margin: 1rem;">
            <div class="text-center">
                <div class="text-4xl mb-3 animate-float">🔔</div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Upcoming Activity!</h3>
                <p class="text-2xl font-bold text-emerald-600 mb-1">${timeStr}</p>
                <p class="text-gray-700 font-semibold mb-1">${itinerary.title}</p>
                <p class="text-sm text-gray-500 mb-4">${itinerary.location || ''}</p>
                <button onclick="dismissReminder('${dismissedKey}')" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all">
                    OK, Siap! 👍
                </button>
            </div>
        </div>
    `;

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
};

window.dismissReminder = function(key) {
    sessionStorage.setItem(key, 'true');
    const modal = document.getElementById('reminder-modal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
};

// Check upcoming every 5 minutes
setInterval(checkUpcomingItinerary, 5 * 60 * 1000);

// ===== Lightbox =====
window.openLightbox = function(src) {
    let lightbox = document.getElementById('lightbox');
    if (!lightbox) {
        lightbox = document.createElement('div');
        lightbox.id = 'lightbox';
        lightbox.className = 'lightbox';
        lightbox.onclick = () => {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        };
        document.body.appendChild(lightbox);
    }
    lightbox.innerHTML = `<img src="${src}" alt="Preview" class="animate-scale-in">`;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
};

// ===== Image Upload Preview =====
window.previewImage = function(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0] && preview) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
};

// ===== Initialize on page load =====
document.addEventListener('DOMContentLoaded', function() {
    // Check for upcoming itinerary
    setTimeout(checkUpcomingItinerary, 2000);

    // Add fade-in animation to cards
    document.querySelectorAll('.card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.05}s`;
        card.classList.add('animate-fade-in');
    });
});
