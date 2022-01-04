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
                            <a class="nav-link" onclick="show('allNotes');noteView();">All</a>
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
{{--                {{Auth::user()->user_id}}--}}
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
            <div id="shareNotes" style="display: none" class="container-fluid px-4">
                <br>
                <form id="sharingNotes">
                <label for="userShared">Collaborator</label>
                <input name="username" placeholder="Username" required>
                <button type="submit">Share</button>
                <br><br>
                </form>
                <button onclick="shareNotesView()">fuck</button>
            </div>
            <div id="editNotes" style="display: none" class="container-fluid px-4">
                <div style="cursor: pointer; width: 70px" onclick="cancel()">
                    <p><u>< Cancel</u></p>
                </div>
                <div class="card card-margin">
                    <form>
                    <input class="noteContent" id="noteTitle" style="height: 50px;" placeholder="Note Title" maxlength="100" required>
                    <textarea class="noteContent" id="noteContent" style="height: 400px; border-bottom: none" placeholder="Note Content" required></textarea>
                    <div>
                        <label style="margin-left: 20px" for="cat">Category</label>
                        <select style="width: 150px" name="cat" id="cat">
                            <option value="Defualt">Default</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" onsubmit="" class="btn btn-sm btn-flash-border-success" style="float: right; ">SAVE NOTE</button>
                    </div>
                    </form>
                </div>
            </div>
            <div id="allNotes" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4" style="width: 400px;display: inline-block">Notes</h1>
{{--                <input type="button" value="Create Category" style="display: inline-block">--}}
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">All</li>
                </ol>
                <div  style="float: right;margin-right: 100px">
                    <form>
                    <label for="categoryCreation">Create Category:</label>
                    <input type="text" name="category" id="categoryCreation" placeholder="Category Title" required>
                    <input type="submit" value="Create">
                    </form>
                </div>
                <br>
                <div style="cursor: pointer ;margin-right: 100px" onclick="show('editNotes')" >
                    <svg style="display: inline-block" class="svg-inline--fa fa-sticky-note fa-w-14" aria-hidden="true" focusable="false" data-prefix="far" data-icon="sticky-note" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M448 348.106V80c0-26.51-21.49-48-48-48H48C21.49 32 0 53.49 0 80v351.988c0 26.51 21.49 48 48 48h268.118a48 48 0 0 0 33.941-14.059l83.882-83.882A48 48 0 0 0 448 348.106zm-128 80v-76.118h76.118L320 428.106zM400 80v223.988H296c-13.255 0-24 10.745-24 24v104H48V80h352z"></path></svg>
                    <p style="display: inline-block" class="breadcrumb-item active" > Add new note</p>
                </div>
                <div class="container" >
                    <div class="row" id="notesContainer">
                    </div>
                </div>
                <div>
                    <input type="button" value="Share Notes" style="background: inherit;border: none; float: right;" onclick="show('shareNotes')" >
                </div>
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
                    <i class="fas fa-eraser" style="margin-right: 10px; color: #801818; cursor: pointer"></i><li class="breadcrumb-item diaryChoices">Erase Memories    </li>
                    <i class="fas fa-star" style="margin-right: 10px; margin-left: 20px; color: #efa315; cursor: pointer"></i><li class="breadcrumb-item diaryChoices"> Go To Bookmarked    </li>
                    <i class="fas fa-save" onclick="return saveDiary()" id="save1" style="margin-right: 10px; margin-left: 20px; color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity))"></i><li id="save2" style="cursor: default" onclick="return saveDiary()" class="breadcrumb-item diaryChoices"> Save Changes    </li>
                    <li class="breadcrumb-item diaryChoices" style="margin-left: 20px">Go To: </li><input type="number" id="goto">
                </ol>
                <div>
                    <div style="display: inline-block; float: left; height: 500px"><i class="fas fa-chevron-left arr" id="leftArrow"></i></div>
                    <div style="display: inline-block; float: left; margin-left: 550px; margin-right: 10px">
                        <div class="dBook">
                            <input type="checkbox" name="flip" id="flip" class="flipper" style="display: none">
                            <label class="dCover" for="flip">
                                <img src="../assets/img/12.jpg">
                                <h2 style="font-family: 'Lucida Handwriting'; text-align: center">Secret Diary</h2>
                            </label>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="dPage">
                                <textarea name="" id="page-back" rows="17" maxlength="510" disabled></textarea>
                                <footer style="width: 350px; justify-content: center; transform: scale(-1, 1); cursor: default">
                                    <a>1</a>
                                    <i class="fas fa-star" id="star1" style="float: right; margin-right: 20px; color: #b9b1a1; cursor: pointer"></i>
                                </footer>
                            </div>
                            <div class="last-dPage">
                                <textarea name="" id="page-front" rows="17" maxlength="510" disabled></textarea>
                                <footer style="width: 350px; justify-content: center; cursor: default">
                                    <a>2</a>
                                    <i class="fas fa-star" id="star2" style="float: right; margin-right: 20px; color: #b9b1a1; cursor: pointer"></i>
                                </footer>
                            </div>
                            <div class="back-dCover"></div>
                        </div>
                    </div>
                    <div style="display: inline-block; float: right; height: 500px; margin-right: 20px"><i class="fas fa-chevron-right arr" id="leftArrow"></i></div>
                    <footer style="position: fixed;bottom: 0; width: 100%">
                        <a id="addPage" class="editDiary"><i class="fas fa-pencil-alt" style="margin-right: 10px"></i>Have More Adventures?</a>
                    </footer>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/homeScript.js"></script>
