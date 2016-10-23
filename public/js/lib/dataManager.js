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
        }).fail(function(data, statusText, xhr){
            onFailureCallback(data, xhr.status);
            that.endAjaxCall();
        });
    },
    getCookie: function(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }
};