<!doctype html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="img js-fullheight" style="background-image: url(assets/img/4.jpg);">
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
            </div>
        </div>
        <div class="col-md-6 text-center mb-5"></div>
        <div class="col-md-6 text-center mb-5"></div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-wrap p-0">
                    <h3 class="mb-4 text-center">Have an account?</h3>
                    <form class="signin-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input name="user_id" type="text" class="form-control" placeholder="Username" required>
                        </div>
                        @error('user_id')
                        <small class="from-text text-danger">{{"wrong password or email"}}</small>
                        @enderror
                        <div class="form-group">
                            <input name="password" id="lgps" type="password" class="form-control" placeholder="Password" required>
                            <span toggle="#lgps" style="cursor: pointer" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        @error('password')
                        <small class="from-text text-danger">{{"wrong password or email"}}</small>
                        @enderror
                        <div class="form-group">
                            <button type="submit" id="login" class="form-control btn btn-primary submit px-3">Sign In</button>
                        </div>
                        <div>
                            <a href="register" style="color: #fff">Don't have an account?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
