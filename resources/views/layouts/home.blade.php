<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Agenda - Home</title>
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Font-awesome Icons-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link id="stylesheet" href="css/home.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="/">AGENDA</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href=""><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="setting"><i class="fas fa-user-cog"></i>  Settings</a></li>
                <li><a id="theme" class="dropdown-item" style="cursor: pointer"><i class="bi bi-cloud-sun-fill"></i>  Theme</a></li>
{{--                <form id="theme-form" method="POST" class="d-none">--}}
{{--                    @csrf--}}
{{--                </form>--}}

                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>  Logout</a>
                </li>
{{--                <li>{{Auth::user()->user_id}}</li>--}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Tasks
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" onclick="show('allTasks')">All</a>
                            <a class="nav-link" onclick="show('assignedToMeTasks')">Assigned to me</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="far fa-sticky-note"></i></div>
                        Notes
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" onclick="show('allNotes')">All</a>
                            <a class="nav-link" onclick="show('sharedWithMeNotes')">Shared with me</a>
                        </nav>
                    </div>
                    <a class="nav-link" onclick="show('diaryDiv')">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Diary
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                {{Auth::user()->user_id}}
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main id="container">
            <div id="allTasks" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Tasks</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">All</li>
                </ol>
{{--                <div class="row">--}}

{{--                    --}}{{--                    <div class="col-xl-3 col-md-6">--}}
{{--                    --}}{{--                        <div class="card bg-primary text-white mb-4">--}}
{{--                    --}}{{--                            <div class="card-body">Primary Card</div>--}}
{{--                    --}}{{--                            <div class="card-footer d-flex align-items-center justify-content-between">--}}
{{--                    --}}{{--                                <a class="small text-white stretched-link" href="#">View Details</a>--}}
{{--                    --}}{{--                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
{{--                    --}}{{--                            </div>--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                    </div>--}}
{{--                    --}}{{--                    <div class="col-xl-3 col-md-6">--}}
{{--                    --}}{{--                        <div class="card bg-warning text-white mb-4">--}}
{{--                    --}}{{--                            <div class="card-body">Warning Card</div>--}}
{{--                    --}}{{--                            <div class="card-footer d-flex align-items-center justify-content-between">--}}
{{--                    --}}{{--                                <a class="small text-white stretched-link" href="#">View Details</a>--}}
{{--                    --}}{{--                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
{{--                    --}}{{--                            </div>--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                    </div>--}}
{{--                    --}}{{--                    <div class="col-xl-3 col-md-6">--}}
{{--                    --}}{{--                        <div class="card bg-success text-white mb-4">--}}
{{--                    --}}{{--                            <div class="card-body">Success Card</div>--}}
{{--                    --}}{{--                            <div class="card-footer d-flex align-items-center justify-content-between">--}}
{{--                    --}}{{--                                <a class="small text-white stretched-link" href="#">View Details</a>--}}
{{--                    --}}{{--                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
{{--                    --}}{{--                            </div>--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                    </div>--}}
{{--                    --}}{{--                    <div class="col-xl-3 col-md-6">--}}
{{--                    --}}{{--                        <div class="card bg-danger text-white mb-4">--}}
{{--                    --}}{{--                            <div class="card-body">Danger Card</div>--}}
{{--                    --}}{{--                            <div class="card-footer d-flex align-items-center justify-content-between">--}}
{{--                    --}}{{--                                <a class="small text-white stretched-link" href="#">View Details</a>--}}
{{--                    --}}{{--                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>--}}
{{--                    --}}{{--                            </div>--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div id="assignedToMeTasks" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Tasks</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Assigned to me</li>
                </ol>
            </div>
            <div id="allNotes" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Notes</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">All</li>
                </ol>
            </div>
            <div id="sharedWithMeNotes" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Notes</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Shared with me</li>
                </ol>
            </div>
            <div id="diaryDiv" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Diary</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Secret Diary</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6"></div>
                    <div class="col-xl-3 col-md-6"></div>
                    <div class="col-md-6">
                        <div class="dBook">
                            <div class="dCover">
                                <img src="../assets/img/12.jpg">
                                <h2 style="font-family: 'Lucida Handwriting'; text-align: center">Secret Diary</h2>
                            </div>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="last-dPage">
                                <h2 style="font-family: 'Lucida Handwriting'">Secret Diary</h2>
                            </div>
                            <div class="back-dCover"></div>
                        </div>
                    </div>
{{--                    <div class="col-xl-3 col-md-6"></div>--}}
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/homeScript.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script>
    var theme = 1;
    function swap() {
        let sheet = document.getElementById('stylesheet').getAttribute('href');
        if (sheet === "css/homeDark.css"){
            theme = 1;
            document.getElementById("stylesheet").setAttribute("href", "css/home.css");
        }else {
            theme = 0;
            document.getElementById("stylesheet").setAttribute("href", "css/homeDark.css");
        }
    }

    $('#theme').click(function(e) {
        e.preventDefault();
        var _token = $("input[name='_token']").val();
        swap();
        $.ajax({
            type: "POST",
            url: "{{route('edit-theme')}}",
            data: {theme: theme, _token: _token},
            success: function() {
                console.log("success");
            }
        });
    });


    $(document).ready(function(){
        theme = '{{\Illuminate\Support\Facades\Auth::user()->theme}}';
        console.log(theme);
        if (theme == 1){
            console.log(theme);
            document.getElementById("stylesheet").setAttribute("href", "css/home.css");
        }else {
            document.getElementById("stylesheet").setAttribute("href", "css/homeDark.css");
        }
    });
    function show(id) {
        var divList = document.getElementById('container').children
        for (let i = 0; i < divList.length; i++) {
            divList[i].style.display = 'none';
        }
        document.getElementById(id).style.display = 'block';
    }
</script>
</body>
</html>
