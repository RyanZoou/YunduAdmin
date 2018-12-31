define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        mobilenewmedia: function () {
            // 给上传按钮添加上传成功事件
            $("#ad_proved_div").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                $("#ad_proved_img_path").val(url);
                Toastr.success(__('Upload successful'));
            });

            Form.api.bindevent($("#ad_support_form"), function (data, ret) {
                if (ret.code == 1) {
                    if (confirm("添加成功，是否跳转查看结果？")) {
                        setTimeout(function () {
                            location.href = data.url ? data.url : "/";
                        }, 1000);
                    }
                }
            });
        },
        videoadvertisement: function () {
            // 给上传按钮添加上传成功事件
            $("#ad_proved_div").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                $("#ad_proved_img_path").val(url);
                Toastr.success(__('Upload successful'));
            });

            Form.api.bindevent($("#ad_support_form"), function (data, ret) {
                if (ret.code == 1) {
                    if (confirm("添加成功，是否跳转查看结果？")) {
                        setTimeout(function () {
                            location.href = data.url ? data.url : "/";
                        }, 1000);
                    }
                }
            });
        },
        smallprogramproduction: function () {
            // 给上传按钮添加上传成功事件
            $("#ad_proved_div").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                $("#ad_proved_img_path").val(url);
                Toastr.success(__('Upload successful'));
            });

            Form.api.bindevent($("#ad_support_form"), function (data, ret) {
                if (ret.code == 1) {
                    if (confirm("添加成功，是否跳转查看结果？")) {
                        setTimeout(function () {
                            location.href = data.url ? data.url : "/";
                        }, 1000);
                    }
                }
            });
        },
        fullbigdata: function () {
            // 给上传按钮添加上传成功事件
            $("#ad_proved_div").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                $("#ad_proved_img_path").val(url);
                Toastr.success(__('Upload successful'));
            });

            Form.api.bindevent($("#ad_support_form"), function (data, ret) {
                if (ret.code == 1) {
                    if (confirm("添加成功，是否跳转查看结果？")) {
                        setTimeout(function () {
                            location.href = data.url ? data.url : "/";
                        }, 1000);
                    }
                }
            });
        },
        lists: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/lists',
                    detail_url: 'order/detail',
                    cancel_url: 'api/order/cancel',
                    table: 'order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'addTime',
                columns: [
                    [
                        {field: 'adName', title: '投放名称', formatter:Table.api.formatter.search, operate: 'LIKE'},
                        {field: 'adType', title: '广告投放', searchList: {'移动新媒体':'移动新媒体', "视频广告": '视频广告', "小程序制作":'小程序制作', '全网通大数据':'全网通大数据'},operate: 'FIND_IN_SET', formatter: Table.api.formatter.status},
                        {
                            field: 'status',
                            title: '投放状态',
                            custom: {'canceled':'danger', 'working':'success', 'pendding': 'warning'},
                            searchList: {"new": '新申请', "pendding": '已审核', 'working':'投放中', 'expired': '已结束', 'canceled' : '已取消'},
                            operate: 'FIND_IN_SET',
                            formatter: Table.api.formatter.label
                        },
                        {field: 'adBusinessType', title: '投放行业',operate: 'LIKE'},
                        {field: 'adPlatform', title: '投放平台',operate: 'LIKE'},
                        {field: 'adMode', title: '广告形式',operate: 'LIKE',visible: false,},
                        {field: 'adPosition', title: '广告位', visible: false,},
                        {field: 'adProvedFilePath', title: '资质证明', formatter: Table.api.formatter.image,visible: false,},
                        {field: 'adForGender', title: '性别',visible: false,},
                        {field: 'adForAge', title: '年龄段', visible: false,},
                        {field: 'adHobbyType', title: '兴趣分类',visible: false,operate: 'LIKE'},
                        {field: 'adForArea', title: '投放地区', operate: 'LIKE'},
                        {field: 'adDateRange', title: '投放日期', },
                        {field: 'adTimeSpecial', title: '投放时段', visible: false,},
                        {field: 'adDailyPrice', title: '每日预算', visible: false,},
                        {field: 'adPayType', title: '出价形式', },
                        {field: 'addTime', title: '申请时间↑↓', formatter: Table.api.formatter.datetime, addclass: 'datetimerange', operate: 'RANGE',sortable: true},
                        {
                            field: 'buttons',
                            width: "80px",
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('详情'),
                                    title: __('投放详情'),
                                    classname: 'btn btn-xs btn-success btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'order/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        return true;
                                    }
                                },
                                {
                                    name: 'ajax',
                                    text: __('取消'),
                                    title: __('点击取消申请'),
                                    classname: 'btn btn-xs btn-danger btn-magic btn-ajax',
                                    icon: 'fa fa-magic',
                                    url: '../api/order/cancel',
                                    confirm: __('确定要取消投放该项目吗？'),
                                    visible: function (row) {
                                        if (row.status == 'new') {
                                            return true
                                        } else {
                                            return false;
                                        }
                                    },
                                    success: function (data, ret) {
                                        Layer.alert(ret.msg);
                                        setTimeout(function () {
                                            location.href = data.url ? data.url : "/";
                                        }, 1000);
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                }
                            ],
                        formatter: Table.api.formatter.buttons
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            $("button[name='commonSearch']").css('margin','-10px 0 auto 4px');
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});