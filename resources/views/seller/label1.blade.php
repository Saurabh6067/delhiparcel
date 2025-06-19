<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('web/images/logo.png') }}">
    <title>Online Label</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .label {
            width: 500px;
            height: fit-content;
            padding: 10px;
            background-color: #fff;
        }

        table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        th,
        td {
            border: 2px solid #000;
            padding: 5px;
        }

        .header {
            font-weight: bold;
            color: green;
            font-size: 16px;
        }

        .qr-code {
            text-align: center;
        }

        #qrcode {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .terms {
            font-size: 10px;
        }

        #print_btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
        }

        #print_btn:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #print_btn:active {
            background-color: #004085;
            box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        #print_btn i {
            margin-right: 8px;
        }

        .btn-primary {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div>
        <div id="printableArea">
            <div class="label">
                <table>
                    <tr>
                        <td colspan="2" class="header">
                            <img src="{{ asset('web/images/logo.png') }}" alt="{{ asset('web/images/logo.png') }}"
                                height="100px">
                        </td>
                        <td class="qr-code">
                            <div id="qrcode"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>Tracking ID:</strong> {{ $data->order_id }}</td>
                        <input type ="text" value="{{ $data->order_id }}" id="ord_id" hidden>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>Receiver:</strong> {{ $data->receiver_name }} | {{ $data->receiver_cnumber }} <br>
                            <strong>Address:</strong> {{ $data->receiver_add }} <br>
                            <strong>PIN:</strong> {{ $data->receiver_pincode }}
                        </td>
                        <td class="center bold">
                            <p>Pre-paid</p>
                            <p>Surface</p>
                            <br>
                            {{ 
                                (($data->service_type ?? '') === 'ex' || ($data->service_type ?? '') === 'stex') ? 'Express' : 
                                (($data->service_type ?? '') === 'SuperExpress' ? 'SuperExpress' : 
                                'Standard') 
                            }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>Sender:</strong> {{ $data->sender_name }} | {{ $data->sender_number }} <br>
                            <strong>Address:</strong> {{ $data->sender_address }} <br>
                            <strong>PIN:</strong> {{ $data->sender_pincode }}
                        </td>
                        <td>
                            <strong>Date:</strong> <span style="display: none" id="dateTime">{{ $data->datetime }}</span> <span id="formattedDate"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="terms">
                            <h2>Terms & Conditions:</h2>
                            <p>
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
            <button type="button" class="btn btn-primary" onclick="printDiv('printableArea')" id="print_btn">
                <i class="fa fa-print noPrint"></i> Print
            </button>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code
        var orderId = document.querySelector('#ord_id').value;
        new QRCode(document.getElementById("qrcode"), {
            text: `{{ url('/trackOrders') }}/${orderId}`,
            width: 150,
            height: 150
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date
            const todayElement = document.getElementById('today');
            if (todayElement) {
                const today = new Date();
                todayElement.textContent = today.toLocaleDateString('en-GB');
            }

            // Format existing date from #dateTime
            const dateTimeElement = document.getElementById('dateTime');
            const formattedDateElement = document.getElementById('formattedDate');
            if (dateTimeElement && formattedDateElement) {
                const rawDateTime = dateTimeElement.textContent.trim();
                formattedDateElement.textContent = rawDateTime.split('|')[0].trim();
            }
        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>

</html>