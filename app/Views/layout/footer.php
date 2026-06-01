    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    <script>
// Auto refresh notifikasi count
function updateNotifikasiCount() {
    $.get('<?= base_url("/notifikasi/count") ?>', function(data) {
        if (data.count > 0) {
            $('#notifikasi-badge').show().text(data.count);
        } else {
            $('#notifikasi-badge').hide();
        }
    });
}

setInterval(updateNotifikasiCount, 30000);

// Mark notifikasi as read
function markNotifikasiRead(id) {
    $.post('<?= base_url("/notifikasi/read") ?>/' + id, function() {
        location.reload();
    });
}
    </script>
    </body>

    </html>