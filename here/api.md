## 约定
数据接口放在Here.api下，获取数据使用Here.api.get接口，提交数据使用Here.api.post接口

```javascript

/**
 * 获取数据的接口
 *
 * @param string url 数据接口的url
 * @param json input 输入
 * @param json callbacks 提交后的处理函数定义，含错误(error)和成功(success)两个
 * @param string 返回类型，和jquery的接口一致，默认为json
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
```javascript
url: /user/get_user
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
```javascript
url: /user/get_comments
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
```javascript
url: /user/get_follows
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
```javascript
url: /user/get_group
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
```javascript
url: /user/follow
input: {
    photoId {Integer}
    userId {Integer}
}
```

### 发起评论
```javascript
url: /user/comment
input: {
    photoId {Integer}
    userId {Integer}
    content {String}
}
```

### 上传照片
```javascript
url: /user/upload
input: {
    userId {Integer}
    groupId {Integer}
    file {File}
}
return: {
    url {String}
}
```