<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Generate QR Code</h2>
    <form id="qrForm">
        <label for="qrData">Enter Text for QR Code:</label><br>
        <input type="text" id="qrData" name="qrData"><br><br>
        <button type="submit" id="generateQR">Generate QR Code</button>
    </form>
    <div id="qrCodeContainer"></div>

    <script>
        $(document).ready(function(){
    $('#qrForm').submit(function(e){
        e.preventDefault();
        var qrData = $('#qrData').val();
        $.ajax({
            type: 'POST',
            url: 'generate_qr.php',
            data: { qrData: qrData },
            success: function(response){
                // Tampilkan QR code di dalam div
                $('#qrCodeContainer').html(response);
                
                // Mulai mode cetak
                printQRCode();
            },
            error: function(xhr, status, error){
                console.error(error);
                alert('An error occurred while generating QR code.');
            }
        });
    });
    
    // Fungsi untuk memulai mode cetak
    function printQRCode() {
        // Buat elemen baru untuk menyimpan QR code
        document.write('<html><head><title>Print QR Code</title></head><body><img src="' + $('#qrCodeContainer img').attr('src') + '"></body></html>');
        
        // Tunggu gambar QR code selesai dimuat sebelum memulai mode cetak
        document.getElementsByTagName('img')[0].onload = function() {
            window.print();
        // Tambahkan event listener untuk mendeteksi saat pencetakan selesai atau dibatalkan
            var onAfterPrint = function() {
                console.log('masuk');
                window.location.reload(); // Reload halaman setelah mencetak
                window.removeEventListener('afterprint', onAfterPrint); // Hapus event listener setelah pengguna mencetak
            };

            window.addEventListener('afterprint', onAfterPrint); // Tambahkan event listener
        };
    }
});

    </script>
</body>
</html>
