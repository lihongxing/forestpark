<?
use yii\helpers\Url;
?>
<link rel="stylesheet" href="/admin/plugins/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" href="/admin/plugins/fullcalendar/fullcalendar.print.css" media="print">
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h4 class="box-title">值班备勤项</h4>
                </div>
                <div class="box-body">
                    <!-- the events -->
                    <div id="external-events">
                        <?php if(!empty($beondutytemplates)){?>
                            <?php foreach($beondutytemplates as $key => $item){?>
                                <div style="background-color: <?=$item['tem_currColor']?>; border-color:<?=$item['tem_currColor']?>; color: rgb(255, 255, 255); position: relative;" class="external-event ui-draggable ui-draggable-handle"><input id="" value="<?=$item['tem_id']?>" type="hidden"><?=$item['tem_val']?>(<?=$item['tem_username']?>)</div>
                            <?php }?>
                        <?php }?>
                        <div class="checkbox">
                            <label for="drop-remove">
                                <input id="drop-remove" type="checkbox">
                                移动后删除
                            </label>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">创建值班备勤项</h3>
                </div>
                <div class="box-body">
                    <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                        <select id="selectuser" class="form-control select2 select2-hidden-accessible" data-placeholder="选择值班人员" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="" >选择值班人员</option>
                            <?php if(!empty($users)){?>
                                <?php foreach($users as $key => $item){?>
                                    <option value="<?=$item['id']?>" > <?=$item['username']?> </option>
                                <?php }?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                        <button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">选择颜色 <span class="caret"></span></button>
                        <ul class="fc-color-picker" id="color-chooser">
                            <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                        </ul>
                    </div>
                    <div class="input-group">
                        <input id="new-event" class="form-control" placeholder="备勤信息" type="text">
                        <div class="input-group-btn">
                            <button id="add-new-event" type="button" class="btn btn-primary btn-flat">添加</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body no-padding">
                    <!-- THE CALENDAR -->
                    <div id="calendar" class="fc fc-ltr fc-unthemed">
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- Bootstrap 3.3.6 -->
<script src="/admin/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Slimscroll -->
<script src="/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/admin/plugins/select2/select2.full.min.js"></script>
<script src="/admin/plugins/fastclick/fastclick.js"></script>
<script src="/resource/js/lib/moment.js"></script>
<script src="/admin/plugins/fullcalendar/fullcalendar.min.js"></script>
<script>
    $(function () {

        $(".select2").select2();
        function ini_events(ele) {
            ele.each(function () {
                var eventObject = {
                    title: $.trim($(this).text())
                };
                $(this).data('eventObject', eventObject);
                $(this).draggable({
                        zIndex: 1070,
                    revert: true,
                    revertDuration: 0
                });

            });
        }
        ini_events($('#external-events div.external-event'));
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                //right: 'month,agendaWeek,agendaDay'
            },
            monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            dayNames: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
            dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
            today: ["今天"],
            firstDay: 1,
            buttonText: {
                today: '本月',
                month: '月',
                week: '周',
                day: '日',
                prev: '上一月',
                next: '下一月'
            },

            events: "<?=Url::toRoute("/admin/beonduty/beonduty-list-default")?>",
            dayClick: function(date, allDay, jsEvent, view) {},
            eventClick: function(calEvent, jsEvent, view) {
                dialog({
                    title: prompttitle,
                    content: '您去定要删除[ '+calEvent.title+' ]值班备勤吗？',
                    okValue: '确定',
                    ok: function () {
                        this.close().remove();
                        //删除值班模板
                        $.ajax({
                            type: "POST",
                            url: '<?=Url::toRoute("/admin/beonduty/beonduty-delete")?>',
                            //提交的数据
                            data: {cal_id: calEvent.id, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                            datatype: "json",
                            success: function (data) {
                                data = eval("(" + data + ")");
                                switch(data.status){
                                    case 403:
                                        content = '你没有删除值班模板的权限';
                                        break;
                                    case 100:
                                        content = '值班备勤删除成功';
                                        break;
                                    case 101:
                                    case 102:
                                        content = '值班备勤删除失败';
                                        break;
                                }
                                dialog({
                                    title: prompttitle,
                                    content: content,
                                    cancel: false,
                                    okValue: '确定',
                                    ok: function () {
                                        $('#calendar').fullCalendar('refetchEvents');
                                    }
                                }).showModal();
                            }
                        });
                    },
                    cancelValue: '取消',
                    cancel: function () {}
                }).showModal();
            },
            editable: true,
            droppable: true,
            drop: function (date, allDay) {
                var originalEventObject = $(this).data('eventObject');
                var copiedEventObject = $.extend({}, originalEventObject);
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                copiedEventObject.backgroundColor = $(this).css("background-color");
                copiedEventObject.borderColor = $(this).css("border-color");
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                var tem_id = $(this).children().val();
                var date = new Date(date);
                var d = date.getDate(),
                    m = date.getMonth()+1,
                    y = date.getFullYear();

                $.ajax({
                    type: "POST",
                    async:false,
                    url: '<?=Url::toRoute("/admin/beonduty/beonduty-form")?>',
                    //提交的数据
                    data:  {tem_id: tem_id, y:y , d:d, m:m, flag:'add', _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    datatype: "json",
                    success: function (data) {
                        data = eval("(" + data + ")");
                        switch(data.status){
                            case 403:
                                content = '你没有新增值修改班备勤的权限';
                                dialog({
                                    title: prompttitle,
                                    content: content,
                                    cancel: false,
                                    okValue: '确定',
                                    ok: function () {
                                    }
                                }).showModal();
                                break;
                            case 100:
                                break;
                            case 101:
                                content = '新增值班备勤失败';
                            dialog({
                                title: prompttitle,
                                content: content,
                                cancel: false,
                                okValue: '确定',
                                ok: function () {
                                }
                            }).showModal();
                            return;
                            break;
                        }
                    }
                });
                if ($('#drop-remove').is(':checked')) {
                    //删除值班模板
                    $.ajax({
                        type: "POST",
                        url: '<?=Url::toRoute("/admin/beonduty/beonduty-template-delete")?>',
                        //提交的数据
                        data: {tem_id: tem_id, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                        datatype: "json",
                        success: function (data) {
                            data = eval("(" + data + ")");
                            switch(data.status){
                                case 403:
                                    content = '你没有删除值班模板的权限';
                                    dialog({
                                        title: prompttitle,
                                        content: content,
                                        cancel: false,
                                        okValue: '确定',
                                        ok: function () {
                                        }
                                    }).showModal();
                                    break;
                                case 100:
                                    break;
                            }
                        }
                    });
                    $(this).remove();
                }
            },
            eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
                $.ajax({
                    type: "POST",
                    url: '<?=Url::toRoute('/admin/beonduty/beonduty-form')?>',
                    //提交的数据
                    data: {id:event.id, daydiff: dayDelta._days, flag:'update', _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    datatype: "json",
                    success: function (data) {
                        data = eval("(" + data + ")");
                        switch(data.status) {
                            case 403:
                                content = '你没有新增修改值班备勤的权限';
                                break;
                            dialog({
                                title: prompttitle,
                                content: content,
                                cancel: false,
                                okValue: '确定',
                                ok: function () {
                                }
                            }).showModal();
                            revertFunc();
                        }
                    }
                });
            }
        });
        var currColor = "#3c8dbc"; //Red by default
        var colorChooser = $("#color-chooser-btn");
        $("#color-chooser > li > a").click(function (e) {
            e.preventDefault();
            currColor = $(this).css("color");
            $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
        });
        $("#add-new-event").click(function (e) {
            e.preventDefault();
            var val = $("#new-event").val();
            var username = $("#selectuser  option:selected").text();
            var uid = $("#selectuser  option:selected").val();
            if(username == '' || uid == ''){
                content = '请选择值班人员';
                dialog({
                    title: prompttitle,
                    content: content,
                    cancel: false,
                    okValue: '确定',
                    ok: function () {
                        return;
                    }
                }).showModal();
            }

            if (val.length == 0) {
                return;
            }
            //入库值班模板
            $.ajax({
                type: "POST",
                url: '<?=Url::toRoute("/admin/beonduty/beonduty-template-form")?>',
                //提交的数据
                data: {uid: uid, currColor: currColor, val: val, username: username,_csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                datatype: "json",
                success: function (data) {
                    data = eval("(" + data + ")");
                    switch(data.status){
                        case 403:
                            content = '你没有添加值班模板的权限';
                            dialog({
                                title: prompttitle,
                                content: content,
                                cancel: false,
                                okValue: '确定',
                                ok: function () {
                                }
                            }).showModal();
                            break;
                        case 100:
                            var event = $("<div />");
                            event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
                            event.html( '<input type="hidden"  value="'+data.id+'">'+val+'('+username+')');
                            $('#external-events').prepend(event);
                            ini_events(event);
                            $("#new-event").val("");
                            break;
                    }
                }
            });
        });
    });
</script>