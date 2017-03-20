{include file="header.html"}

<body>

<script type="text/javascript">
    $(function () {
        $("#modify_button").click(function () {
            $("form").submit();
        });
    });
</script>

<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right" role="tablist">
            <li role="presentation" class="active"><a href="index.php">首页</a></li>
        </ul>
        <h3 class="text-muted">Jselect企业购员工注册</h3>
    </div>

    {if $modify_status==1}
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>淘宝账号信息修改成功!</strong> 
    </div>
    {/if}
    {if $login_status==1}
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>{$msg}</strong>
    </div>
    {/if}

    <div class="jumbotron">
        <div class="text-left">
            <h3>欢迎使用Jselect企业购员工注册系统!</h3>
            <br/>
            <p>您绑定的资料:</p>
            <p>淘宝账号:<strong>{$account}</strong></p>
            <p>邮箱地址:<strong>{$email}</strong></p>
            <p>
                <form id="modify_form" method="post" action="index.php">
                    <input type="hidden" name="do" value="user.showModify" />
                    <input type="hidden" name="email" value="{$email}"/>
                    <button id="modify_button" type="submit" class="btn btn-default btn-sm">修改</button>
                </form>
            </p>
        </div>
    </div>


    {include file="footer.html"}