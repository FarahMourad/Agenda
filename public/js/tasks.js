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


window.addEventListener('click', event => {
    $('#addTask').on("click", function () {
        let title = document.getElementById('taskTitle').value;
           let category = document.getElementById('cate').value;
           let description = document.getElementById('des').value;
           let deadline = document.getElementById('deadline').value;
           let completed = document.getElementById('completeTask').$(this).prop('checked');

           console.log(title);
        console.log(category);
        console.log(description);
        console.log(deadline);
        console.log(completed);
        if (document.getElementById('taskTitle').value.length != 0){
            var _token = $("input[name='_token']").val();
            $.ajax({
                type: "POST",
                url: "/addTask",
                data: {
                    _token:_token,
                    title: document.getElementById('taskTitle').value,
                    category: document.getElementById('cate').value,
                    description: document.getElementById('des').value,
                    deadline: document.getElementById('deadline').value,
                    completed: document.getElementById('completeTask').$(this).prop('checked')
                },
                success: function(res) {
                }
            });
        }
    })
});
