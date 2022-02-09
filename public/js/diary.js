window.addEventListener('click', event => {
    $('#star1').on("click", function () {
        if (document.getElementById('page-back').value.length != 0){
            document.getElementById('star1').style.color = '#efa315';
            document.getElementById('star2').style.color = '#b9b1a1';
            var _token = $("input[name='_token']").val();
            $.ajax({
                type: "POST",
                url: "/bookmarkPage",
                data: {
                    _token:_token,
                    page_no: document.getElementById('leftNo').innerText,
                },
                success: function(res) {
                }
            });
        }
    })

    $('#star2').on("click", function () {
        if (document.getElementById('page-back').value.length != 0){
            document.getElementById('star2').style.color = '#efa315';
            document.getElementById('star1').style.color = '#b9b1a1';
            var _token = $("input[name='_token']").val();
            $.ajax({
                type: "POST",
                url: "/bookmarkPage",
                data: {
                    _token:_token,
                    page_no: document.getElementById('rightNo').innerText,
                },
                success: function(res) {
                }
            });
        }
    })

    $('#fade').on("click", function () {
        document.getElementById('light').style.display='none';
        document.getElementById('light_share_task').style.display='none';
        document.getElementById('light_task_cat').style.display='none';
        document.getElementById('fade').style.display='none';
    })
});

$('#go').on("click",function(){
    var pageNo = document.getElementById('goto').value;
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
});

$('#loader').on("click", function () {
    document.getElementById('page-back').value = "";
    document.getElementById('page-front').value = "";
    $.ajax({
        type: "GET",
        url: "/getDiary",
        data: {},
        success: function(res) {
            if (res) {
                // console.log(res.left_page);
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
});






