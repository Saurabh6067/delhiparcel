<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #343a40;
        }

        .error-container {
            max-width: 100%;
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: #dc3545;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 0 rgba(220, 53, 69, 0.2);
        }

        .error-message {
            font-size: 1.75rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .error-description {
            margin-bottom: 2rem;
            color: #6c757d;
        }

        .astronaut {
            max-width: 150px;
            margin-bottom: 2rem;
            animation: float 6s ease-in-out infinite;
        }

        .planet {
            position: absolute;
            z-index: -1;
            opacity: 0.1;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: #dc3545;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .star {
            position: absolute;
            background-color: white;
            border-radius: 50%;
            animation: twinkle 5s infinite ease-in-out;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }
        }

        .btn-primary {
            padding: 0.75rem 2rem;
            font-weight: 500;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
    </style>
</head>

<body>
    <!-- Stars background -->
    <div class="stars" id="stars"></div>

    <div class="planet"></div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="error-container">
                    <img src="{{ asset('web/images/error404.jpg') }}" alt="Astronaut" class="astronaut img-fluid">
                    <div class="error-code">404</div>
                    <h1 class="error-message">Page Not Found</h1>
                    <p class="error-description">The page you are looking for might have been removed, had its name
                        changed, or is temporarily unavailable.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary mb-4">Return to Homepage</a>
                    <div class="mt-4">
                        <p class="text-muted small">If you think this is an error, please contact support.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Script to generate stars -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const stars = document.getElementById('stars');
            const starsCount = 100;

            for (let i = 0; i < starsCount; i++) {
                const star = document.createElement('div');
                star.classList.add('star');

                // Random position
                const x = Math.floor(Math.random() * window.innerWidth);
                const y = Math.floor(Math.random() * window.innerHeight);

                // Random size
                const size = Math.random() * 3;

                // Random animation delay
                const delay = Math.random() * 5;

                star.style.left = `${x}px`;
                star.style.top = `${y}px`;
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                star.style.animationDelay = `${delay}s`;

                stars.appendChild(star);
            }
        });
    </script>
</body>

</html>
