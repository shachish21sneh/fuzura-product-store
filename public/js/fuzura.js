/**
 * Fuzura Product Store & Warranty Management System
 * Global Utility Scripts
 */

$(document).ready(function () {
    // Setup AJAX CSRF Token for jQuery
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Mobile Sidebar Toggle
    $('#sidebarToggle, .sidebar-toggle-btn').on('click', function (e) {
        e.preventDefault();
        $('.app-sidebar').toggleClass('show');
        $('.sidebar-backdrop').toggleClass('show');
    });

    $('.sidebar-backdrop').on('click', function () {
        $('.app-sidebar').removeClass('show');
        $(this).removeClass('show');
    });

    // Auto-dismiss standard bootstrap alerts after 5 seconds
    setTimeout(function () {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
});

// SweetAlert2 Confirmation Dialog for Deletions
function confirmDelete(formId, message) {
    Swal.fire({
        title: 'Are you sure?',
        text: message || "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Copy to clipboard helper
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Copied to clipboard: ' + text,
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            alert('Copied: ' + text);
        }
    });
}
