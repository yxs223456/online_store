/**
 * Created by lichaoyang on 2020/3/5.
 */
//编辑商品
$().ready(function () {

    $('#formSubmit').click(function () {

        if (common.isNullOrEmpty($('#goods_name').val())) {
            layer.msg('请输入商品名称', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        if (common.isNullOrEmpty($('#goods_no').val())) {
            layer.msg('请输入商品编号', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        if (common.isNullOrEmpty($('#product_no').val())) {
            layer.msg('请输入商品货号', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        if (common.isNullOrEmptyForDictionary($('#market_price').val())) {
            layer.msg('请输入市场价格', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        if (common.isNullOrEmptyForDictionary($('#shop_price').val())) {
            layer.msg('请输入门店价格', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        if (common.isNullOrEmptyForDictionary($('#stock').val())) {
            layer.msg('请输入库存', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        if (common.isNullOrEmpty($('#goods_unit').val())) {
            layer.msg('请输入商品单位', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        $("form#editPost").submit();

    });

    $(document).on("change", ".change-select", function () {
        var uuid = $(this).val();

        $(this).nextAll().remove();
        if (uuid === "") {
            return
        }
        getData(uuid, "");
    });

    function getData(uuid, current_uuid) {
        $.ajax({
            url: '/admin/goods/getCat',//地址
            dataType: 'json',//数据类型
            type: 'POST',//类型
            data: {"current_uuid": current_uuid, "uuid": uuid},
            timeout: 4000,//超时
            //请求成功
            success: function (data) {
                $('#selected-box').append(data);
                console.log(data);
                return true
            },
            //失败/超时
            error: function (data) {
                console.log(data)
                return false
            }
        })
    }

    //IOS开关样式配置
    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {
        color: '#1AB394'
    });

    elem.onchange = function () {
        if (elem.checked) {
            $("input[name='is_sale']").val(1);
        } else {
            $("input[name='is_sale']").val(0);
        }
    };

    var config = {
        '.chosen-select': {}
    };
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    // init goods category
//        var data = "{$info.goods_classify_path}";
//
//        //可以用字符或字符串分割
//        arr=data.split('_');
//        for(var i=0;i<arr.length;i++)
//        {
//            console.log(i);
//            console.log(arr[i]);
//            getData(arr[i], arr[i + 1]);
//        }


    var editor = new UE.ui.Editor();
    editor.render("myEditor");
    // 在渲染 ueditor 的时候, 将 ueditor 交给一个全局变量
    var ue = UE.getEditor('myEditor');

    // 重写百度富文本编辑器监听上传图片事件
    UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
    UE.Editor.prototype.getActionUrl = function(action) {
        if (action == 'uploadimage') {
            return '/admin/upload/uploadEditorToOss';
        } else {
            return this._bkGetActionUrl.call(this, action);
        }
    };


    // validate signup form on keyup and submit
    var icon = "<i class='fa fa-times-circle'></i> ";
    $("#signupForm").validate({
        rules: {
            name: {
                required: true,
            },
            image_url: {
                required: true
            },
        },
        focusCleanup: true,
        submitHandler: function (form) {

            $(form).ajaxSubmit({
                type: 'post',
                url: "/admin/goodsBrand/addPost",
                beforeSend: function () {
                    // 显示loading
                    index2 = layer.load(0, {
                        shade: [0.3, '#fff']
                    });
                    $("#formSubmit").attr('type', 'button')
                },
                success: function (data) {

                    if (data['code'] != 1) {
                        layer.msg(data.msg, {icon: 2, time: 1000, shade: 0.1,}, function (index) {
                            layer.close(index)
                        });
                        $("#formSubmit").attr('type', 'submit')
                        return false;
                    }

                    layer.msg(data.msg, {icon: 1, time: 1000, shade: 0.1,}, function (index) {
                        window.location.href = "/admin/goods_brand/index"
                    });
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    layer.msg('error!', {icon: 1, time: 1000});
                },
                complete: function () {
                    // 隐藏loading
                    layer.close(index2);
                }
            });
        }
    });
});



//编辑规格
$().ready(function () {

    $('#formSubmitSpec').click(function () {

        if (common.isNullOrEmpty($('#name').val())) {
            layer.msg('请输入规格内容', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

//            if (common.isNullOrEmpty($('#image_url').val())) {
//                layer.msg('请上传图片', {icon: 2, time: 1500, shade: 0.1}, function (index) {
//                    layer.close(index);
//                });
//                return false;
//            }

        $("form#editSpecPost").submit();

    });

    $(document).on("change", ".change-select", function () {
        var uuid = $(this).val();
        // console.log(uuid);
        var options = $("#specSelect option:selected");
        console.log(options);
        var image = options.attr("image-data");
        console.log(image);
        setForm(image)
    });

    function setForm(bool) {
        if (bool == 1) {
            var html = `<div class="form-group live-form">
                    <label class="col-sm-2 control-label">图片：</label>
                <div class="input-group col-sm-2">
                    <img id="image_url_src_spec" src="" width="100px">
                    <input type="hidden" id="image_url_spec" name="image_url" value=""/>
                    <input type="file" name="file" id="file_image_url_spec"
                onchange="uploadImage('image_url_src_spec', 'image_url_spec', 'file_image_url_spec')" class="form-control"/>
                    </div>
                    </div>

                    <div class="form-group live-form">
                    <label class="col-sm-2 control-label">规格：</label>
                <div class="input-group col-sm-2">
                    <input type="text" name="name" id="name" value="" class="form-control"/>
                    </div>
                    </div>`;

        } else {
            var html = `<div class="form-group live-form">
                    <label class="col-sm-2 control-label">规格：</label>
                <div class="input-group col-sm-2">
                    <input type="text" name="name" id="name" value="" class="form-control"/>
                    </div>
                    </div>`;
        }
        $(".live-form").remove();
        $(".live-bind").after(html);
    }

});



//编辑销售规格
$().ready(function () {

    $('#formSubmitSale').click(function () {

//            if (common.isNullOrEmpty($('#name').val())) {
//                layer.msg('请输入规格内容', {icon: 2, time: 1500, shade: 0.1}, function (index) {
//                    layer.close(index);
//                });
//                return false;
//            }

        if ($("input[name='product_no[]']").length < 1) {
            layer.msg('先获取销售规格', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        var flag = true
        $("input[name='product_no[]']").each(function () {
            if (common.isNullOrEmpty($(this).val())) {
                layer.msg('货号不能有空', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                    layer.close(index);
                });
                flag = false;
                return false;
            }
        });

        if (!flag) {
            return false;
        }

        $("input[name='market_price[]']").each(function () {
            if (common.isNullOrEmptyForDictionary($(this).val())) {
                layer.msg('市场价格不能有空', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                    layer.close(index);
                });
                flag = false;
                return false;
            }
        });

        if (!flag) {
            return false;
        }

        $("input[name='shop_price[]']").each(function () {
            if (common.isNullOrEmptyForDictionary($(this).val())) {
                layer.msg('本店价格不能有空', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                    layer.close(index);
                });
                flag = false;
                return false;
            }
        });

        if (!flag) {
            return false;
        }

        $("input[name='stock[]']").each(function () {
            if (common.isNullOrEmptyForDictionary($(this).val())) {
                layer.msg('库存不能有空', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                    layer.close(index);
                });
                flag = false;
                return false;
            }
        });

        if (!flag) {
            return false;
        }

        $("form#editSalePost").submit();

    });

    function getDataForm(items) {
        $.ajax({
            url: '/admin/goods/getSaleForm',//地址
            dataType: 'json',//数据类型
            type: 'POST',//类型
            data: {"item": items},
            timeout: 4000,//超时
            //请求成功
            success: function (data) {
//                    $('#selected-box').append(data);
                $('#ibox-content').html("");
                $('#ibox-content').html(data);
                // console.log(data);
                return true
            },
            //失败/超时
            error: function (data) {
                console.log(data);
                return false
            }
        })
    }

    $('#getForm').click(function () {
        var checkboxs = $("input[class='spec']:checked");
        if(checkboxs.length < 1){
            layer.msg('请选择规格', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        } else {
            var data = new Array;
            for (i=0;i<checkboxs.length;i++) {
                data[i] = checkboxs[i].value;
            }
            console.log(data)
            getDataForm(data)
        }
    })

});



//编辑图片
$().ready(function () {

    $(document).on('click',".btn-del-img",function () {
        $(this).closest(".image-box").remove()
    });

    $("#upload").click(function () {
        $("#upload-file").click();
    });


    $('#formSubmitImg').click(function () {

        if ($(".image-box").length < 1) {
            layer.msg('请上传至少一张图片', {icon: 2, time: 1500, shade: 0.1}, function (index) {
                layer.close(index);
            });
            return false;
        }

        $("form#editImgPost").submit();

    });
});