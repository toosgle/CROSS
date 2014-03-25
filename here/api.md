## 约定
数据接口收敛在Here.api下，获取数据使用Here.api.get接口，提交数据使用Here.api.post接口。

返回值统一为JSON格式，其中包含两个字段：no和data；no为0时表示操作失败，接口层直接转到error处理，业务层不需要关注；data中包含的是返回值

```javascript

/**
 * 获取数据的接口
 *
 * @param string url 数据接口的url
 * @param json input 输入
 * @param json callbacks 提交后的处理函数定义，含错误(error)和成功(success)两个
 */
Here.api.get = function(url, input, callbacks, type);

/**
 * 提交数据的接口
 *
 * @param string url 数据接口的url
 * @param json input 输入
 * @param json callbacks 提交后的处理函数定义，含错误(error)和成功(success)两个
 */
Here.api.post = function(url, input, callbacks);

```

## 前台接口
### 获取用户信息
[http://localhost/end/here/here/api/get_user?username=jianling](http://localhost/end/here/here/api/get_user?username=jianling)
```javascript
url: /api/get_user
input: {
    username {String}
}
return: {
    username {String}
    nickname {String}
    groups {Array}
    photos {Array}
}
```

### 获取照片评论
[http://localhost/end/here/here/api/get_comments?photoId=1](http://localhost/end/here/here/api/get_comments?photoId=1)
```javascript
url: /api/get_comments
input: {
    photoId {Integer}
}
return: {
    comments {Array}:{
                        content {String}
                        userId {Integer}
                        username {String}
                        time {String}
                    }       
}
```


### 获取照片关注
[http://localhost/end/here/here/api/get_follows?photoId=1](http://localhost/end/here/here/api/get_follows?photoId=1)
```javascript
url: /api/get_follows
input: {
    photoId {Integer}
}
return: {
    follows {Array}:{
                        userId {Integer}
                        username {String}
                        time {String}
                    }
}
```

### 获取一组照片（只返回被所有者merge的照片）
[http://localhost/end/here/here/api/get_group?groupId=1](http://localhost/end/here/here/api/get_group?groupId=1)
```javascript
url: /api/get_group
input: {
    groupId {Integer}
}
return: {
    group {Object}:{
                        name {Integer}
                        userId {Integer}
                        username {String}
                        photos {Array}:{
                                            photoId {Integer}
                                            userId {Integer}
                                            username {String}
                                            position {String}
                                            environment {String}
                                            measurement {String}
                                            direction {String}
                                            url {String}
                                        }
                    }
}
```

### 发起关注
[http://localhost/end/here/here/api/get_group?groupId=1](http://localhost/end/here/here/api/get_group?groupId=1)
```javascript
url: /api/follow
input: {
    photoId {Integer}
}
```



### 发起评论
[http://localhost/end/here/here/api/comment?photoId=1&content=fafds](http://localhost/end/here/here/api/comment?photoId=1&content=fafds)
```javascript
url: /api/comment
input: {
    photoId {Integer}
    content {String}
}
```


### 上传照片
```javascript
url: /api/upload
input: {
    userId {Integer}
    groupId {Integer}
    file {File}
}
return: {
    url {String}
}
```