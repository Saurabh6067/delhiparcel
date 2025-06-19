<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('web/images/logo.png') }}">
    <title>Tax Invoice6</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .invoice-container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header .logo {
            text-align: left;
            flex: 1;
        }

        .header h1 {
            /* color: green; */
            font-size: 24px;
            margin: 0;
        }

        .header p {
            margin: 5px 0;
        }

        .info {
            text-align: right;
            flex: 1;
        }

        .copy {
            font-weight: bold;
            text-align: right;
            width: 100%;
            margin-top: 10px;
        }

        .details {
            margin-top: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .order-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* margin-bottom: 20px; */
        }

        .sender-receiver {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .sender,
        .receiver,
        .parcel {
            width: 100%;
            margin-bottom: 10px;
        }

        @media (min-width: 600px) {

            .sender,
            .receiver,
            .parcel {
                width: 30%;
            }
        }

        .amounts {
            margin-top: 20px;
        }

        .summary {
            text-align: right;
        }

        .summary p {
            margin: 5px 0;
        }

        .amount-in-words {
            margin-top: 10px;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .bank-details {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .terms {
            margin-bottom: 10px;
        }

        .signature {
            text-align: right;
        }

        .signature p {
            margin: 5px 0;
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
    <div class="invoice-container">
        <div id="printableArea">
            <header class="header">
                <div class="logo">
                    <img src="{{ asset('web/images/logo.png') }}" alt="{{ asset('web/images/logo.png') }}"
                        height="100px">

                </div>
                <div class="info">
                    <h1>DELHI PARCEL</h1>
                    <p>Near Police Station Bhajanpura, Delhi, 110053</p>
                    <p><b>PAN:</b> AAUFD9215E | <b>GSTIN:</b> 07AAUFD9215E1Z8</p>
                    <p><b>Tel:</b> 7678149050 | <b>Email:</b> info@delhiparcel.com</p>
                </div>
                <div class="copy">Original Copy</div>
            </header>

            <section class="details">
                <div class="sender-receiver">
                    <div class="parcel">
                        <p><strong>Parcel Details:</strong></p>
                        <p>Order No: <b>{{ $data->order_id }}</b></p>
                        <p>Invoice Date: <span id="today"></span></p>
                        <p>Order Date: <span style="display: none" id="dateTime">{{ $data->datetime }}</span> <span
                                id="formattedDate"></span></p>
                    </div>
                    <div class="sender">
                        <p><strong>Sender Details:</strong></p>
                        <p>{{ $data->sender_name }}</p>
                        <p>{{ $data->sender_number }}</p>
                        <p>{{ $data->sender_address }}</p>
                        
                    </div>
                    <div class="receiver">
                        <p><strong>Receiver Details:</strong></p>
                        <p>{{ $data->receiver_name }}</p>
                        <p>{{ $data->receiver_cnumber }}</p>
                        <p>{{ $data->receiver_add }}</p>
                        <p>GST No. {{$data->gstno ?? 'N/A'  }}</p>
                    </div>
                </div>
            </section>

            <section class="amounts">
                @php
                    $gst_rate = 0.18;
                    $sub_total = $data->price / (1 + $gst_rate);
                    $gst_amount = $sub_total * $gst_rate;
                @endphp
                <div class="summary">
                    <p><strong>Sub Total:</strong> ₹ {{ number_format($sub_total, 2) }}</p>
                    <p><strong>GST(18%):</strong> ₹ {{ number_format($gst_amount, 2) }}</p>
                    <p><strong>Grand Total:</strong> ₹ {{ $data->price }}</p>
                </div>
                @php
                    if (!function_exists('numberToWords')) {
                        function numberToWords($number)
                        {
                            $units = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
                            $teens = [
                                'Eleven',
                                'Twelve',
                                'Thirteen',
                                'Fourteen',
                                'Fifteen',
                                'Sixteen',
                                'Seventeen',
                                'Eighteen',
                                'Nineteen',
                            ];
                            $tens = [
                                'Ten',
                                'Twenty',
                                'Thirty',
                                'Forty',
                                'Fifty',
                                'Sixty',
                                'Seventy',
                                'Eighty',
                                'Ninety',
                            ];

                            // Handle negative numbers
                            if ($number < 0) {
                                return 'Negative ' . numberToWords(abs($number));
                            }

                            // Handle numbers less than 10
                            if ($number < 10) {
                                return $units[$number];
                            }

                            // Handle numbers between 11 and 19
                            if ($number > 10 && $number < 20) {
                                return $teens[$number - 11];
                            }

                            // Handle numbers between 10 and 99
                            if ($number < 100) {
                                return $tens[intdiv($number, 10) - 1] .
                                    ($number % 10 ? ' ' . $units[$number % 10] : '');
                            }

                            // Handle numbers between 100 and 999
                            if ($number < 1000) {
                                return $units[intdiv($number, 100)] .
                                    ' Hundred' .
                                    ($number % 100 ? ' and ' . numberToWords($number % 100) : '');
                            }

                            // Handle numbers between 1,000 and 999,999
                            if ($number < 1000000) {
                                return numberToWords(intdiv($number, 1000)) .
                                    ' Thousand' .
                                    ($number % 1000 ? ' ' . numberToWords($number % 1000) : '');
                            }

                            // Handle numbers between 1,000,000 and 999,999,999
                            if ($number < 1000000000) {
                                return numberToWords(intdiv($number, 1000000)) .
                                    ' Million' .
                                    ($number % 1000000 ? ' ' . numberToWords($number % 1000000) : '');
                            }

                            // Handle numbers 1,000,000,000 and above
                            return numberToWords(intdiv($number, 1000000000)) .
                                ' Billion' .
                                ($number % 1000000000 ? ' ' . numberToWords($number % 1000000000) : '');
                        }
                    }
                @endphp
                <p class="amount-in-words">Amount In Words: {{ numberToWords(intval($data->price)) }}</p>
            </section>

            <section class="footer">
                <div class="terms">
                    <p><b>Terms & Conditions</b></p>
                    <p>I/We declare that this consignment does not contain personal mail, cash, jewellery, contraband,
                        illegal drugs, any prohibited items and commodities which can cause safety hazards while
                        transporting.</p>
                </div>
                <div class="signature">
                    <p>Receiver's Signature:</p>
                    <p>This is a computer generated invoice no signature required</p>
                    <p><strong>Authorised Signatory</strong></p>
                </div>
            </section>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="printDiv('printableArea')" id="print_btn">
                <i class="fa fa-print noPrint"></i> Print
            </button>
        </div>
    </div>


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
