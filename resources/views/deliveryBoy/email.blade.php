<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailData['title'] }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #4a6ee0;
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .otp-box {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            background-color: #e8f0fe;
            border: 2px dashed #4a6ee0;
            border-radius: 8px;
            margin: 25px 0;
            letter-spacing: 5px;
        }

        .info-box {
            font-size: 16px;
            text-align: left;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 20px 0;
        }

        .count-box {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 12px;
            background-color: #f0f8e6;
            border: 2px dashed #5cb85c;
            border-radius: 8px;
            margin: 25px 0;
            color: #2e7d32;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 5px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .highlight {
            font-weight: bold;
            color: #4a6ee0;
        }

        .btn {
            display: inline-block;
            background-color: #4a6ee0;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: bold;
        }

        @media screen and (max-width: 480px) {
            body {
                padding: 10px;
            }

            .header h2 {
                font-size: 20px;
            }

            .otp-box {
                font-size: 24px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Delhi Parcel - Order Verification</h2>
        </div>

        <p>Hello,</p>

        <p>Please use the following OTP for order verification:</p>

        <div class="otp-box" role="text" aria-label="Your OTP code">
            {{ explode(' - ', $mailData['otp'])[0] }}
        </div>

        <div class="info-box">
            @if(isset($mailData['orders']) && !empty($mailData['orders']))
                <p><strong>Orders:</strong> {{ $mailData['orders'] }}</p>
            @endif

            @if(isset($mailData['order_count']) && is_numeric($mailData['order_count']))
                <div class="count-box">
                    <p style="margin: 0;">Total Parcel Count: {{ $mailData['order_count'] }}</p>
                </div>
            @elseif(isset($mailData['orders']))
                <div class="count-box">
                    <p style="margin: 0;">Total Parcel Count: {{ substr_count($mailData['orders'], ',') + 1 }}</p>
                </div>
            @endif
        </div>

        <p>This OTP is required to mark these orders as <span class="highlight">"Delivered to Branch"</span>. Please
            provide this OTP to the delivery boy for verification.</p>

        <p>The OTP will expire once used successfully.</p>

        <p>Thank you for using Delhi Parcel services!</p>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Delhi Parcel. All rights reserved.</p>
        </div>
    </div>
</body>

</html>