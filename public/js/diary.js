var editing = 0;
var diarySaved = 0;
window.addEventListener('click', event => {
    $('#star1').on("click", function () {
        document.getElementById('star1').style.color = '#efa315';
        document.getElementById('star2').style.color = '#b9b1a1';
    })

    $('#star2').on("click", function () {
        document.getElementById('star2').style.color = '#efa315';
        document.getElementById('star1').style.color = '#b9b1a1';
    })
});

$('#goto').on("keypress",function(){
    var pageNo = document.getElementById('goto').value;
    console.log(pageNo);
    if (pageNo <= 0)
        alert("Page number is not valid");
});

$('#addPage').on("click", function () {
    editing = 1;
    document.getElementById('page-back').disabled = false;
    document.getElementById('page-front').disabled = false;
    document.getElementById('save1').style.cursor = 'pointer';
    document.getElementById('save2').style.cursor = 'pointer';
})

function saveDiary() {

}
