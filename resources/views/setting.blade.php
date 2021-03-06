<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Agenda</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Font-awesome Icons-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/css/setting.css" rel="stylesheet" />
</head>
<body>
<div class="container rounded mt-5 trans">
    <div class="row">
        <div class="col-md-4 border-right trans">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><i class="bi bi-person-circle avatar"></i>
                <span class="font-weight-bold">{{Auth::user()->fName}} {{Auth::user()->lName}}</span>
                <span class="text-black-50">{{Auth::user()->user_id}}</span>
            </div>
            <div id="eDiv" style="color: #9a0404; font-weight: bold; font-family: 'Lucida Fax'; padding-top: 30%; text-align: center; display: none"></div>
            @if($errors->any())
                <div  id="errorDiv" style="color: #9a0404; font-weight: bold; font-family: 'Lucida Fax'; padding-top: 30%; text-align: center">ERROR: {{ $errors->first() }}</div>
            @endif
            @if(\Session::has('msg'))
                <div id="feedBack" style="color: #6b2e1f; font-weight: bold; font-family: 'Lucida Bright'; padding-top: 30%; text-align: center">ALERT: Your profile have been updated successfully</div>
            @endif
        </div>
        <div class="col-md-8 trans">
            <form method="POST" class="p-3 py-5 trans" action="{{route('edit')}}" onsubmit="return validate()">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-3 trans">
                    <div onclick="location.href='home';" class="d-flex flex-row align-items-center back"><i class="fa fa-long-arrow-left mr-1 mb-1"></i>
                        <h6>Back to home</h6>
                    </div>
                    <h6 class="text-right editText">Edit Profile</h6>
                </div>
                <div class="row mt-2 trans">
                    <div class="col-md-6 trans"><input onclick="resetColor()" id="input1" name="fName" type="text" class="form-control" placeholder={{Auth::user()->fName}}></div>
                    <div class="col-md-6 trans"><input onclick="resetColor()" id="input2" name="lName" type="text" class="form-control" placeholder={{Auth::user()->lName}}></div>
                </div>
                <div class="row mt-3 trans">
                    <div class="col-md-6 trans"><input onclick="resetColor()" id="input3" name="old_password" type="password" class="form-control" placeholder="old password"></div>
                    <div class="col-md-6 trans"><input onclick="resetColor()" id="input4" name="birthDate" type="date" class="form-control" placeholder="birthday" value={{Auth::user()->birthDate}}></div>
                </div>
                <div class="row mt-3 trans">
                    <div class="col-md-6 trans"><input onclick="resetColor()" id="input5" name="new_password" type="password" class="form-control" placeholder="new password"></div>
                    <div class="col-md-6 trans"></div>
                </div>
                <div class="row mt-3 trans">
                    <div class="col-md-6 trans"><input onclick="resetColor()" id="input6" name="confirm_password" type="password" class="form-control" placeholder="confirm password"></div>
                </div>
                <div class="mt-5 text-right trans"><button id="save" class="btn btn-primary profile-button" type="submit" >Save Profile</button></div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SimpleLightbox plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script>
    function validate (){
        let in1 = document.getElementById('input1').value;
        let in2 = document.getElementById('input2').value;
        let in3 = document.getElementById('input3').value;
        let in4 = document.getElementById('input4').value;
        let in5 = document.getElementById('input5').value;
        let in6 = document.getElementById('input6').value;

        if (in1 === "" && in2 === "" && in3 === "" && in4 === "" && in5 === "" && in6 === ""){
            // alert("Nothing to update!");
            document.getElementById('eDiv').innerHTML = "ERROR: nothing to update!";
            document.getElementById('eDiv').style.display = 'block';
            return false;
        }
        if((in3 !== "" || in5 !== "" || in6 !== "") && (in3 === "" || in5 === "" || in6 === "")){
            // alert("To change your password fill the 3 fields");
            document.getElementById('input3').style.borderColor = '#9a0404';
            document.getElementById('input5').style.borderColor = '#9a0404';
            document.getElementById('input6').style.borderColor = '#9a0404';
            document.getElementById('eDiv').innerHTML = "ERROR: to change your password fill the 3 fields";
            document.getElementById('eDiv').style.display = 'block';
            return false;
        }
        return true;
    };
    function resetColor() {
        document.getElementById('input3').style.borderColor = '';
        document.getElementById('input5').style.borderColor = '';
        document.getElementById('input6').style.borderColor = '';
    }
</script>
</body>
</html>
