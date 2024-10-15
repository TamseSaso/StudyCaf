<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9d4b3;
        }
        #reader {
            width: 500px;
        }
    </style>
</head>
<body>
    <div id="reader"></div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function onScanSuccess(decodedText, decodedResult) {
                // Handle the decoded QR code, redirect to add_point.php
                window.location.href = decodedText;
            }

            function onScanFailure(error) {
                // Log scan failures for debugging purposes
                console.warn(`QR code scan error: ${error}`);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: { width: 250, height: 250 } },
                /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</body>
</html>
