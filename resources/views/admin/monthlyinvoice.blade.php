<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('web/images/logo.png') }}">
    <title>Tax Invoice</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .invoice-container {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .header .logo img {
            max-height: 100px;
        }

        .info h1 {
            color: #007bff;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .copy {
            color: #dc3545;
            font-weight: bold;
            text-align: right;
        }

        .details, .amounts, .footer {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .sender p, .receiver p, .parcel p {
            margin: 0.3rem 0;
        }

        .summary p {
            margin: 0.5rem 0;
            font-size: 1.1rem;
        }

        .amount-in-words {
            font-style: italic;
            color: #343a40;
        }

        .terms p {
            font-size: 0.9rem;
        }

        .signature p {
            margin: 0.3rem 0;
        }

        .btn-custom {
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 28px;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        #invoiceContent {
            display: none;
        }

        .dropdown-container select {
            border-radius: 5px;
            padding: 0.5rem;
            width: 150px;
        }

        .invoice-number {
            font-weight: bold;
            font-size: 1.2rem;
            color: #000;
        }
    </style>
    <!-- Include jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>

<body>
    <div class="invoice-container">
        <!-- Dropdown Container (Visible by Default) -->
        <div class="dropdown-container mb-4">
            <form id="downloadForm" action="{{ url('/monthly-admin-invoice') }}" method="GET" class="d-flex flex-wrap gap-3 align-items-center">
                <div>
                    <label for="type" class="form-label">Branch Type:</label>
                    <select name="type" id="type" class="form-select" onchange="updateBranchOptions()">
                        <option value="Seller" {{ request()->input('type', 'Seller') == 'Seller' ? 'selected' : '' }}>Seller</option>
                        <option value="Booking" {{ request()->input('type') == 'Booking' ? 'selected' : '' }}>Booking</option>
                    </select>
                </div>
                <div>
                    <label for="branch_id" class="form-label">Branch:</label>
                    <select name="branch_id" id="branch_id" class="form-select">
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}" {{ $data->branch_id == $b->id ? 'selected' : '' }}>{{ $b->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="year" class="form-label">Year:</label>
                    <select name="year" id="year" class="form-select">
                        @for ($y = 2025; $y <= 2030; $y++)
                            <option value="{{ $y }}" {{ $data->selected_year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="month" class="form-label">Month:</label>
                    <select name="month" id="month" class="form-select">
                        @php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            $currentYear = date('Y'); // Current year, e.g., 2025
                            $selectedYear = $data->selected_year ?? $currentYear; // Selected year from form or default to current
                            if ($selectedYear <= $currentYear) {
                                $currentMonthIndex = date('n') - 1; // Current month index (0-based, June 2025 = 5)
                                for ($i = 0; $i <= $currentMonthIndex; $i++) {
                                    $month = $months[$i];
                                    $selected = $data->selected_month == $month ? 'selected' : '';
                                    echo "<option value='$month' $selected>$month</option>";
                                }
                            } else {
                                echo "<option value='' disabled selected>No months available</option>";
                            }
                        @endphp
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-custom" onclick="showInvoice()">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
                <!--<button type="button" class="btn btn-success btn-custom" onclick="downloadPDF()">-->
                <!--    <i class="fas fa-download me-2"></i> Download PDF-->
                <!--</button>-->
            </form>
        </div>

        <!-- Invoice Content (Hidden by Default) -->
        <div id="invoiceContent">
            <div id="printableArea">
                <header class="header d-flex flex-wrap justify-content-between align-items-center">
                    <div class="logo">
                        <img src="{{ asset('web/images/logo.png') }}" alt="Delhi Parcel Logo" class="img-fluid">
                    </div>
                    <div class="info text-end">
                        <h1>DELHI PARCEL</h1>
                        <p>D-10 Bhajanpura Delhi 110053</p>
                        <p><b>PAN:</b> AAUFD9215E | <b>GSTIN:</b> 07AAUFD9215E1Z8</p>
                        <p><b>Tel:</b> 7678149050 | <b>Email:</b> info@delhiparcel.com</p>
                    </div>
                    <div class="copy w-100 mt-2">Original Copy</div>
                </header>

                <section class="details">
                    <div class="sender-receiver d-flex flex-wrap gap-3">
                        <div class="sender flex-fill">
                            <p><strong>Sender Details:</strong></p>
                            <p><strong>Branch Name:</strong> {{ $data->branch_fullname ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $data->branch_fulladdress ?? 'N/A' }}</p>
                        </div>
                        <div class="flex-fill">
                            <p><strong>Phone:</strong> {{ $data->branch_phoneno ?? 'N/A' }}</p>
                            <p><strong>GST No:</strong> {{ $data->gstno ?? 'N/A' }}</p>
                        </div>
                        <div class="invoice-number flex-fill">
                            <p><strong>Invoice Number:</strong> {{ $data->invoice_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </section>

                <section class="amounts">
                    @php
                        $gst_rate = 0.18;
                        $sub_total = $data->total_price / (1 + $gst_rate);
                        $gst_amount = $sub_total * $gst_rate;
                    @endphp
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Postal & Courier Service's:(9968) for {{ $data->selected_month }} {{ $data->selected_year }}</strong></p>
                        </div>
                         <div class="col-sm-6">
                             <div class="summary text-end">
                                <p><strong>Sub Total:</strong> ₹ {{ number_format($sub_total, 2) }}</p>
                                <p><strong>GST(18%):</strong> ₹ {{ number_format($gst_amount, 2) }}</p>
                                <p><strong>Grand Total:</strong> ₹ {{ number_format($data->total_price, 2) }}</p>
                            </div>
                        </div>
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

                                if ($number < 0) {
                                    return 'Negative ' . numberToWords(abs($number));
                                }

                                if ($number < 10) {
                                    return $units[$number];
                                }

                                if ($number > 10 && $number < 20) {
                                    return $teens[$number - 11];
                                }

                                if ($number < 100) {
                                    return $tens[intdiv($number, 10) - 1] .
                                        ($number % 10 ? ' ' . $units[$number % 10] : '');
                                }

                                if ($number < 1000) {
                                    return $units[intdiv($number, 100)] .
                                        ' Hundred' .
                                        ($number % 100 ? ' and ' . numberToWords($number % 100) : '');
                                }

                                if ($number < 1000000) {
                                    return numberToWords(intdiv($number, 1000)) .
                                        ' Thousand' .
                                        ($number % 1000 ? ' ' . numberToWords($number % 1000) : '');
                                }

                                if ($number < 1000000000) {
                                    return numberToWords(intdiv($number, 1000000)) .
                                        ' Million' .
                                        ($number % 1000000 ? ' ' . numberToWords($number % 1000000) : '');
                                }

                                return numberToWords(intdiv($number, 1000000000)) .
                                    ' Billion' .
                                    ($number % 1000000000 ? ' ' . numberToWords($number % 1000000000) : '');
                            }
                        }
                    @endphp
                    <p class="amount-in-words">Amount In Words: {{ numberToWords(intval($data->total_price)) }}</p>
                </section>

                <section class="footer row">
                    <div class="terms col-sm-6">
                        <p><b>Terms & Conditions</b></p>
                        <p>All Dispute are subject to Delhi Jurisdiction Only </p>
                    </div>
                    <div class="signature col-sm-6 text-end">
                        <p><strong>Authorised Signatory</strong></p>
                    </div>
                </section>
                
                <p class="text-center mt-4">This is a computer generated invoice no signature required</p>
            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-primary btn-custom" onclick="printDiv('printableArea')">
                    <i class="fas fa-print me-2"></i> Print
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
         document.getElementById('year').addEventListener('change', function() {
        const year = this.value;
        const currentYear = new Date().getFullYear(); // e.g., 2025
        const monthSelect = document.getElementById('month');

        if (year > currentYear) {
            // If future year is selected, clear month dropdown
            monthSelect.innerHTML = '<option value="" disabled selected>No months available</option>';
        } else {
            // Populate months up to current month for current or past year
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            const currentMonthIndex = new Date().getMonth(); // 0-based, June = 5
            let options = '';

            for (let i = 0; i <= currentMonthIndex; i++) {
                options += `<option value="${months[i]}">${months[i]}</option>`;
            }

            monthSelect.innerHTML = options;

            // If a month was previously selected, try to reselect it
            const previouslySelectedMonth = '{{ $data->selected_month ?? "" }}';
            if (previouslySelectedMonth && months.includes(previouslySelectedMonth)) {
                monthSelect.value = previouslySelectedMonth;
            }
        }
    });
        
        
        // Show invoice content when filter button is clicked
        function showInvoice() {
            document.getElementById('invoiceContent').style.display = 'block';
        }

        // Update branch options based on selected type
        async function updateBranchOptions() {
            const type = document.getElementById('type').value;
            const branchSelect = document.getElementById('branch_id');
            
            try {
                const response = await fetch(`/get-branches?type=${type}`);
                const branches = await response.json();
                
                // Clear existing options
                branchSelect.innerHTML = '';
                
                // Add new options
                branches.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = branch.fullname;
                    branchSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching branches:', error);
            }
        }

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload(); // Reload to reset the state
        }

        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'px',
                format: 'a4'
            });

            const element = document.getElementById('printableArea');
            const year = document.getElementById('year').value;
            const month = document.getElementById('month').value;

            html2canvas(element, {
                scale: 2,
                useCORS: true
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = doc.internal.pageSize.getWidth();
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                doc.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                doc.save(`invoice_${year}_${month}.pdf`);
            }).catch(error => {
                console.error('Error generating PDF:', error);
                alert('Failed to generate PDF. Please try again.');
            });
        }

        // Check if year and month are set in URL to show invoice content
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('year') && urlParams.has('month')) {
                showInvoice();
            }
            // Initialize branch options on page load
            updateBranchOptions();
        });
    </script>
</body>

</html>