$('i.glyphicon-refresh-animate').hide();
function updateItems(r) {
    _opts.items.avaliable = r.avaliable;
    _opts.items.assigned = r.assigned;
    search('avaliable');
    search('assigned');
}

$('.btn-assign').click(function () {
    var $this = $(this);
    var target = $this.data('target');
    var items = $('select.list[data-target="' + target + '"]').val();

    if (items && items.length) {
        $this.children('i.glyphicon-refresh-animate').show();
        $.post($this.attr('href'), {items: items}, function (r) {
            updateItems(r);
        }).always(function () {
            $this.children('i.glyphicon-refresh-animate').hide();
        });
    }
    return false;
});
$("#add_fields").on('click','div i',function () {
    var data=[];
    element = $(this).parent().parent();
    data.push(element.attr('data-slider-value'));
    if (data && data.length) {
        $.post($("#remove").val(), {items: data}, function (r) {
            updateItems(r);
        }).always(function () {

        });
    }
    return false;
});
$("#new_fields").on('click','div',function () {
    var data=[];
    data.push($(this).attr('data-slider-value'));
    if (data && data.length) {
        $.post($("#assign").val(), {items: data}, function (r) {
            updateItems(r);
        }).always(function () {

        });
    }
    return false;
});

$('.search[data-target]').keyup(function () {
    search($(this).data('target'));
});

function search(target) {
    if(target == 'avaliable'){
        $("#new_fields").empty();
    }else{
        $("#add_fields").empty();
    }
    var q = $('.search[data-target="' + target + '"]').val();
    var groups = {
        role: [$('<optgroup label="角色列表">'), false],
        permission: [$('<optgroup label="权限列表">'), false],
    };
    $.each(_opts.items[target], function (name, group) {
        if (name.indexOf(q) >= 0) {
            if(target == 'avaliable'){
                $('<div class="field-item field-item-add" data-subtitle="" data-width="12" data-slider-value="'+name+'" data-title="'+name+'" data-field="couponprice">'+name+' </div>').appendTo($("#new_fields"));
            }else{
                $('<div class="field-item field-item-remove" data-slider-value='+name+' data-field="field-item field-item-add" data-title="'+name+'" data-width="12" data-subtitle="">'+name+'<span><i class="fa fa-remove"></i></span><input name="Sqlbackstore[dbname][]" value="'+name+'" type="hidden"> </div>').appendTo($("#add_fields"));
            }
        }
    });
    $.each(groups, function () {
        if (this[1]) {
            $list.append(this[0]);
        }
    });
}

// initial
search('avaliable');
search('assigned');
