登陆：userId ; password

注册：
userId
nick
password
rptpassword
school
email
vcode

修改信息：
nick
password
npassword
rptpassword
school
email

<include file="Public/header" />
{__CONTENT__}
<include file="Public/login" />
<include file="Public/footer" />

<if condition = "empty($Think.session.user_id) eq true">
</if>