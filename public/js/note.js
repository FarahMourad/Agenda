

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

    document.getElementById('share').onsubmit = function (){
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
                coll_username, coll_username
            },
            success: function (res) {
                console.log("shared");
            }
        });
    }

})
