<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>用户登录</title>
    </head>
    <body>
        <fieldset> 
            <legend>用户登录</legend> 
            <form name="registerForm" method="post" action="?m=here&c=user&a=login"> 
                <p> 
                    <label for="username" class="label">用户名:</label> 
                    <input id="username" name="username" type="text" class="input" /> 
                <p/> 
                <p> 
                    <label for="password" class="label">密 码:</label> 
                    <input id="password" name="password" type="password" class="input" /> 
                <p/>
                <p>
                    <input type="submit" name="submit" value=" 确 定 " class="left" /> 
                </p> 
            </form> 
        </fieldset> 
    </body>
</html>