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

{{------------------------------Helpers------------------------------------}}
<div id="light" class="white_content"></div>
<div id="light_share_task" class="white_content">
    <span>Username: </span>
    <span><input type="text" style="margin-left: 10px; background-color: rgba(210,180,165,0.37); border: none; outline: none; color: #23282a"></span>
    <span><button class="saveTaskButton">Share</button></span>
</div>
<div id="light_task_cat" class="white_content">
    <span>Category: </span>
    <span><input type="text" style="margin-left: 10px; background-color: rgba(210,180,165,0.37); border: none; outline: none; color: #23282a"></span>
    <span><button class="saveTaskButton">Create</button></span>
</div>
<div id="fade" class="black_overlay"></div>
<div id="stepBody" style="width: 100%; font-size: 16px; display: none">
    <span style="width: 10%">1.</span>
    <input type="text" style="width: 80%; margin-left: 10px; background-color: transparent; border: none; outline: none">
    <span style="float: right; width: 10%">
        <i onclick="deleteStep()" class="bi bi-trash-fill selectedTaskIcons"></i>
        <input type="checkbox" class="chk">
    </span>
</div>
{{-------------------------------------------------------------------------}}

<div id="layoutSidenav">
    {{-----------------------------Side Nav Bar--------------------------------}}
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link collapsed" style="cursor: pointer" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Tasks
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" style="cursor: pointer" onclick="show('allTasks')">All</a>
                            <a class="nav-link" style="cursor: pointer" onclick="show('assignedToMeTasks')">Assigned to me</a>
                        </nav>
                    </div>
                    <a id="getCat" class="nav-link collapsed" style="cursor: pointer" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div  class="sb-nav-link-icon"><i class="far fa-sticky-note"></i></div>
                        Notes
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav id="notesCategories" class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" style="cursor: pointer" id="allNotesButton">All</a>
{{--                            <a class="nav-link" style="cursor: pointer" onclick="show('sharedWithMeNotes')">Shared with me</a>--}}
                        </nav>
                    </div>
                    <a class="nav-link" style="cursor: pointer" onclick="show('diaryDiv')">
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
    {{-------------------------------------------------------------------------}}


    {{----------------------------Container------------------------------------}}
    <div id="layoutSidenav_content">
        <main id="container">

            {{--------------------------------Tasks------------------------------------}}
            <div id="allTasks" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Tasks</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">All</li>
                </ol>
                <ol class="breadcrumb mb-4" style="">
                    <li class="breadcrumb-item taskBar">Sort by Title <i class="bi bi-sort-down taskIcons"></i></li>
                    <li class="breadcrumb-item taskBar">Sort by Deadline <i class="bi bi-sort-down taskIcons"></i></li>
                    <li onclick="createTask()" class="breadcrumb-item taskBar">Add Task <i class="bi bi-plus-square taskIcons"></i></li>
                    <li onclick="createCategory()" class="breadcrumb-item taskBar">Create Category <i class="bi bi-plus-square taskIcons"></i></li>
                    <li onclick="getPerformance()" class="breadcrumb-item taskBar">Performance <i class="bi bi-bar-chart-line-fill taskIcons"></i></li>
                </ol>
                <div class="row">

                    <div onclick="showSelectedTask()" class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body"><span>Task Title</span></div>
                            <div class="small" style="color: rgba(243,239,233,0.63); padding: 0 15px 15px"><span>Category</span><span style="float: right">Deadline</span></div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" style="text-decoration: none; cursor: pointer">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="assignedToMeTasks" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Tasks</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Assigned to me</li>
                </ol>
            </div>
            <div id="task" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Tasks</h1>
                <div class="task_window">
                    <div class="task_window_after">
                        <div style="width: 100%; border-bottom: #454748 1px solid; padding-bottom: 10px; margin-bottom: 10px">
                            <span>Task Title</span><span style="float: right">
                                <i onclick="deleteTask()" class="bi bi-trash-fill selectedTaskIcons"></i>
                                <i onclick="shareTaskB()" class="fas fa-share selectedTaskIcons"></i>
                                <i id="completeTask" class="bi bi-check-square-fill selectedTaskIcons"></i>
                            </span>
                        </div>

                        <div style="width: 100%; border-bottom: #454748 1px solid; padding-bottom: 10px; font-size: 14px; color: #23282a; margin-bottom: 10px">
                            <span>Deadline:</span><input type="date" style="margin-left: 11.5px; width: 167px; background-color: rgba(210,180,165,0.37); border: none; outline: none; color: #23282a"><br>
                            <span>Category:</span><input type="text" value="Uncategorized" placeholder="task category" style="margin-left: 10px; background-color: rgba(210,180,165,0.37); border: none; outline: none; color: #23282a"><br>
                        </div>

                        <div style="width: 100%; height: 150px; border-bottom: #454748 1px solid; padding-bottom: 10px; margin-bottom: 10px">
                            <span>Details</span><br>
                            <textarea placeholder="Enter task details....." style="width: 100%; font-size: 14px; height: 100px; outline: none; resize: none; border: none; background-color: rgba(210,180,165,0.37); color: #272c2d"></textarea>
                        </div>

                        <div style="color: #1a1e21; width: 100%; height: 200px; border-bottom: #454748 1px solid; margin-bottom: 10px">
                            <span>Steps</span><span style="float: right"><i onclick="addStep()" style="cursor: pointer" class="bi bi-plus-circle-fill"></i></span><br>
                            <div id="stepsContainer" style="width: 100%; height: 160px; overflow: auto; background-color: rgba(210,180,165,0.37)">


                            </div>
                        </div>
                        <div style="padding: 10px 0 10px 10px; width: 100%">
                            <button class="saveTaskButton">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-------------------------------------------------------------------------}}


            {{--------------------------------Notes------------------------------------}}
            <div id="shareNotes" style="z-index: 1000;width:100%;height:100%;position: fixed;display: none;background: rgba(0,0,0,0.5);" class="container-fluid px-4">
                <br>
                <div style="padding:15px;z-index: 1001;margin-left:350px;margin-top:0px;width: 500px;height: 500px;  overflow-y: scroll; " class="card card-margin">
                <form id="sharedForm" class="sharedForm" >
                    <button style="background-color: #4a9a71;width: 30px;height: 30px;border-radius: 50% ;border:none;float: right;cursor: pointer"
                            onclick="var result = confirm('Are you sure you want to discard changes?');
                        if (result) {
                          document.getElementById('shareNotes').style.display='none';
                          document.getElementById('sharedForm').reset();
                        }
                         ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                    <label style="font-weight: lighter;margin-right: 5px" for="userShared">COLLABORATOR</label>
                    <input type="text" style="display: none" id="shared_note">
                    <input class="categoryTitle" id="username" name="username" placeholder="Username" required>
                    <div class="btn btn-sm btn-flash-border-success" id="share_note">SHARE</div>
                    <br><br>
                    <div id="notesToBeShared">
                    </div>
                </form>
                </div>
            </div>
            <div id="addNotes" style="z-index: 1000;width:100%;height:100%;position: fixed;display: none;background: rgba(0,0,0,0.5);" class="container-fluid px-4">
                <br>
{{--                <div style="height:100%;opacity:0.5;background-color:#000000;" ></div>--}}
                {{--                <div style="cursor: pointer; width: 70px" onclick="cancel()">--}}
                {{--                    <p><u>< Cancel</u></p>--}}
                {{--                </div>--}}
                <div id="newNotte" style="padding:15px;z-index: 1001;margin-left:350px;margin-top:50px;width: 500px;" class="card card-margin">
                    <form id="noteForm" action="{{route('addNote')}}" method="POST">
                        @csrf
                        <button style="background-color: #4a9a71;width: 30px;height: 30px;border-radius: 50% ;border:none;float: right;cursor: pointer"
                                onclick="var result = confirm('Are you sure you want to discard changes?');
                        if (result) {
                          document.getElementById('noteForm').reset();
                          document.getElementById('addNotes').style.display='none';
                        }
                         ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                        </button>
                        <input name="title" class="noteContent" id="addedNoteTitle" style="height: 50px;" placeholder="Title" maxlength="100" required>
                        <textarea name="note_content" class="noteContent" id="addedNoteContent" style="height: 400px; border-bottom: none" placeholder="Content" required></textarea>
                        <div>
                            <label class="btn btn-sm btn-flash-border-success" style="cursor:none;margin-left: 20px" for="cat">Category</label>
                            <select class="btn btn-default dropdown-toggle" name="category" style="width: 150px; background-color: #4a9a71" name="cat" id="cat">
                                <option style="background-color: #c4d8da" selected value>Uncategorized</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-flash-border-success" style="float: right; ">ADD NOTE</button>
                        </div>
                    </form>

                </div>
            </div>
            <div id="editNotes" style="z-index: 1000;width:100%;height:100%;position: fixed;display: none;background: rgba(0,0,0,0.5);" class="container-fluid px-4">
                <br>

                <div style="padding:15px;z-index: 1001;margin-left:350px;margin-top:50px;width: 500px;" class="card card-margin">
                    <form action="{{route('editNote')}}" method="POST">
                        @csrf
                        <button style="background-color: #4a9a71;width: 30px;height: 30px;border-radius: 50% ;border:none;float: right;cursor: pointer"
                                onclick="var result = confirm('Are you sure you want to discard changes?');
                        if (result) {
                          document.getElementById('editNotes').style.display='none';
                        }
                         ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                        <input name="title" class="noteContent" id="noteTitle" style="height: 50px;" placeholder="Note Title" maxlength="100" required>
                        <input id="note_id" type="text" name="note_id" style="display: none">
                        <textarea name="note_content" class="noteContent" id="noteContent" style="height: 400px; border-bottom: none" placeholder="Note Content" required></textarea>
                        <div>
                            <label class="btn btn-sm btn-flash-border-success" style="cursor:none;margin-left: 20px" for="cat">Category</label>
                            <select class="btn btn-default dropdown-toggle" name="category" style="width: 150px; background-color: #4a9a71" name="cat" id="editCat">
                                <option style="background-color: #c4d8da" selected value>Uncategorized</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-flash-border-success" style="float: right; ">EDIT NOTE</button>
                        </div>
                    </form>

                </div>
            </div>
            <div id="allNotes" style="display: none" class="container-fluid px-4">
                <h1  class="mt-4" style="width: 400px;display: inline-block">Notes</h1>
                {{--                <input type="button" value="Create Category" style="display: inline-block">--}}
                <ol class="breadcrumb mb-4">
                    <li id="categoryNamePreview" class="breadcrumb-item active">All</li>
                </ol>

                <div  style="float: right;margin-right: 100px">
                    <form>
                        <input class="categoryTitle" type="text" name="category" id="categoryCreation" placeholder="Category Title" required>
                        <input id="addCat" class="btn btn-sm btn-flash-border-success"
                               style="width: 140px"
                               value="CREATE CATEGORY">
                    </form>
                </div>
                <br>
{{--                <div style="cursor: pointer ;width:120px;  margin-right: 100px" onclick="show('addNotes')" >--}}
                    <div style="cursor: pointer ;width:140px;  margin-right: 100px" onclick="document.getElementById('addNotes').style.display='block';" >
                    <svg style="display: inline-block" class="svg-inline--fa fa-sticky-note fa-w-14" aria-hidden="true" focusable="false" data-prefix="far" data-icon="sticky-note" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M448 348.106V80c0-26.51-21.49-48-48-48H48C21.49 32 0 53.49 0 80v351.988c0 26.51 21.49 48 48 48h268.118a48 48 0 0 0 33.941-14.059l83.882-83.882A48 48 0 0 0 448 348.106zm-128 80v-76.118h76.118L320 428.106zM400 80v223.988H296c-13.255 0-24 10.745-24 24v104H48V80h352z"></path></svg>
                    <p style="display: inline-block" class="breadcrumb-item active" > ADD NEW NOTE</p>
                </div>
                <div id="sortIcon" style="cursor: pointer;width: 20px" onclick="" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down" viewBox="0 0 16 16">
                        <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
                    </svg>
                </div>
                <br>
                <div class="container" >
                    <div class="row" id="notesContainer">

                    </div>
                    <form method="POST" style="display: none" id="deleteNote" name="deleteNote">
                        @csrf
                        <input type="number" name="note_id" id="deletedNote">
                    </form>
                </div>
                <div>
                    <input type="button" class="shareNotesB" value="Share Notes" style="background: inherit;border: none; float: right;" onclick="document.getElementById('shareNotes').style.display='block';" >
                </div>
            </div>
            <div id="sharedWithMeNotes" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Notes</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Shared with me</li>
                </ol>
            </div>
            {{-------------------------------------------------------------------------}}


            {{--------------------------------Diary------------------------------------}}
            <div id="diaryDiv" style="display: none" class="container-fluid px-4">
                <h1 class="mt-4">Diary</h1>
                <ol class="breadcrumb mb-4">
                    <i onclick="deleteDiary()" class="fas fa-eraser" style="margin-right: 10px; color: #801818; cursor: pointer"></i><li onclick="deleteDiary()" class="breadcrumb-item diaryChoices">Erase Memories    </li>
                    <i class="fas fa-star" style="margin-right: 10px; margin-left: 20px; color: #efa315; cursor: pointer" onclick="getBook()"></i><li onclick="getBook()" class="breadcrumb-item diaryChoices"> Go To Bookmarked    </li>
                    <i class="fas fa-save" onclick="return saveDiary()" id="save1" style="margin-right: 10px; margin-left: 20px; color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity))"></i><li id="save2" style="cursor: default" onclick="return saveDiary()" class="breadcrumb-item diaryChoices"> Save Changes    </li>
                    <li id="go" class="breadcrumb-item diaryChoices" style="margin-left: 20px">Go To: </li><input type="number" id="goto">
                </ol>
                <div>
                    <div style="display: inline-block; float: left; height: 500px"  onclick="goLeft()"><i class="fas fa-chevron-left arr" id="leftArrow"></i></div>
                    <div style="display: inline-block; float: left; margin-left: 550px; margin-right: 10px">
                        <div class="dBook">
                            <input type="checkbox" name="flip" id="flip" class="flipper" style="display: none">
                            <label id="loader" class="dCover" for="flip">
                                <img src="../assets/img/12.jpg">
                                <h2 style="font-family: 'Lucida Handwriting'; text-align: center">Secret Diary</h2>
                            </label>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="dPage"></div>
                            <div class="dPage">
                                <textarea name="" id="page-back" rows="17" maxlength="510" readonly></textarea>
                                <div style="width: 350px; justify-content: center; transform: scale(-1, 1); cursor: default">
                                    <a id="leftNo">1</a>
                                    <i class="fas fa-star" id="star1" style="float: right; margin-right: 20px; color: #b9b1a1; cursor: pointer"></i>
                                </div>
                            </div>
                            <div class="last-dPage">
                                <textarea name="" id="page-front" rows="17" maxlength="510" readonly></textarea>
                                <div style="width: 350px; justify-content: center; cursor: default">
                                    <a id="rightNo">2</a>
                                    <i class="fas fa-star" id="star2" style="float: right; margin-right: 20px; color: #b9b1a1; cursor: pointer"></i>
                                </div>
                            </div>
                            <div class="back-dCover"></div>
                        </div>
                    </div>
                    <div onclick="goRight()" style="display: inline-block; float: right; height: 500px; margin-right: 20px"><i class="fas fa-chevron-right arr" id="rightArrow"></i></div>
                    <footer style="position: fixed;bottom: 0; width: 100%">
                        <a id="addPage" class="editDiary"><i class="fas fa-pencil-alt" style="margin-right: 10px"></i>Have More Adventures?</a>
                    </footer>
                </div>
            </div>
            {{-------------------------------------------------------------------------}}
        </main>
    </div>
    {{-------------------------------------------------------------------------}}
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/homeScript.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="js/diary.js"></script>
<script src="js/newNote.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script>
    function deleteTask() {
        show('allTasks');
        document.getElementById('light').innerHTML = "This task has been deleted successfully";
        document.getElementById('light').style.display = "block";
        document.getElementById('fade').style.display = "block";
    }
    function shareTaskB() {
        document.getElementById('light_share_task').style.display = "block";
        document.getElementById('fade').style.display = "block";
    }
    var c = 0;
    $("#completeTask").click(function () {
        if(c == 0){
            $("input:checkbox").prop('checked', true);
            c = 1;
        }else{
            $("input:checkbox").prop('checked', false);
            c = 0;
        }

    });

    const elem = document.getElementById('stepBody');
    function addStep() {
        const divList = document.getElementById('stepsContainer').children;
        console.log(divList);

        elem.children[0].innerHTML = `${divList.length + 1}.`;
        elem.style.display = 'block';
        elem.id = `stepBody${divList.length + 1}`;
        console.log(elem);

        const parent = document.getElementById('stepsContainer');
        parent.appendChild(elem);
    }
    function deleteStep() {

    }
    function saveTask() {
        show('allTasks');
        document.getElementById('light').innerHTML = "This task has been saved successfully";
        document.getElementById('light').style.display = "block";
        document.getElementById('fade').style.display = "block";
    }
    function getPerformance() {
        document.getElementById('light').innerHTML = "Your Performance is: ";
        document.getElementById('light').style.display = "block";
        document.getElementById('fade').style.display = "block";
    }
    function createTask() {
        show('task');
    }
    function showSelectedTask() {
        show('task');
    }
    function createCategory() {
        document.getElementById('light_task_cat').style.display = "block";
        document.getElementById('fade').style.display = "block";
    }


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
    function categoryView(category){
        var categoryContainer = document.getElementById("cat");
        var categoryContainer2 = document.getElementById("editCat");
        var option = document.createElement("option");
        option.innerText=category;
        categoryContainer.insertBefore(option,null);
        categoryContainer2.insertBefore(option,null);
        var aCategory = document.createElement("a");
        aCategory.id=category;
        aCategory.innerText=category;
        aCategory.className="nav-link";
        aCategory.onclick=function (){
            document.getElementById("categoryNamePreview").innerHTML=category;
            document.getElementById('notesContainer').innerHTML = "";
            $.ajax({
                type: "GET",
                url: "/getCategoryNotes",
                data: {
                    category: category
                },
                success: function (res) {
                    if (res) {
                        $.each(res, function (key, value) {
                            noteView(value);
                            shareNotesView(value);
                            console.log(value);
                        });
                        show('allNotes');
                    }
                }
            });
        }
        var categories =document.getElementById("notesCategories");
        categories.insertBefore(aCategory,null);

    }
    function shareNotesView(note){
        var form = document.getElementById("notesToBeShared");
        var article = document.createElement("article");
        var noteShared= document.createElement("input");
        article.style="border-radius: 50%;";
        noteShared.style="border-radius: 50%;";
        noteShared.className="sharedNote";
        var div = document.createElement("div");
        noteShared.required=true;
        article.onclick=function (){
            document.getElementById("shared_note").value=note.note_id;
        }
        div.innerText=note.title;
        noteShared.id=note.note_id;
        noteShared.type="radio";
        noteShared.name="selected";
        // noteShared.required;c
        article.className="feature1";
        article.insertBefore(noteShared,null);
        article.insertBefore(div,null);
        form.insertBefore(article,null);

    }
    var num=0;
    function noteView(note){
        var notesContainer = document.getElementById("notesContainer");
        var col = document.createElement("div");
        col.className="col-md-4 col-sm-6 content-card";
        col.style="width:300px";
        col.id = note.note_id + "";
        var div2 = document.createElement("div");
        div2.className="card-big-shadow";
        var div3 = document.createElement("div");
        div3.className="card card-just-text";
        div3.setAttribute("data-background","color");
        if(num%6==0)
            div3.setAttribute("data-color","blue");
        else if(num%6==1)
            div3.setAttribute("data-color","green");
        else if(num%6==2)
            div3.setAttribute("data-color","yellow");
        else if(num%6==3)
            div3.setAttribute("data-color","brown");
        else if(num%6==4)
            div3.setAttribute("data-color","purple");
        else if(num%6==5)
            div3.setAttribute("data-color","orange");
        num++;
        div3.setAttribute("data-radius","none");
        var div4 = document.createElement("div");
        div4.className="content";
        var div5 = document.createElement("div");
        div5.style="font-size: 25px;height:35px;text-overflow: ellipsis; overflow:hidden";
        div5.innerHTML=note.title;
        var div6 = document.createElement("div");
        var description= document.createElement("p");
        description.className="description";
        description.style="text-overflow: ellipsis; overflow-y:hidden;height: 195px;width:100%;";
        description.innerHTML=note.content;


        //pin
        var pinned = note.pinned;
        var pin= document.createElement("svg");
        pin.style= "float:right; width:16px;cursor:pointer";
        pin.xmlns="http://www.w3.org/2000/svg";
        pin.width='16';
        pin.height='16';
        pin.float='right';
        pin.fill="currentColor";
        pin.viewBox="0 0 16 16";
        if (pinned==true)
            pin.className="bi bi-pin-fill";
        else
            pin.className="bi bi-pin";
        var path = document.createElement("path");
        path.d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A5.921 5.921 0 0 1 5 6.708V2.277a2.77 2.77 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354zm1.58 1.408-.002-.001.002.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a4.922 4.922 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a4.915 4.915 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.775 1.775 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14c.06.1.133.191.214.271a1.78 1.78 0 0 0 .37.282z";
        pin.insertBefore(path,null);

        pin.onclick=function (e){
            if (pinned === 1){ //pin style
                pinned = false;
                pin.className="bi bi-pin";
            }
            else {
                pinned = true;
                pin.className="bi bi-pin-fill";
            }
            e.preventDefault();
            var _token = $("input[name='_token']").val();
            console.log(pinned);
            $.ajax({
                type: "POST",
                url: "{{route('pinNote')}}",
                data: {
                    note_id: note.note_id,
                    _token: _token,
                },
                success: function() {
                    console.log("success");
                }
            });
        }


        //view note
        var viewNote = document.createElement("div");
        viewNote.innerHTML="VIEW NOTE";
        viewNote.style="padding: 0.25rem 0.5rem; font-size: 0.875rem;float:right;margin-right:10px;margin-bottom:10px; cursor:pointer";
        viewNote.onclick=function (){

            document.getElementById("noteTitle").value= note.title;
            document.getElementById("noteContent").innerText= note.content;
            document.getElementById('editNotes').style.display='block';
            document.getElementById("note_id").value = col.id;
        }


        //delete note
        const self= this;
        var deleteButton =document.createElement("svg");
        deleteButton.style= "float:left;cursor:pointer;margin-left:10px";
        deleteButton.onclick=function (){
            var result = confirm("Are you sure you want to delete this note?");
            if (result) {
                document.getElementById("deletedNote").value = col.id;
                document.forms["deleteNote"].action= "{{route('deleteNote')}}";
                document.forms["deleteNote"].submit();
                notesContainer.removeChild(col);
            }

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


        var view = document.createElement("div");
        view.insertBefore(deleteButton,null);
        view.insertBefore(viewNote,null);




        var br=document.createElement("br");
        div6.insertBefore(br,null);
        div6.insertBefore(description,null);
        div4.insertBefore(div5,null);

        div4.insertBefore(div6,null);
        div3.insertBefore(pin,null);
        div3.insertBefore(div4,null);
        div3.insertBefore(view,null);
        div2.insertBefore(div3,null);
        col.insertBefore(div2,null);

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
        num=0;
    }
    var editing = 0;
    var diarySaved = 0;
    $('#addPage').on("click", function () {
        document.getElementById('page-back').value = "";
        document.getElementById('page-front').value = "";
        $.ajax({
            type: "GET",
            url: "/getDiary",
            data: {},
            success: function(res) {
                if (res) {
                    editing = 1;
                    diarySaved = 0;
                    document.getElementById('page-back').readOnly = false;
                    document.getElementById('page-front').readOnly = false;
                    document.getElementById('star1').style.cursor = 'pointer';
                    document.getElementById('star2').style.cursor = 'pointer';
                    document.getElementById('save1').style.cursor = 'pointer';
                    document.getElementById('save2').style.cursor = 'pointer';

                    document.getElementById('page-back').value = res.left_content;
                    // console.log(document.getElementById('page-back').innerText);
                    document.getElementById('page-front').value = res.right_content;
                    document.getElementById('leftNo').innerText = res.left_page;
                    document.getElementById('rightNo').innerText = res.right_page;
                    if (res.left_bookmarked){
                        document.getElementById('star1').style.color = '#efa315';
                    }else {
                        document.getElementById('star1').style.color = '#b9b1a1';
                    }
                    if (res.right_bookmarked){
                        document.getElementById('star2').style.color = '#efa315';
                    }else {
                        document.getElementById('star2').style.color = '#b9b1a1';
                    }
                }
            }
        });
    })
    function saveDiary() {
        if (editing == 1 && diarySaved == 0) {
            editing = 0;
            diarySaved = 1;
            document.getElementById('page-back').readOnly = true;
            document.getElementById('page-front').readOnly = true;
            document.getElementById('save1').style.cursor = 'default';
            document.getElementById('save2').style.cursor = 'default';
            var _token = $("input[name='_token']").val();
            var left = document.getElementById('page-back').value;
            var right = document.getElementById('page-front').value;
            if (left.length == 0 && right.length != 0){
                left = right;
                right = null;
            }
            $.ajax({
                type: "POST",
                url: "/setContent",
                data: {
                    _token:_token,
                    left_page: document.getElementById('leftNo').innerText,
                    right_page: document.getElementById('rightNo').innerText,
                    left_page_content: left,
                    right_page_content: right,
                },
                success: function (res) {
                }
            });
        }
    }
    function deleteDiary() {
        var _token = $("input[name='_token']").val();

        $.ajax({
            type: "POST",
            url: "/deleteDiary",
            data: {
                _token:_token,
            },
            success: function (res) {
                document.getElementById('page-front').value = "";
                document.getElementById('page-back').value = "";
                document.getElementById('leftNo').innerText = "1";
                document.getElementById('rightNo').innerText = "2";
                document.getElementById('star1').style.color = '#b9b1a1';
                document.getElementById('star2').style.color = '#b9b1a1';
                document.getElementById('light').innerHTML = "Your diary has been deleted successfully";
                document.getElementById('light').style.display = "block";
                document.getElementById('fade').style.display = "block";
            }
        });
    }
    function getBook() {
        $.ajax({
            type: "GET",
            url: "/getBook",
            data: {},
            success: function(res) {
                if (res) {
                    console.log(res);
                    if (res.left_content == null && res.right_content == null && res.left_page == null && res.right_page == null){
                        document.getElementById('light').innerHTML = "Didn't found any bookmarked page";
                        document.getElementById('light').style.display = "block";
                        document.getElementById('fade').style.display = "block";
                    }else{
                        document.getElementById('page-back').value = res.left_content;
                        document.getElementById('page-front').value = res.right_content;
                        document.getElementById('leftNo').innerText = res.left_page;
                        document.getElementById('rightNo').innerText = res.left_page + 1;
                        console.log(res.left_bookmarked)
                        console.log(res.right_bookmarked)

                        if (res.left_bookmarked){
                            document.getElementById('star1').style.color = '#efa315';
                        }else {
                            document.getElementById('star1').style.color = '#b9b1a1';
                        }
                        if (res.right_bookmarked){
                            document.getElementById('star2').style.color = '#efa315';
                        }else {
                            document.getElementById('star2').style.color = '#b9b1a1';
                        }
                    }
                }
            }
        });
    }
    function goLeft(){
        console.log('left');
        var pageNo = document.getElementById('leftNo').innerText;
        pageNo = parseInt(pageNo) - 1;
        console.log(pageNo);
        if (pageNo <= 0) {
            document.getElementById('light').innerHTML = "Page number is invalid";
            document.getElementById('light').style.display = "block";
            document.getElementById('fade').style.display = "block";
        }else{
            $.ajax({
                type: "GET",
                url: "/searchPage",
                data: {
                    page_no: pageNo,
                },
                success: function(res) {
                    if (res) {
                        if (res.left_content == null && res.right_content == null && res.left_page == null && res.right_page == null && res.left_bookmarked == null && res.right_bookmarked == null){
                            document.getElementById('light').innerHTML = "Page number is invalid";
                            document.getElementById('light').style.display = "block";
                            document.getElementById('fade').style.display = "block";
                        }else {
                            document.getElementById('page-back').value = res.left_content;
                            document.getElementById('page-front').value = res.right_content;
                            document.getElementById('leftNo').innerText = res.left_page;
                            document.getElementById('rightNo').innerText = res.right_page;
                            if (res.left_bookmarked){
                                document.getElementById('star1').style.color = '#efa315';
                            }else {
                                document.getElementById('star1').style.color = '#b9b1a1';
                            }
                            if (res.right_bookmarked){
                                document.getElementById('star2').style.color = '#efa315';
                            }else {
                                document.getElementById('star2').style.color = '#b9b1a1';
                            }
                        }
                    }
                }
            });
        }
    };
    function goRight(){
        var pageNo = document.getElementById('rightNo').innerText;
        pageNo = parseInt(pageNo) + 1;
        console.log(pageNo);
        $.ajax({
            type: "GET",
            url: "/searchPage",
            data: {
                page_no: pageNo,
            },
            success: function(res) {
                if (res) {
                    if (res.left_content == null && res.right_content == null && res.left_page == null && res.right_page == null && res.left_bookmarked == null && res.right_bookmarked == null){
                        document.getElementById('light').innerHTML = "Page number is invalid";
                        document.getElementById('light').style.display = "block";
                        document.getElementById('fade').style.display = "block";
                    }else {
                        document.getElementById('page-back').value = res.left_content;
                        document.getElementById('page-front').value = res.right_content;
                        document.getElementById('leftNo').innerText = res.left_page;
                        document.getElementById('rightNo').innerText = res.right_page;
                        if (res.left_bookmarked){
                            document.getElementById('star1').style.color = '#efa315';
                        }else {
                            document.getElementById('star1').style.color = '#b9b1a1';
                        }
                        if (res.right_bookmarked){
                            document.getElementById('star2').style.color = '#efa315';
                        }else {
                            document.getElementById('star2').style.color = '#b9b1a1';
                        }
                    }
                }
            }
        });
    };

</script>
<script src="js/note.js"></script>
</body>
</html>
