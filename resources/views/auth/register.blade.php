<!doctype html>
<html lang="en">
<head>
    <title>SignUp</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/signup.css">

</head>
<body class="img js-fullheight" style="background-image: url(assets/img/4.jpg);position: relative;
">
<section  class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-wrap p-0">
                    <h3 class="mb-4 text-center">Sign Up</h3>
                    <form method="POST" class="signin-form" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <input name="fName" type="text" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="form-group">
                            <input name="lName" type="text" class="form-control" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <input name="user_id" type="text" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input id="sups" name="password" type="password" class="form-control" placeholder="Password" required>
                            <span toggle="#sups" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <input name="password_confirmation" autocomplete="new-password" id="sucps" type="password" class="form-control" placeholder="Confirm Password" required>
                            <span toggle="#sucps" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div>
                            <label for="birthDate">Birthday</label>
                            <input class="form-control" type="date" id="birthday" name="birthDate">
                        </div>
                        <div style="margin-top: 10px" class="form-group">
                            <label class="gender">Male
                                <input type="radio" checked="checked" name="gender">
                                <span class="checkmark"></span>
                            </label>
                            <label class="gender">Female
                                <input type="radio" name="gender">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary submit px-3">Sign Up</button>
                        </div>
                        <div>
                            <a href="login" style="color: #fff">Have an account?</a>
                        </div>
                    </form>
                    <!--<p class="w-100 text-center">&mdash; Or Sign In With &mdash;</p>
                    <div class="social d-flex text-center">
                        <a href="#" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> Facebook</a>
                        <a href="#" class="px-2 py-2 ml-md-1 rounded"><span class="ion-logo-twitter mr-2"></span> Twitter</a>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>

</script>
</body>
</html>