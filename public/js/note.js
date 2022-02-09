

window.addEventListener('click', event => {
    document.getElementById('allNotesButton').onclick = function () {
        while (document.getElementById("notesToBeShared").firstChild) {
            document.getElementById("notesToBeShared").removeChild(document.getElementById("notesToBeShared").firstChild);
        }
        while (document.getElementById("notesContainer").firstChild) {
            document.getElementById("notesContainer").removeChild(document.getElementById("notesContainer").firstChild);
        }
        document.getElementById("categoryNamePreview").innerText = 'All';
        $.ajax({
            type: "GET",
            url: "/getAllNotes",
            data: {},
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
    },
    document.getElementById('sortIcon').onclick = function () {
            while (document.getElementById("notesToBeShared").firstChild) {
                document.getElementById("notesToBeShared").removeChild(document.getElementById("notesToBeShared").firstChild);
            }
            while (document.getElementById("notesContainer").firstChild) {
                document.getElementById("notesContainer").removeChild(document.getElementById("notesContainer").firstChild);
            }
            $.ajax({
                type: "GET",
                url: "/sortNotesByTitle",
                data: {},
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
        },

    document.getElementById('share_note').onclick = function (){
        var _token = $("input[name='_token']").val();
        var note_id = document.getElementById('shared_note').value;
        var coll_username = document.getElementById('username').value;

        console.log(note_id);
        console.log(coll_username);
        $.ajax({
            type: "POST",
            url: "/shareNote",
            data: {
                _token:_token,
                note_id: note_id,
                coll_username: coll_username
            },
            success: function (res) {
                if (res === 1) {
                    document.getElementById('shareNotes').style.display='none';
                    document.getElementById('sharedForm').reset();
                } else {
                    alert("Invalid username");
                }
            }
        });
    }

    document.getElementById('addCat').onclick = function () {
        var _token = $("input[name='_token']").val();
        // var note_id = document.getElementById('shared_note').value;
        var category = document.getElementById('categoryCreation').value;
        if (category.length !== 0 ) {
            $.ajax({
                type: "POST",
                url: "/createNoteCategory",
                data: {
                    _token: _token,
                    category: category,
                },
                success: function (res) {
                    if (res === 1) {
                        categoryView(category);
                    } else {
                        alert("Category already exits")
                    }
                    console.log("worked");
                }
            });
        }
    }
})
window.addEventListener('load', (event) => {
    console.log("test");
    $.ajax({
        type: "GET",
        url: "/getCategories",
        data: {},
        success: function (res) {
            if (res) {
                $.each(res, function (key, value) {
                    categoryView(value.category);
                    console.log(value);
                });
            }
        }
    });
});