<script src="js/diary.js"></script>
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
    function cancel(){
        var result = confirm("Unsaved data will be lost. Are you sure you want to proceed?");
        if (result) {
            show('allNotes');
        }
    }
    function categoryView(){
        var categoryContainer = document.getElementById("cat");
        var option = document.createElement("option");
        option.innerText="Sad";
        categoryContainer.insertBefore(option,null);
    }
    function shareNotesView(){
        var form = document.getElementById("sharingNotes");
        var article = document.createElement("article");
        var noteShared= document.createElement("input");
        var div = document.createElement("div");
        noteShared.required=true;
        div.innerText="Note";
        noteShared.type="radio";
        noteShared.name="category";
        // noteShared.required;
        article.className="feature1";
        article.insertBefore(noteShared,null);
        article.insertBefore(div,null);
        form.insertBefore(article,null);
    }
    function noteView(){
        var notesContainer = document.getElementById("notesContainer");
        var col = document.createElement("div");
        col.className="col-lg-4";
        var card = document.createElement("div");
        card.className="card card-margin";
        var cardHeader = document.createElement("div");
        cardHeader.className="card-header no-border";
        var cardBody = document.createElement("div");
        cardBody.className="card-body pt-0";
        var body = document.createElement("div");
        body.className="widget-49";
        var view = document.createElement("div");
        view.className="widget-49-meeting-action";
        var cardTitleHeader = document.createElement("h5");
        cardTitleHeader.className="card-title";
        cardTitleHeader.innerHTML="MOM";
        cardHeader.style="width:50%;";
        var pinDiv= document.createElement("div");
        var pin= document.createElement("svg");
        pinDiv.style= "float:right;cursor:pointer";
        pinDiv.onclick=function (){

        }
        pin.style= "float:right";
        pin.xmlns="http://www.w3.org/2000/svg";
        pin.width='16';
        pin.height='16';
        pin.className="bi bi-pin";
        pin.fill="currentColor";
        pin.viewBox="0 0 16 16";
        var path = document.createElement("path");
        path.d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A5.921 5.921 0 0 1 5 6.708V2.277a2.77 2.77 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354zm1.58 1.408-.002-.001.002.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a4.922 4.922 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a4.915 4.915 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.775 1.775 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14c.06.1.133.191.214.271a1.78 1.78 0 0 0 .37.282z";
        pin.insertBefore(path,null);
        var noteContent = document.createElement("p");
        noteContent.className="widget-49-pro-title";
        noteContent.style="text-overflow: ellipsis; overflow-y:hidden;height: 290px;width:100%;";
        noteContent.innerHTML="PRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera modulePRO-027865 Opera module"
        var viewNote = document.createElement("div");
        viewNote.innerHTML="VIEW NOTE";
        viewNote.className="btn btn-sm btn-flash-border-success";
        viewNote.onclick=function (){
            show('editNotes');
        }
        const self= this;
        var deleteButton =document.createElement("svg");
        deleteButton.style= "float:left;cursor:pointer";
        deleteButton.onclick=function (){
            notesContainer.removeChild(col);
        }
        deleteButton.xmlns="http://www.w3.org/2000/svg";
        deleteButton.width='16';
        deleteButton.height='16';
        deleteButton.className="bi bi-trash-fill";
        deleteButton.fill="currentColor";
        deleteButton.viewBox="0 0 16 16";
        var deletePath = document.createElement("path");
        deletePath.d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"
        deleteButton.insertBefore(deletePath,null);
        view.insertBefore(deleteButton,null);
        view.insertBefore(viewNote,null);
        body.insertBefore(noteContent,null);
        body.insertBefore(view,null);
        cardBody.insertBefore(body,null);
        cardHeader.insertBefore(cardTitleHeader,null);
        pinDiv.insertBefore(pin,null);
        cardHeader.insertBefore(pinDiv,null);
        card.insertBefore(cardHeader,null);
        card.insertBefore(cardBody,null);
        col.insertBefore(card,null);
        notesContainer.insertBefore(col,null);

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
