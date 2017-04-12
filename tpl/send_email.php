{include file="header.html"}

<body>

<script type="text/javascript">
    $(function () {
        $("#done").click(function () {
            location.href = "index.php";
            return;
        });

        $("#resend").click(function () {
            var o = this;
            get_code_time(o);
            $.ajax({
                url: 'index.php?do=email.sendEmail&email={$email}',
                dataType: 'json',
                success: function (data) {
                    console.log('status='+data['status']);
                    console.log('msg='+data['msg']);
                },
                error:function(e){
                    console.log(e);
                }
            });
        });
    });

    var wait = 30;
    get_code_time = function (o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            $("#resend").text('重发激活邮件');
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            $("#resend").text("(" + wait + ")秒后重新发送");
            wait--;
            setTimeout(function () {
                get_code_time(o);
            }, 1000);
        }
    }
</script>

<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right" role="tablist">
            <li role="presentation" class="active"><a href="index.php">首页</a></li>
        </ul>
        <h3 class="text-muted">{$srcName}企业购员工注册</h3>
    </div>

    {if $modify_status==1}
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <strong>淘宝账号信息修改成功!</strong>
    </div>
    {/if}

    <div class="jumbotron">
        <div class="text-left">
            <p>已经向您的邮箱<strong>{$email}</strong>发送了一封激活账号邮件,请您登录此邮箱点击激活链接完成本次账号激活操作.</p>
            <p>如未收到邮件,请点击下方按钮重发邮件.</p>
            <div>
                <button type="button" id="resend" class="btn btn-default">重发激活邮件</button>
                <button type="button" id="done" class="btn btn-primary">已完成激活</button>
            </div>
            <p><a href='https://detail.tmall.com/item.htm?spm=a220z.1000880.0.0.Ub0RXh&id=542635015181'>https://detail.tmall.com/item.htm?spm=a220z.1000880.0.0.Ub0RXh&id=542635015181</a></p>
            <p><img src='http://jselect.online/login_t/reg/etc/img/qr_code.png'></p>
        </div>
    </div>


    {include file="footer.html"}