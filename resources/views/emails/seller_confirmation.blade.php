<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailData['title'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .label {
            width: 500px !important;
            padding: 10px;
            background-color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
            border: 2px solid #000 !important;
        }

        th, td {
            border: 2px solid #000 !important;
            padding: 5px;
            vertical-align: top;
        }

        .header {
            font-weight: bold;
            text-align: center;
        }

        .qr-code {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .terms {
            font-size: 13px;
        }

        .btn {
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 10px;
            text-decoration: none;
            text-align: center;
        }

        #download_btn {
            background-color: #28a745;
            color: white;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .thank-you-box {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            background-color: #f28c38;
            color: white;
            border-radius: 8px;
            margin: 20px 0;
        }

        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .steps-list {
            padding-left: 20px;
        }

        .steps-list li {
            margin-bottom: 10px;
        }

        .logo {
            text-align: center;
            margin-top: 20px;
        }

        .logo img {
            max-width: 150px;
            height: auto;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thank-you-box">
            Thank you for Booking your Parcel
        </div>

        <p>Hello {{ $mailData['sender_name'] }},</p>

        <p>Thank you for booking your parcel with us. Your parcel is successfully booked.</p>

        <p>Your Tracking ID is <span style="font-weight: bold; color: #4a6ee0;">{{ $mailData['order_id'] }}</span>. Use this Tracking ID to track your parcel.</p>

        <div class="info-box">
            <p><strong>Important: Process to be followed further</strong></p>
            <ol class="steps-list">
                <li>Take a printout of the label or download it as a PDF and paste it on your parcel.</li>
                <li>Our pickup person will come to your door to pick up the parcel.</li>
                <li>Hand over the parcel to our pickup person.</li>
                <li>Sit back, relax &amp; leave the rest to us. Your parcel will be successfully delivered on time.</li>
            </ol>
        </div>


        <div>
             <a href="{{ url('/order-Label-Email/'.$mailData['order_id'])}}" class="btn" id="download_btn">Download PDF</a>
        </div>

        <div class="logo">
            <img src="https://delhiparcel.nilet.in/web/images/logo.png" alt="Delhi Parcel" height="80px">
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>© {{ now()->year }} Delhi Parcel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
