/*--------------------statistics/ic_transform_s页面vvv--------------------*/
//创建element
function createElement(order, channelOrder, realAmount, chargeChanel, userPaymentAmount, chargeType, orderTime) {
    var panel_record2 =
        "<div class='panel panel-default mark_contain_panel'>" +
        "<div class='panel-body'>" +
        "<ul class='float_left_ul'><li>订单号:" + order + "</li><li>渠道订单号:" + channelOrder + "</li></ul>" +
        "<ul class='float_left_ul'><li>商品实际金额:" + realAmount + "</li><li>充值渠道:" + chargeChanel + "</li></ul>" +
        "<ul class='float_left_ul'><li>用户支付金额:" + userPaymentAmount + "</li><li>支付类型:" + chargeType + "</li></ul>" +
        "<ul class='float_left_ul'><li>订单时间:" + orderTime + "</li><li>转存地区:<span class='address_real' style='margin-bottom: 10px'></span></li></ul>" +
        "</div>" +
        "</div>";

    return panel_record2;
}

/**
 * 调用高德地图api 根据经纬度获取地址
 * @param lngLatXY array 经纬度
 * @param index_l int 该类出现的位置index
 */
function geoCoderMap(lngLatXY, index_l) {
    var geocoder = new AMap.Geocoder({});
    geocoder.getAddress(lngLatXY, function (status, result) {
        if (status === 'complete' && result.info === 'OK') {
            gCoder_CallBack(result, index_l);
        }
    });
}

/**
 *执行回掉action
 * @param data 高德API返回结果
 * @param index_l
 */
function gCoder_CallBack(data, index_l) {
    //返回地址
    var address = data.regeocode.formattedAddress;
    $(".address_real").eq(index_l).text(address);
}

/**
 * 获取个人ic转存信息
 * @param thisDom
 * @param url
 */
function getDetailInfoOfIc(thisDom, url) {
    var uid = thisDom.siblings('.hidden_id').val(),
        time_start = $("input[name='beginTime']").val(),
        time_end = $("input[name='endTime']").val(),
        mobile = thisDom.parent().siblings(".mobile_2").text(),
        transform_num = thisDom.parent().siblings(".transform_num").text();

    $.ajax({
        url: url,
        type: 'get',
        data: {uid: uid, time_start: time_start, time_end: time_end}
    }).done(function (msg) {
        //移除原先js创建的element
        $(".mark_contain_panel").remove();
        //初始化js生成的地址
        $(".address_real").text('');
        //初始化详情头部
        $(".fill_mobile").text('');
        $(".block_p").text('');
        $(".nums_2").text('');
        $(".inline_p").text('');

        var count = msg.length,
            //个人该段时间转存总额
            amount_money = 0;

        for (var i = 0; i < count; i++) {
            amount_money += msg[i].amount_money;
            var element = createElement(msg[i].orderid, msg[i].order_juhe, msg[i].amount_money, msg[i].channel, msg[i].spend_money, msg[i].deduct_type, msg[i].lastime);
            //遍历插入数据
            $(".contain_panel_record").after(element);
            //调整经纬度顺序
            var lngLat = reverseOrderOfLngAndLat(msg[i].coordinate);
            //根据经纬度生成地址 并写到相关处
            geoCoderMap(lngLat, i);

        }

        //写入手机号
        $(".fill_mobile").text(mobile);
        //转存时间
        $(".block_p").text(time_start + " 至 " + time_end);
        //转存次数
        $(".nums_2").text(transform_num);
        //转存金额
        $(".inline_p").text("转存金额:" + amount_money);
    });
}

/**
 * 调整经纬度顺序
 * @param lngLgt
 * @returns {[*,*]}
 */
function reverseOrderOfLngAndLat(lngLgt) {
    var firstOccurrence = lngLgt.indexOf(','),
        a = lngLgt.substr(0, firstOccurrence),
        b = lngLgt.substr(firstOccurrence + 1);
    return [b, a];
}

/**
 * 二选一 input checkbox
 * @param thisDom $(this)
 */
function alternativeOption(thisDom) {
    var siblingsCheck = thisDom.parent().siblings('.option_2').find('input');
    if (siblingsCheck.is(':checked')) {
        siblingsCheck.prop('checked', false);
    }
}

/**
 * 提交筛选条件
 * @returns {boolean}
 */
function submitIcTransform() {
    var time_start = $("input[name='beginTime']").val(),
        time_end = $("input[name='endTime']").val(),
        inputNumNode = $("#input-num"),
        inputNum = inputNumNode.val(),
        inputProcessNum = parseFloat(inputNum);

    if (time_start == time_end) {
        alert('时间间隔至少一天!');
        inputNumNode.val('');
        return false;
    }
    if (time_start > time_end) {
        alert('开始时间不能大于结束时间!');
        inputNumNode.val('');
        return false;
    }

    if (inputNum.length > 0 && isNaN(inputProcessNum)) {
        alert('请输入数字');
        inputNumNode.val('');
        return false;
    }

    if (inputNum.length > 0 && !Number.isInteger(inputProcessNum)) {
        alert('请输入正整数');
        inputNumNode.val('');
        return false;
    }

    if (inputNum.length > 0 && inputProcessNum <= 0) {
        alert('次数不能小于或等于0');
        inputNumNode.val('');
        return false;
    }

    $(".submit_2").submit();

}

