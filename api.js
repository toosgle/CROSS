Here = window.Here || {};
Here.api = {
    /**
     * 获取数据的接口
     *
     * @param string url 数据接口的url
     * @param json input 输入
     * @param json callbacks 提交后的处理函数定义，含错误(error)和成功(success)两个
     */
    get: function( url, input, callbacks ){
        input._t_ = Date.now();

        $.get( 'http://localhost/end/here/here' + url, input, function( response ){
            if ( 1 === response.no ) {
                if ( $.isFunction(callbacks.success) ) {
                    callbacks.success( response.data );
                }
            }else {
                if ( $.isFunction(callbacks.error) ) {
                    callbacks.error( response.data );
                }
            }
        }, 'json');
    },

    /**
     * 提交数据的接口
     *
     * @param string url 数据接口的url
     * @param json input 输入
     * @param json callbacks 提交后的处理函数定义，含错误(error)和成功(success)两个
     */
    post: function( url, input, callbacks ){
        if (/ \?/.test(url) ) {
            url += "&_t=" + Date.now();
        } else {
            url += "?_t=" + Date.now();
        }

        $.post( 'http://localhost/end/here/here' + url, input, function( response ) {
            if ( 1 === response.no ) {
                if ( $.isFunction(callbacks.success) ) {
                    callbacks.success( response.data );
                }
            }else {
                if ( $.isFunction(callbacks.error) ) {
                    callbacks.error( response.data );
                }
            }
        }, "json");
    }
};