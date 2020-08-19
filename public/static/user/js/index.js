/**
 * Created by Administrator on 2018/5/21.
 */

$(() => {
    const $left = $('#arrowLeft')
    const $right = $('#arrowRight')
    const $gallery = $('#gallery')
    const $show = $('#show')
    const $mask = $('#mask')

    const $add = $('#add')
    const $reduce = $('#reduce')
    const $hidden = $('#hidden')

    const $move = $('.move li')
    const len = $move.length

    const w = 1000

    let flag = true

    $move.each(function (i) {
    $(this).attr('data-index', i)
})

let move = (adiot, bool = false) => {
    if (!flag) {
        return false
    }
    flag = false
    let p = $gallery.position().left
    let n = p / 400
    let z = null
    if (bool) {
        z = w * -adiot
    } else {
        z = p + w * adiot
        if (n <= -3 && adiot < 0) {
            z = 0
        } else if (n >= 0 && adiot > 0) {
            z = -3 * w
        }
    }
    $gallery.animate({'left': z + 'px'}, 500, function () {
        flag = true
        let i = - ($gallery.position().left / w)
        $move.removeClass('gray')
        $($move[i]).addClass('gray')
    })
}

$left.on('click', () => {
    move(1)
})

$right.on('click', () => {
    move(-1)
})

$move.on('click', function () {
    let i = $(this).data('index')
    console.log($(this).siblings())
    $(this).siblings().removeClass('gray')
    $(this).addClass('gray')
    move(i, true)
})

$('.gallery_singel span').each(function (i) {
    let p = $(this).position().left
    let w = $(this).width()
    $(this).css({left: (p - w/2 + 8) + 'px'})
})

$('#gallery img').on('click', function () {
    let imgSrc = $(this).attr('src')
    $show.css({backgroundImage: `url(${imgSrc})`})
    $mask.css({display: 'block'})
})

$hidden.on('click', function () {
    $mask.css({display: 'none'})
    $show.css({width: '600px', height: '500px'})
})

function resize (size) {
    let width = $show.width()
    let height = $show.height()
    $show.css({width: width + size + 'px', height: height + size + 'px'})
}

$add.on('click', function () {
    resize(100)
})

$reduce.on('click', function () {
    resize(-100)
})
})
