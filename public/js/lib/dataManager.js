var dataManager = {
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
        App.ajaxCalls++;

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
        App.ajaxCalls--;

        // Unblock action
        if (this.contentInstance != null) {
            this.contentInstance.off('click');
        }

        // Terminate the connection
        //this.urlPrefix = '';
        //this.header = {};
    },
    ajax: function(url, method, onSuccessCallback, onFailureCallback, contentInstance) {
        if (typeof onSuccessCallback == 'undefined') {
            onSuccessCallback = function(){};
        }

        if (typeof onFailureCallback == 'undefined') {
            onFailureCallback = function(){
                console.log('Error! The ajax call was unsuccessful.');
            };
        }

        if (typeof contentInstance != 'undefined') {
            this.contentInstance = contentInstance;
        }

        that = this;
        that.startAjaxCall();

        $.ajax({
            url: that.urlPrefix + url,
            type: method,
            headers: that.header
        }).done(function(data, statusText, xhr) {
            onSuccessCallback(data, xhr.status);
            that.endAjaxCall();
        }).fail(function(data, statusText, xhr){
            onFailureCallback(data, xhr.status);
            that.endAjaxCall();
        });
    }
};