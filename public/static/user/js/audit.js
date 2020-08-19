function hideAll(){
    $('#info').hide();
    $('#rating').hide();
    $('#mate').hide();
    $('#overdue').hide();
    $('#result').hide();
    $('#record').hide();
    $('#audit').hide();
}

function show_info(){
    hideAll();
    $('#info').show();
}
show_info();

function show_rating(){
    hideAll();
    $('#rating').show();
}

function show_mate() {
    hideAll();
    $('#mate').show();
}

function show_overdue() {
    hideAll();
    $('#overdue').show();
}
function show_result() {
    hideAll();
    $('#result').show();
}

function show_audit() {
    hideAll();
    $('#audit').show();
}
function show_record() {
    hideAll();
    $('#record').show();
}

$('.show-move li').on('click', function () {
    let i = $(this).data('index');
    console.log($(this).siblings());
    $(this).siblings().removeClass('gray');
    $(this).addClass('gray');
    move(i, true)
})


window.addEventListener('DOMContentLoaded', function () {
    var galley = document.getElementById('galley');
    new Viewer(galley)
});