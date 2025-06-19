<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailData['title'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
            height: fit-content;
            padding: 10px;
            background-color: #fff;
        }

        table {
            width: 100%;
            height: 100%;
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
            color: #000;
            font-size: 16px;
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
            font-size: 10px;
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

        #print_btn {
            background-color: #007bff;
            color: white;
            margin-right: 10px;
        }

        #download_btn {
            background-color: #28a745;
            color: white;
        }

        .btn-primary {
            text-transform: uppercase;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            font-size: 16px;
            text-align: left;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 20px 0;
        }

        .steps-list {
            margin: 20px 0;
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

        @media screen and (max-width: 480px) {
            body {
                padding: 10px;
            }

            .thank-you-box {
                font-size: 16px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thank-you-box" role="text" aria-label="Thank you message">
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
                <li>Sit back, relax & leave the rest to us. Your parcel will be successfully delivered on time.</li>
            </ol>
        </div>

        <div id="printableArea">
            <div class="label">
                <table>
                    <tr>
                        <td colspan="2" class="header">
                            <img src="https://delhiparcel.nilet.in/web/images/logo.png" alt="Delhi Parcel" height="100px">
                        </td>
                        <td class="qr-code">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://delhiparcel.nilet.in/trackOrders/{{ $mailData['order_id'] }}" alt="QR Code" width="100" height="100">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Tracking ID:</strong> {{ $mailData['order_id'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>Receiver: </strong>{{ $mailData['receiver_name'] }} | {{ $mailData['receiver_cnumber'] ?? $mailData['receiver_number'] }} <br>
                            <strong>Address: </strong>{{ $mailData['receiver_add'] ?? $mailData['receiver_address'] }} <br>
                            <strong>PIN: </strong>{{ $mailData['receiver_pincode'] ?? $mailData['receiverPinCode'] }}
                        </td>
                        <td class="center bold">
                            @if($mailData['payment_mode'] === 'COD')
                                INR {{ round(preg_replace('/[^0-9.]/', '', $mailData['codAmount'] ?? $mailData['price'] ?? 0)) }}<br><br>
                            @else
                                Pre-paid<br>
                            @endif
                            {{ 
                                ($mailData['service_type'] ?? '') === 'ex' || ($mailData['service_type'] ?? '') === 'stex' ? 'Express' : 
                                (($mailData['service_type'] ?? '') === 'SuperExpress' ? 'SuperExpress' : 'Standard') 
                            }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>Sender:</strong> {{ $mailData['sender_name'] }} | {{ $mailData['sender_number'] }} <br>
                            <strong>Address:</strong> {{ $mailData['sender_address'] }} <br>
                            <strong>PIN:</strong> {{ $mailData['sender_pincode'] }}
                        </td>
                        <td>
                            <strong>Date:</strong> {{ $mailData['datetime'] }}
                        </td>
                    </tr>
                    @if($mailData['payment_mode'] === 'COD')
                        <tr>
                            <td><strong>Product (Qty)</strong></td>
                            <td class="center"><strong>Price</strong></td>
                            <td class="center bold"><strong>Total</strong></td>
                        </tr>
                        <tr>
                            <td>Item 1</td>
                            <td class="center">
                                INR {{ round(preg_replace('/[^0-9.]/', '', $mailData['codAmount'] ?? $mailData['price'] ?? 0)) }}
                            </td>
                            <td class="center">
                                INR {{ round(preg_replace('/[^0-9.]/', '', $mailData['codAmount'] ?? $mailData['price'] ?? 0)) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">Total</td>
                            <td class="center">
                                INR {{ round(preg_replace('/[^0-9.]/', '', $mailData['codAmount'] ?? $mailData['price'] ?? 0)) }}
                            </td>
                            <td class="center">
                                INR {{ round(preg_replace('/[^0-9.]/', '', $mailData['codAmount'] ?? $mailData['price'] ?? 0)) }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="terms">
                            <strong>Terms & Conditions:</strong><br>
                            <p style="font-size:13px">
                                I/We declare that this consignment does not contain personal mail, cash, jewellery,
                                contraband, illegal drugs, any prohibited items, and commodities which can cause safety
                                hazards while transporting.
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div>
            <a href="https://delhiparcel.nilet.in/order-Label-Email-{{ $mailData['payment_mode'] === 'COD' ? 'Cod' : 'Online' }}/{{ $mailData['order_id'] }}" class="btn" id="download_btn">Download PDF</a>
        </div>
        
        <div class="logo">
            <img src="https://delhiparcel.nilet.in/web/images/logo.png" alt="Delhi Parcel" height="100px">
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>© {{ date('Y') }} Delhi Parcel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>