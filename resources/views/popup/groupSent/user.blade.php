<div class="form-group" style="padding: 10px 0;background:#fff;margin: 0">

    <label for="inputPassword3" class="col-lg-2 col-md-3 control-label" style="text-align: left;padding-left: 10px;width: auto;">发送时间</label>
    <div class="col-sm-8">
        <input type="text" class="form-control datetimepicker" id="send_time" name="data[send_time]" placeholder="" value="{{$item->send_time ?? null}}" autocomplete="off">
    </div>

    <div class="form-group col-md-12" style="margin: 20px 0 0 0 ;background:#fff;width: 100%;border-top: 1px solid #eee;padding-top: 10px;">
        【<a href="javascript:;" onclick="setDataValue(10,'minutes')">10分钟后</a>】
        【<a href="javascript:;" onclick="setDataValue(30,'minutes')">30分钟后</a>】
        【<a href="javascript:;" onclick="setDataValue(1,'hours')">1小时后</a>】
        【<a href="javascript:;" onclick="setDataValue(2,'hours')">2小时后</a>】
        【<a href="javascript:;" onclick="setDataValue(3,'hours')">3小时后</a>】
    </div>
</div>

<div class="grouptypes" style="margin: 10px 0 0 0;background: #fff;padding: 10px;height: auto;">
    <div class="form-group" style="margin: 0;">
        <label for="inputPassword3" class="col-sm-2 control-label" style="text-align: left;;width: auto;">发送用户群</label>
        <div class="col-sm-3">
            <select id="grouptype" class="form-control" name="data[filter_type]">
                <option value="0">所有粉丝</option>
                <option value="1">条件粉丝</option>
            </select>
        </div>
    </div>

    <!--条件粉丝-->
    <div class="grouptype2" style="padding-left: 10px; display: none;">
        <div class="form-group" style="margin: 20px 0 0 0;">
            <label class="col-sm-2 control-label" style="text-align: left;padding-left: 0;padding-right: 15px;width: auto;">粉丝性别</label>
            <div class="col-sm-10 " style="padding:0; ">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn1 active ">
                        <input type="radio" name="data[sex]" value="-1" checked="">
                        全部
                    </label>
                    <label class="btn btn1 ">
                        <input type="radio" name="data[sex]" value="1"> 男
                    </label>
                    <label class="btn btn1 ">
                        <input type="radio" name="data[sex]" value="2"> 女
                    </label>
                    <label class="btn btn1 ">
                        <input type="radio" name="data[sex]" value="0"> 未知
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin: 20px 0 0 0;">
            <label class="col-sm-2 control-label" style="text-align: left;padding-left: 0;padding-right: 15px;width: auto;">支付状态</label>
            <div class="col-sm-10 " style="padding:0; ">
                <div class="btn-group paystatus" data-toggle="buttons">
                    <label class="btn btn1">
                        <input type="radio" name="data[pay]" value="-1" checked=""> 全部
                    </label>
                    <label class="btn btn1  active">
                        <input type="radio" name="data[pay]" value="0"> 未充值
                    </label>
                    <label class="btn btn1 ">
                        <input type="radio" name="data[pay]" value="1"> 已充值
                    </label>
                    <label class="btn btn1 ">
                        <input type="radio" name="data[pay]" value="2"> 年费会员
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group rechargemoney" style="margin: 20px 0px 0px; display: none;">
            <label class="col-md-2 control-label" style="text-align: left;padding-left: 0;padding-right: 0;width: auto;">充值金额:
                &nbsp;</label>
            <div class="col-sm-2">
                <input type="number" class="qf-num1 form-control" name="data[totalmoney_from]" value="" oninput="if(value<0)value=0">
            </div>
            <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>
            <div class="col-sm-2">
                <input type="number" class="qf-num2 form-control" name="data[totalmoney_to]" value="" oninput="if(value<0)value=0">
            </div>

