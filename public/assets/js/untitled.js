// im praying for whoever wants to modify this 🙏🙏🙏

$('.go-top').click(function() {
    $('html, body').animate({scrollTop: '0'}, 700);
});

function waterAnimation() {
    var water = document.querySelectorAll('.water');
    const targetNode = document.getElementById('coding');
    const config = { className: 'panel aos-init' };

    if(document.getElementById('coding').classList.contains("aos-animate")) {
        anime({
            targets: water,
            top: [0, function(anim) {
                return '-1' + anim.getAttribute('data-level') + '%';
            }]
        });
        clearInterval(t);
    }
}
if (document.getElementById('coding'))
    var t=setInterval(waterAnimation,1000);

function buildForm() {
    let fields = document.getElementById("inputFields").value;
    let name = document.getElementById("inputTypeName").value;
    if (fields === '' || name === '' || fields.length < 2) return;

    let elemToChange = document.getElementById("form");
    let res = fields.split(",");
    let onclickAction = "postAndRefresh('form','/admin/ajax/manage/Type/add')";

    elemToChange.innerHTML = '';
    res.forEach(function (array, index) {
        elemToChange.innerHTML += '<div class="form-row"> <div class="col"><label class="col-form-label w-100">'+ array + '<input placeholder="'+ array +' input type (text,textarea,date etc.)"  type="text" name="fields['+ array +'][type]" id="inputNameR" class="form-control" required autofocus>' +
            'Unique field ?' +
            '<input type="checkbox" name="fields['+ array +'][settings][unique]" id="inputNameR" class="form-control">' +
            '</label></div> </div>'
    })

    elemToChange.innerHTML += '<input value="'+ name +'" hidden id="inputNameR" "type="text" name="name" required>' +
        '<button class="btn btn-primary btn-block" onclick="'+ onclickAction +'" type="button">Create</button>';
}

function postAndRefresh(formParentID, target)
{
    var element = document.getElementById(formParentID);
    var formData = new FormData(element);
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onload = function() {
        location.reload();
        return false;
    }
    xmlHttp.open("post", target);
    xmlHttp.send(formData);

}
let page = 1;

function getTypes(select, addCount = 0, nou = null)
{
    if (select.value != null) {
        let xmlHttp = new XMLHttpRequest();

        xmlHttp.open("GET", '/admin/ajax/getType/' + select.value + '/' + addCount);
        xmlHttp.send();
        xmlHttp.onload = function () {
            let field = '#field' + page++;

            let wrapper = '<div id="'+ field + '"> <button type="button" onclick="removeField(\''+ field +'\')" class="btn btn-primary float-right"> Remove Field </button> ' + xmlHttp.response + '</div>';

            if (nou)
                document.getElementById('ajaxChange').innerHTML = wrapper;
            else
                document.getElementById('ajaxChange').innerHTML += wrapper;
        };
    }
}
function removeField(id)
{
    let elem = document.getElementById(id);
    elem.parentNode.removeChild(elem);
}
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
