/* =====================================================
   CAMPUS LOST & FOUND - MAIN JAVASCRIPT
   ===================================================== */

// Mobile Menu Toggle
function toggleMobileMenu() {
    const nav = document.querySelector('nav');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    if (nav) {
        nav.classList.toggle('active');
    }
}

// Mobile Menu Close on Link Click
function closeMobileMenu() {
    const nav = document.querySelector('nav');
    if (nav) {
        nav.classList.remove('active');
    }
}

// Add event listener to mobile menu button
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.mobile-menu-btn');
    if (menuBtn) {
        menuBtn.addEventListener('click', toggleMobileMenu);
    }

    // Close menu when clicking on nav links
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
});

/* =====================================================
   FORM VALIDATION
   ===================================================== */

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    // Clear previous error messages
    form.querySelectorAll('.error').forEach(error => error.remove());
    form.querySelectorAll('.invalid').forEach(field => field.classList.remove('invalid'));

    // Validate each required field
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('invalid');
            showError(field, 'This field is required');
        } else {
            field.classList.remove('invalid');
        }

        // Additional validations
        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                isValid = false;
                field.classList.add('invalid');
                showError(field, 'Please enter a valid email address');
            }
        }

        if (field.type === 'tel') {
            const phoneRegex = /^[\d\s\-\+\(\)]+$/;
            if (!phoneRegex.test(field.value) || field.value.replace(/\D/g, '').length < 10) {
                isValid = false;
                field.classList.add('invalid');
                showError(field, 'Please enter a valid phone number');
            }
        }
    });

    return isValid;
}

function showError(field, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

/* =====================================================
   SEARCH FUNCTIONALITY
   ===================================================== */

function searchItems() {
    const searchInput = document.getElementById('searchInput');
    const filterCategory = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');

    if (!searchInput) return;

    const searchTerm = searchInput.value.toLowerCase();
    const categoryValue = filterCategory ? filterCategory.value : '';
    const statusValue = statusFilter ? statusFilter.value : '';

    const itemCards = document.querySelectorAll('.item-card');
    let visibleCount = 0;

    itemCards.forEach(card => {
        const itemName = card.querySelector('.item-name')?.textContent.toLowerCase() || '';
        const itemCategory = card.dataset.category || '';
        const itemStatus = card.dataset.status || '';
        const description = card.querySelector('.item-description')?.textContent.toLowerCase() || '';
        const location = card.querySelector('.item-meta')?.textContent.toLowerCase() || '';

        let matches = true;

        // Search term filter
        if (searchTerm && !itemName.includes(searchTerm) && 
            !description.includes(searchTerm) && 
            !location.includes(searchTerm)) {
            matches = false;
        }

        // Category filter
        if (categoryValue && itemCategory !== categoryValue) {
            matches = false;
        }

        // Status filter
        if (statusValue && itemStatus !== statusValue) {
            matches = false;
        }

        if (matches) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Show "no results" message if needed
    showNoResultsMessage(visibleCount);
}

function showNoResultsMessage(count) {
    const existingMessage = document.querySelector('.no-results-message');
    if (existingMessage) {
        existingMessage.remove();
    }

    if (count === 0) {
        const container = document.querySelector('.items-grid') || document.querySelector('.items-table')?.parentNode;
        if (container) {
            const message = document.createElement('div');
            message.className = 'no-results-message alert alert-info';
            message.textContent = '📭 No items found. Try adjusting your search or filters.';
            message.style.marginTop = '24px';
            message.style.justifyContent = 'center';
            container.appendChild(message);
        }
    }
}

// Add event listeners for search and filters
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');

    if (searchInput) {
        searchInput.addEventListener('input', searchItems);
    }
    if (categoryFilter) {
        categoryFilter.addEventListener('change', searchItems);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', searchItems);
    }
});

/* =====================================================
   DELETE CONFIRMATION
   ===================================================== */

let deleteItemId = null;

function confirmDelete(itemId) {
    deleteItemId = itemId;
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.add('active');
    }
}

function cancelDelete() {
    deleteItemId = null;
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function proceedDelete() {
    if (deleteItemId) {
        window.location.href = `?delete=${deleteItemId}`;
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                cancelDelete();
            }
        });
    }
});

/* =====================================================
   IMAGE UPLOAD PREVIEW
   ===================================================== */

function previewImage(inputId, previewId) {
    const fileInput = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (!fileInput) return;

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file) {
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                fileInput.value = '';
                if (preview) preview.style.display = 'none';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please upload a valid image file');
                fileInput.value = '';
                if (preview) preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    });
}

