<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('web/images/logo.png') }}">
    <title>Delhi Parcel</title>
    <link rel="stylesheet" href="{{ asset('web/CSS/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('web/CSS/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap');

        body {
            font-family: "Merriweather", serif !important;
            font-weight: 400 !important;
            font-style: normal !important;

        }

        .image-container {
            position: relative;
            width: 100%;
            height: 250px;
            overflow: hidden;
        }

        .image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('images/service3.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            z-index: -1;
        }

        .image-container img {
            width: 100%;
            height: auto;
        }

        .overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
        }

        .card {
            display: flex;
            justify-content: space-between;
            width: auto;
            height: auto;
            color: white;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 5px;
            overflow: visible;
            background: lightgrey;
        }

        .item {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .item:hover {
            z-index: 9;
        }

        .item--1,
        .item--2,
        .item--3 {
            padding: 15px;
            border-radius: 5px !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
        }

        .quantity {
            font-size: 25px;
            font-weight: 600;
        }

        .text {
            font-size: 12px;
            font-family: inherit;
            font-weight: 600;
        }

        .bg-none {
            background-color: transparent;
        }

        #scrollTop {
            position: fixed;
            bottom: 30px;
            right: 20px;
            display: none;
            background-color: green;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        #scrollTop:hover {
            background-color: #FF5D01;
        }

        .scroll-icon {
            font-size: 20px;
            /* Adjust icon size */
        }

        @media screen and (max-width:768px) {
            body {
                height: 2000px;
                /* Tall page for scrolling */
            }

            #scrollTop {
                position: fixed;
                bottom: 30px;
                right: 10px;
                display: none;
                background-color: green;
                color: white;
                border: none;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                cursor: pointer;
            }

            #scrollTop:hover {
                background-color: #FF5D01;
            }

            .scroll-icon {
                font-size: 20px;
                /* Adjust icon size */
            }
        }
    </style>
</head>

<body>

    @include('web.inc.header')
    @yield('main')
    @include('web.inc.footer')

    <script src="{{ asset('web/js/bootstrap.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert@2/dist/sweetalert.min.js"></script>
    <script src="{{ asset('web/js/toast.js') }}"></script>
    @stack('scripts')
</body>

</html>
