var dataManager = {
    ajaxCalls: 0,
    contentInstance: null,
    urlPrefix: '',
    options: {},
    header: {},

    config: function(options){
        this.options = options;

        return this;
    },
    connect: function(type, auth) {
        this.urlPrefix = this.options[type];

        if (typeof auth != 'undefined') {
            this.header = {
                'Authorization': auth
            }
        }

        return this;
    },
    startAjaxCall: function() {
        // Inform the application that an ajax request has been started
        this.ajaxCalls++;

        // Block action
        if (this.contentInstance != null) {
            this.contentInstance.on('click', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
            });
            // @todo to check is it possible to block hover action as well!
        }
    },
    endAjaxCall: function() {
        // Inform the application that the ajax request has been completed
        this.ajaxCalls--;

        // Unblock action
        if (this.contentInstance != null) {
            this.contentInstance.off('click');
        }

        // Terminate the connection
        //this.urlPrefix = '';
        //this.header = {};
    },
    ajax: function(url, method, input_data, onSuccessCallback, onFailureCallback, contentInstance) {
        if (typeof onSuccessCallback == 'undefined') {
            onSuccessCallback = function(){};
        }

        if (typeof onFailureCallback == 'undefined') {
            onFailureCallback = function(){
                //console.log('Error! The ajax call was unsuccessful.');
            };
        }

        if (typeof contentInstance != 'undefined') {
            this.contentInstance = contentInstance;
        }

        that = this;
        that.startAjaxCall();

        // Prepare the request
        var request_package = {
            url: that.urlPrefix + url,
            type: method,
            headers: that.header,
            contentType: "application/json; charset=utf-8"
        }
        if(method != 'GET') {
            request_package['data'] = JSON.stringify(input_data);
        }

        // Send request
        $.ajax(request_package).done(function(data, statusText, xhr) {
            onSuccessCallback(data, xhr.status);
            that.endAjaxCall();
        }).fail(function(data){
            onFailureCallback($.parseJSON(data.responseText), data.status);
            that.endAjaxCall();
        });
    },
    ajaxFileTransfer: function(url, method, input_data, onSuccessCallback, onFailureCallback, contentInstance) {
        /* Usage:
            var file_data = $('#ID_OF_THE_FILE_INPUT').prop('files')[0];

            if(file_data != undefined) {
                var input_data = new FormData();
                input_data.append('file', file_data);
                input_data.append("VAR_NAME", VALUE_OF_THE_VAR);

                dataManager.connect('mapper').ajaxFileTransfer('/SOME-URL/json', input_data, function(data, http_code){
                    console.log(data);
                }, function(data, http_code){
                    // None
                });
            }
        */

        if (typeof onSuccessCallback == 'undefined') {
            onSuccessCallback = function(){};
        }

        if (typeof onFailureCallback == 'undefined') {
            onFailureCallback = function(){
                //console.log('Error! The ajax call was unsuccessful.');
            };
        }

        if (typeof contentInstance != 'undefined') {
            this.contentInstance = contentInstance;
        }

        that = this;
        that.startAjaxCall();

        // Prepare the request
        var request_package = {
            type: method,
            url: that.urlPrefix + url,
            data: input_data,
            contentType: false,
            processData: false
        }

        // Send request
        $.ajax(request_package).done(function(data, statusText, xhr) {
            onSuccessCallback(data, xhr.status);
            that.endAjaxCall();
        }).fail(function(data){
            onFailureCallback($.parseJSON(data.responseText), data.status);
            that.endAjaxCall();
        });
    },
    getCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    },
    setCookie: function(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }
};