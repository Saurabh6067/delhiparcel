<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="{{ url('/ForgetPassword') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-md-center">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleFormControlInput1" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn-primary w-50 fw-bold"> <i
                        class="fa-solid fa-share-from-square"></i> Send
                    OTP </button>
            </div>
        </div>
    </form>
</body>

</html>