{{--            <label class="col-md-2 control-label" style="text-align: left;padding-left: 0;padding-right: 0;width: auto;">剩余书币:--}}
{{--                <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="不填，表示无限制"></i> &nbsp;--}}
{{--            </label>--}}
{{--            <div class="col-sm-2">--}}
{{--                <input type="number" class="qf-num1 form-control" name="data[balance_from]" value="" oninput="if(value<0)value=0">--}}
{{--            </div>--}}
{{--            <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>--}}
{{--            <div class="col-sm-2">--}}
{{--                <input type="number" class="qf-num2 form-control" name="data[balance_to]" value="--" oninput="if(value<0)value=0">--}}

{{--            </div>--}}
        </div>

{{--        <div class="form-group" style="margin: 20px 0 0 0;">--}}
{{--            <label for="inputPassword3" class="col-md-2 control-label" style="text-align: left;padding-left: 0;padding-right: 0;width: auto;">最后阅读时间</label>--}}
{{--            <div class="col-sm-4">--}}
{{--                <input type="text" class="form-control datetimepicker1" id="readtime_from" name="data[readtime_from]" placeholder="" value="" autocomplete="off">--}}
{{--            </div>--}}
{{--            <label class="col-md-1 control-label" style="text-align: left">~</label>--}}
{{--            <div class="col-sm-4">--}}
{{--                <input type="text" class="form-control datetimepicker1" id="readtime_to" name="data[readtime_to]" placeholder="" value="" autocomplete="off">--}}
{{--            </div>--}}
{{--        </div>--}}


        <div class="form-group" style="margin: 20px 0 0 0;">
            <label for="inputPassword3" class="col-md-2 control-label" style="text-align: left;padding-left: 0;padding-right: 0;width: auto;">关注时间</label>
            <div class="col-sm-4">
                <input type="text" class="form-control datetimepicker1" id="stime" name="data[stime]" placeholder="" value="" autocomplete="off">
            </div>
            <label class="col-md-1 control-label" style="text-align: left">~</label>
            <div class="col-sm-4">
                <input type="text" class="form-control datetimepicker1" id="etime" name="data[etime]" placeholder="" value="" autocomplete="off">
            </div>
        </div>

{{--        <div class="form-group" style="margin: 20px 0 0 0;">--}}
{{--            <label class="col-md-2 control-label" style="text-align: left;padding-left: 0;padding-right: 0;width: auto;">群发次数&nbsp;<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="指本月高级群发。仅能监测到平台内群发次数"></i></label>--}}
{{--            <div class="col-sm-2">--}}
{{--                <input type="number" class="qf-num1 form-control" name="data[qnum1]" value="" oninput="if(value<0)value=0">--}}
{{--            </div>--}}
{{--            <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>--}}
{{--            <div class="col-sm-2">--}}
{{--                <input type="number" class="qf-num2 form-control" name="data[qnum2]" value="" oninput="if(value>=4)value=4;if(value<0)value=0;">--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="form-group" style="margin: 20px 0 0 0;">--}}
{{--            <label class="col-md-2 control-label" style="text-align: left;padding-left: 0;padding-right: 0;width: auto;">阅读记录</label>--}}
{{--            <div class="col-md-3">--}}
{{--                <select class="form-control" name="data[tag_type]">--}}
{{--                    <option value="1" selected="">排除</option>--}}
{{--                    <option value="2">包含</option>--}}
{{--                </select>                                        </div>--}}
{{--            <div class="col-md-6">--}}
{{--                <select id="tag_id" name="data[tag_id]" class="form-control select2-hidden-accessible" data-select2-id="tag_id" tabindex="-1" aria-hidden="true">--}}
{{--                </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="3" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-tag_id-container"><span class="select2-selection__rendered" id="select2-tag_id-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder"><div></div></span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    </div>
    <div class="form-group" style="margin: 20px 0 0 0;">
        <div class="col-sm-12 " style="padding:0 0 0 10px; " id="plan-to-send">
            <input type="hidden" class="form-control" name="data[plan_to_send]" value="">
            预计送达人数  : <span></span>
        </div>
    </div>
</div>