/**
 * 重置
 * @param url
 */
function resetCondition(url) {
    window.location.href = url;
}
/*--------------------statistics/ic_transform_s页面^^^--------------------*/

/*--------------------statistics/is_transform_total页面vvv--------------------*/

/**
 * 二选一
 * @param thisDom
 */
function alternativeOption2(thisDom) {
    var siblingsCheck = thisDom.parent().siblings('label').find('.option_2');
    if (siblingsCheck.is(':checked')) {
        siblingsCheck.prop('checked', false);
    }
}

/**
 * 每日转存总量 提交查询
 * @param url
 * @returns {boolean}
 */
function submitSearch2(url) {
    var time_start = $("input[name='beginTime']").val(),
        time_end = $("input[name='endTime']").val(),
        checkInput = $("input[name='list_or_chart']:checked").val();

    if (time_start == time_end) {
        alert('时间间隔至少一天!');
        return false;
    }
    if (time_start > time_end) {
        alert('开始时间不能大于结束时间!');
        return false;
    }

    if (checkInput == 1) {
        $.ajax({
            url: url,
            type: 'get',
            data: {beginTime: time_start, endTime: time_end, list_or_chart: checkInput}
        }).done(function (msg) {
            /*console.log(msg);return;*/
            $(".rm_later").remove();
            $(".table_option").remove();
            $(".my_own_page").remove();

            if ($("#myChart").length < 1) {
                $(".append_after_e").append("<canvas id='myChart' height='120'></canvas>");
            }
            //返回的数据
            var returnData = msg[0],
                labels = [],
                data1 = [],
                data2=[],
                data3=[],
                data4=[],
                data5=[];

            for (var i = 0; i < returnData.length; i++) {
                labels.push(returnData[i].lastime);
                data1.push(returnData[i].amount_money);
                data2.push(returnData[i].waitingAmount);
                data3.push(returnData[i].processAmount);
                data4.push(returnData[i].failAmount);
                data5.push(returnData[i].refundAmount);
            }

            var ctx = document.getElementById('myChart').getContext('2d');
            //显示Chart
            showCharts(ctx, labels, data1,data2,data3,data4,data5);

        });
    } else {
        $(".submit_3").submit();
    }
}

/**
 * this is Chart instance and display it by con
 * @param ctx
 * @param labels
 * @param data1
 * @param data2
 * @param data3
 * @param data4
 * @param data5
 */
function showCharts(ctx,labels,data1,data2,data3,data4,data5) {
    var chart = new Chart(ctx, {
        type:'line',
        data:{
            labels:labels,
            datasets:[{
                label:'成功转存',
                borderColor:'#95b75d',
                data:data1
            }, {
                label:'等待处理',
                borderColor:'#999999',
                data:data2
            }, {
                label:'充值中',
                borderColor:'#3fbae4',
                data:data3
            }, {
                label:'转存失败',
                borderColor:'#af4342',
                data:data4
            }, {
                label:'退款金额',
                borderColor:'#fea223',
                data:data5
            }]
        }
    });
}

/**
 * 每日转存总量页面 重置按钮
 * @param url
 */
function resetCondition2(url) {
    var listOrTable = $("#myChart").length;
    if (listOrTable == 1) {
        $.ajax({
            url: url,
            type: 'get',
            data: {beginTime: '', endTime: '', list_or_chart: 1}
        }).done(function (msg) {
            var returnData=msg[0],
                time_start=msg[1],
                time_end=msg[2],
                labels = [],
                data1 = [],
                data2=[],
                data3=[],
                data4=[],
                data5=[];

            for (var i = 0; i < returnData.length; i++) {
                labels.push(returnData[i].lastime);
                /*data.push(returnData[i].amount_money);*/
                data1.push(returnData[i].amount_money);
                data2.push(returnData[i].waitingAmount);
                data3.push(returnData[i].processAmount);
                data4.push(returnData[i].failAmount);
                data5.push(returnData[i].refundAmount);
            }

            var ctx = document.getElementById('myChart').getContext('2d');
            //显示Chart
            showCharts(ctx, labels, data1,data2,data3,data4,data5);

            //初始化时间
            $("input[name='beginTime']").val(time_start);
            $("input[name='endTime']").val(time_end);
        });
    } else {
        window.location.href = url;
    }
}

/*--------------------statistics/is_transform_total页面^^^--------------------*/