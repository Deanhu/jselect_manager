{include file="header.html"}

<body>

<script type="text/javascript">
    $(function () {
        $("#form1").bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: '邮箱地址不能为空'
                        },
                        emailAddress: {
                            message: '邮箱地址格式有误'
                        }
                    }
                }
            },
            submitHandler: function (validator, form, submitButton) {
                form.submit();
                return false;
            }
        });

        $("#submit1").click(function () {
            validatorSubmit();
        });
        
        $("#register1").click(function () {
            location.href="index.php?do=user.showRegister";
        });
    });

    function validatorSubmit() {
        var bootstrapValidator = $("#form1").data('bootstrapValidator');
//            bootstrapValidator.validate();
        if (bootstrapValidator.isValid()) {
            bootstrapValidator.defaultSubmit();
        } else {
            return false;
        }
    }

</script>

<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right" role="tablist">
            <li role="presentation" class="active"><a href="index.php">首页</a></li>
        </ul>
        <h3 class="text-muted">Jselect企业购员工注册</h3>
    </div>

    {if $status==1}
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>{$msg}</strong>
    </div>
    {/if}

    {if $login_status==1}
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>{$msg}</strong>
    </div>
    {/if}

    <div class="jumbotron">
        <div style="">
            <form id="form1" role="form" class="form-horizontal" method="post" action="index.php">
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">邮箱地址</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" placeholder="请输入邮箱地址">
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <input type="hidden" name="do" value="login.loginEmail">
                        <botton id="register1" type="button" class="btn btn-default">注册</botton>
                        <botton id="submit1" type="submit" class="btn btn-primary">登录</botton>
                    </div>
                </div>
            </form>
        </div>
    </div>

{include file="footer.html"}
