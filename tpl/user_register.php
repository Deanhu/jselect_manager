{include file="header.html"}

<body>

<script type="text/javascript">
    $(function () {
        $("#return1").click(function () {
            location.href='index.php?do=user.showModify';
        });

        {literal}var regexp = /^[\u4e00-\u9fa5_a-zA-Z0-9]{3,15}$/i;{/literal}
        $("#register_form").bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                account: {
                    validators: {
                        notEmpty: {
                            message: '淘宝账号不能为空'
                        },
                        regexp: {
                            regexp: regexp,
                            message: '请勿使用非法字符,并且长度在3-15字符之间'
                        }
                    }
                },
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

            var p_src = GetQueryString('src');
            if(p_src != null && p_src.toString().length>1){
                $("#src1").val(p_src);
            }

            var bootstrapValidator = $("#register_form").data('bootstrapValidator');
//            bootstrapValidator.validate();
            if (bootstrapValidator.isValid()) {
                bootstrapValidator.defaultSubmit();
            } else {
                return false;
            }
        });


        function GetQueryString(name)
        {
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if(r!=null)return  unescape(r[2]); return null;
        }

        var src = '{$src}';
        if(src!=''){
            $("#src1").val(src);
        }
    });
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
    {if $modify_status==1}
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>淘宝账号信息修改成功!</strong>
    </div>
    {/if}

    <div class="jumbotron">
        <div style="">
            <form id="register_form" role="form" class="form-horizontal" method="post" action="index.php">
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">邮箱账号</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" placeholder="请输入邮箱账号">
                    </div>
                </div>
                <div class="form-group">
                    <label for="account" class="col-sm-2 control-label">淘宝账号</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="account" placeholder="请输入淘宝账号">
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="do" value="user.register">
                    <input type="hidden" id="src1" name="src" value="">
                    <botton id="return1" type="button" class="btn btn-default">修改资料</botton>
                    <botton id="submit1" type="submit" class="btn btn-primary">注册账号</botton>
                </div>
            </div>
        </form>
    </div>

    {include file="footer.html"}