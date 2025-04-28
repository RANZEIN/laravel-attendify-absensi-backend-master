<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Absen - {{ $month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .header h1 {
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 16px;
            color: #64748b;
        }

        .day-container {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }

        .day-header {
            background-color: #f8fafc;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            color: #4f46e5;
            font-weight: bold;
            border-left: 4px solid #4f46e5;
        }

        .qr-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .qr-item {
            text-align: center;
            width: 45%;
            padding: 15px;
            border-radius: 10px;
            background-color: #f8fafc;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .qr-item h3 {
            color: #4f46e5;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .qr-code {
            margin-bottom: 10px;
            padding: 8px;
            background-color: white;
            display: inline-block;
            border-radius: 5px;
        }

        .qr-code img {
            width: 150px;
            height: 150px;
        }

        .qr-info {
            font-family: monospace;
            font-size: 12px;
            background-color: white;
            padding: 5px;
            border-radius: 4px;
            display: inline-block;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>QR Absen PT. Attendify</h1>
            <p>Bulan: {{ $month }}</p>
        </div>

        @foreach($qrCodes as $index => $qr)
            <div class="day-container">
                <div class="day-header">
                    {{ $qr->formattedDate }} ({{ Carbon\Carbon::parse($qr->date)->format('l') }})
                </div>
                <div class="qr-container">
                    <div class="qr-item">
                        <h3>Check-in</h3>
                        <div class="qr-code">
                            <img src="data:image/png;base64,{{ $qr->qrCodeCheckinImage }}" alt="Check-in QR Code">
                        </div>
                        <div class="qr-info">
                            <strong>Kode:</strong> {{ $qr->qr_checkin }}
                        </div>
                    </div>
                    <div class="qr-item">
                        <h3>Check-out</h3>
                        <div class="qr-code">
                            <img src="data:image/png;base64,{{ $qr->qrCodeCheckoutImage }}" alt="Check-out QR Code">
                        </div>
                        <div class="qr-info">
                            <strong>Kode:</strong> {{ $qr->qr_checkout }}
                        </div>
                    </div>
                </div>
            </div>

            @if(($index + 1) % 3 == 0 && $index < count($qrCodes) - 1)
                <div class="page-break"></div>
            @endif
        @endforeach

        <div class="footer">
            <p>QR Code ini hanya berlaku untuk tanggal yang tertera.</p>
            <p>© {{ date('Y') }} PT. Attendify - All Rights Reserved</p>
        </div>
    </div>
</body>

</html>
