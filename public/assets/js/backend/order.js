define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index',
                    edit_url: 'order/edit',
                    result_url: 'order/resultdata',
                    table: 'order',
                }
            });


            var table = $("#table");
            var tableOptions = {
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'addTime',
                pageSize: 20,
                pageList: [10, 20, 50, 'all'],
                columns: [
                    [
                        {field: 'adName', title: '投放名称', formatter:Table.api.formatter.search, operate: 'LIKE'},
                        {field: 'user.nickname', title: '用户名称', formatter:Table.api.formatter.search, operate: 'LIKE'},
                        {field: 'adType', title: '广告投放', searchList: {'移动新媒体':'移动新媒体', "视频广告": '视频广告', "小程序制作":'小程序制作', '全网通大数据':'全网通大数据'},operate: 'FIND_IN_SET', formatter: Table.api.formatter.status},
                        {
                            field: 'status',
                            title: '投放状态',
                            custom: {'canceled':'danger', 'working':'success', 'pendding': 'warning'},
                            searchList: {
                                "new": '新申请',
                                "pendding": '已审核',
                                'working':'投放中',
                                'expired': '已结束',
                                'canceled' : '已取消'
                            },
                            operate: 'FIND_IN_SET',
                            formatter: Table.api.formatter.label
                        },
                        {field: 'adBusinessType', title: '投放行业',operate: 'LIKE'},
                        {field: 'adPlatform', title: '投放平台',operate: 'LIKE'},
                        {field: 'adMode', title: '广告形式',operate: 'LIKE',visible: false,},
                        {field: 'adPosition', title: '广告位', visible: false,},
                        {field: 'adProvedFilePath', title: '资质证明', formatter: Table.api.formatter.image,},
                        {field: 'adForGender', title: '性别',visible: false,},
                        {field: 'adForAge', title: '年龄段', visible: false,},
                        {field: 'adHobbyType', title: '兴趣分类',visible: false,operate: 'LIKE'},
                        {field: 'adForArea', title: '投放地区', operate: 'LIKE'},
                        {field: 'adDateRange', title: '投放日期', },
                        {field: 'adTimeSpecial', title: '投放时段', visible: false,},
                        {field: 'adDailyPrice', title: '每日预算', visible: false,},
                        {field: 'adPayType', title: '出价形式', },
                        {field: 'addTime', title: '申请时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange', operate: 'RANGE',sortable: true},
                        {
                            field: 'buttons',
                            width: "110px",
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'edit',
                                    title: __('编辑'),
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    icon: 'glyphicon glyphicon-pencil',
                                    url: 'order/edit',
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                                {
                                    name: 'result',
                                    text: __('结果'),
                                    title: __('投放结果'),
                                    classname: 'btn btn-xs btn-success btn-dialog',
                                    icon: 'glyphicon glyphicon-indent-left',
                                    url: 'order/resultdata',
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
            };
            // 初始化表格
            table.bootstrapTable(tableOptions);

            // 为表格绑定事件
            Table.api.bindevent(table);

            //绑定TAB事件
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // var options = table.bootstrapTable(tableOptions);
                var typeStr = $(this).attr("href").replace('#','');
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    // params.filter = JSON.stringify({type: typeStr});
                    params.type = typeStr;

                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;

            });

            //必须默认触发shown.bs.tab事件
            // $('ul.nav-tabs li.active a[data-toggle="tab"]').trigger("shown.bs.tab");

        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                $(document).on("change", "#c-type", function () {
                    $("#c-pid option[data-type='all']").prop("selected", true);
                    $("#c-pid option").removeClass("hide");
                    $("#c-pid option[data-type!='" + $(this).val() + "'][data-type!='all']").addClass("hide");
                    $("#c-pid").selectpicker("refresh");
                });
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});