// Setup image preview
document.addEventListener('DOMContentLoaded', function() {
    previewImage('itemImage', 'imagePreview');
    previewImage('itemImage2', 'imagePreview2');
});

/* =====================================================
   FILE UPLOAD DRAG & DROP
   ===================================================== */

function setupDragAndDrop(dropZoneId, inputId) {
    const dropZone = document.getElementById(dropZoneId);
    const fileInput = document.getElementById(inputId);

    if (!dropZone) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.style.backgroundColor = 'rgba(255, 159, 67, 0.1)';
        dropZone.style.borderColor = '#FF9F43';
    }

    function unhighlight(e) {
        dropZone.style.backgroundColor = '';
        dropZone.style.borderColor = '';
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;

        // Trigger change event
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }

    // Click to upload
    if (fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setupDragAndDrop('dropZone', 'itemImage');
    setupDragAndDrop('dropZone2', 'itemImage2');
});

/* =====================================================
   VIEW TOGGLE (GRID/TABLE)
   ===================================================== */

function toggleView(viewType) {
    const grid = document.querySelector('.items-grid');
    const table = document.querySelector('.items-table');
    const gridBtn = document.querySelector('.view-toggle [data-view="grid"]');
    const tableBtn = document.querySelector('.view-toggle [data-view="table"]');

    if (viewType === 'grid') {
        if (grid) grid.style.display = 'grid';
        if (table) table.style.display = 'none';
        if (gridBtn) gridBtn.classList.add('active');
        if (tableBtn) tableBtn.classList.remove('active');
        localStorage.setItem('itemViewPreference', 'grid');
    } else {
        if (grid) grid.style.display = 'none';
        if (table) table.style.display = 'table';
        if (gridBtn) gridBtn.classList.remove('active');
        if (tableBtn) tableBtn.classList.add('active');
        localStorage.setItem('itemViewPreference', 'table');
    }
}

// Restore view preference
document.addEventListener('DOMContentLoaded', function() {
    const preference = localStorage.getItem('itemViewPreference') || 'grid';
    const buttons = document.querySelectorAll('.view-toggle button');
    if (buttons.length > 0) {
        setTimeout(() => toggleView(preference), 100);
    }
});

/* =====================================================
   SUCCESS MESSAGE AUTO-HIDE
   ===================================================== */

function autoHideAlert() {
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    });
}

document.addEventListener('DOMContentLoaded', autoHideAlert);

/* =====================================================
   SORTING FUNCTIONALITY
   ===================================================== */

function sortItems(sortType) {
    const container = document.querySelector('.items-grid');
    if (!container) return;

    const items = Array.from(container.querySelectorAll('.item-card'));

    items.sort((a, b) => {
        const dateA = new Date(a.dataset.date || 0);
        const dateB = new Date(b.dataset.date || 0);

        if (sortType === 'newest') {
            return dateB - dateA;
        } else if (sortType === 'oldest') {
            return dateA - dateB;
        }
        return 0;
    });

    container.innerHTML = '';
    items.forEach(item => container.appendChild(item));
}

document.addEventListener('DOMContentLoaded', function() {
    const sortDropdown = document.getElementById('sortDropdown');
    if (sortDropdown) {
        sortDropdown.addEventListener('change', function(e) {
            sortItems(e.target.value);
        });
    }
});

/* =====================================================
   FORM RESET
   ===================================================== */

function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        form.querySelectorAll('.error').forEach(error => error.remove());
        form.querySelectorAll('.invalid').forEach(field => field.classList.remove('invalid'));
        const preview = document.getElementById('imagePreview');
        if (preview) {
            preview.style.display = 'none';
        }
    }
}

/* =====================================================
   SMOOTH SCROLLING
   ===================================================== */

document.addEventListener('click', function(e) {
    const link = e.target.closest('a[href^="#"]');
    if (link) {
        const target = document.querySelector(link.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});

/* =====================================================
   PAGE ACTIVE INDICATOR
   ===================================================== */

function setActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('nav a');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

document.addEventListener('DOMContentLoaded', setActiveNavLink);

/* =====================================================
   TOOLTIP
   ===================================================== */

function showTooltip(element, text) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = text;
    tooltip.style.cssText = `
        position: absolute;
        background: #333;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        z-index: 1000;
        white-space: nowrap;
        pointer-events: none;
        margin-top: -35px;
    `;
    document.body.appendChild(tooltip);

    const rect = element.getBoundingClientRect();
    tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = (rect.top - 35) + 'px';

    setTimeout(() => tooltip.remove(), 3000);
}

console.log('✓ Campus Lost & Found - JavaScript loaded successfully');
