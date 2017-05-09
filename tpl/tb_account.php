{include file="header.html"}

<body>

<script type="text/javascript">
    $(function () {
        $("#done").click(function () {
            location.href = "index.php";
            return;
        });

        $("#select01").change(function () {
            var select_src = $("#select01").val();
            console.log(select_src);
            location.href = 'index.php?do=user.showBySrc&src=' + select_src;
        });

        $(".btn-info").click(function () {
            var url = $(this).data("daylink");
            location.href = url;
            return;
        });

        $(".b-status").click(function (){
            var status = $(this).data("status");
            var src = getUrlParam('src');
            var day = getUrlParam('day');
            var url = '';
            if (!day && typeof(day)!="undefined" && day!=0){
                url = 'index.php?do=user.showBySrc&src=' + src+'&status='+status;
            }else{
                url = 'index.php?do=user.showBySrc&src=' + src+'&status='+status+'&day='+day;
            }
            location.href = url;
            return;
        });

        function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg);  //匹配目标参数
            if (r != null) return unescape(r[2]); return null; //返回参数值
        }
    });

</script>

<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right" role="tablist">
            <li role="presentation" class="active"><a href="index.php">首页</a></li>
        </ul>
        <h3 class="text-muted">{$srcName}企业购员工注册</h3>
    </div>

    <div>
        {if $msg!=''}
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <strong>{$msg}</strong>
        </div>
        {/if}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div>src={$src}</div>
                <div class="btn-group" role="group">
                    <button type="button" class="b-status btn btn-default" {if $status == 3}disabled="disabled"{/if} data-status="3">全部</button>
                    <button type="button" class="b-status btn btn-default" {if $status == 1}disabled="disabled"{/if} data-status="1">激活</button>
                    <button type="button" class="b-status btn btn-default" {if $status == 0}disabled="disabled"{/if} data-status="0">未激活</button>
                </div>
            </div>
            <div class="panel-body">
                {foreach $days as $day => $day_link}
                <button style="margin-bottom: 5px;" data-dayLink="{$day_link}" type="button"
                        class="btn btn-info btn-xs">{$day}
                </button>
                {/foreach}
            </div>
        </div>
        <div style="margin: 20px 0 20px 0;">
            <textarea class="form-control" rows="15" readonly="true">{$s}</textarea>
        </div>
    </div>

    {include file="footer.html"}