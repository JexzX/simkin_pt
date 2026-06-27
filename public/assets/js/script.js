// SIMKIN UIN Salatiga - Custom JavaScript

$(document).ready(function() {
    
    // Initialize DataTables
    if ($('.datatable').length) {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Confirm delete
    $('.btn-delete').on('click', function(e) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault();
            return false;
        }
        return true;
    });
    
    // Show loading on form submit
    $('form').on('submit', function() {
        if ($(this).valid !== false) {
            $('body').append('<div class="loading"><div class="spinner-border text-light" role="status"></div></div>');
        }
    });
    
    // Print function
    window.printReport = function(elementId) {
        var printContent = document.getElementById(elementId).innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    };
    
    // Export to Excel
    window.exportToExcel = function(tableId, filename) {
        var table = document.getElementById(tableId);
        var html = table.outerHTML;
        var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        var link = document.createElement('a');
        link.href = url;
        link.download = filename + '.xls';
        link.click();
    };
    
    // Chart helper
    window.createChart = function(canvasId, type, labels, data, colors) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        return new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors || ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    };
    
    // Notifikasi count updater
    window.updateNotifikasiCount = function() {
        $.get(baseUrl + '/notifikasi/count', function(response) {
            if (response.count > 0) {
                $('#notifikasi-badge').show().text(response.count);
            } else {
                $('#notifikasi-badge').hide();
            }
        });
    };
    
    // Mark notifikasi as read
    window.markNotifikasiRead = function(id) {
        $.post(baseUrl + '/notifikasi/read/' + id, function() {
            location.reload();
        });
    };
    
    // Progress bar updater
    window.updateProgress = function(elementId, percentage) {
        $('#' + elementId).css('width', percentage + '%').attr('aria-valuenow', percentage);
    };
    
    // Format number with thousand separator
    window.formatNumber = function(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };
    
});

// Base URL configuration
var baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';