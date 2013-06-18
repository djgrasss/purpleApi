define(['tools/knockout','jquery'], function (ko, jQuery) {
/** 
 * Autcomplete binding Handler 
 * JSON api should implement array of objects like {id:1,label:'foo'}
**/
ko.bindingHandlers.autoComplete = {
    init: function (element, params) {
        var paramsLocal = ko.utils.unwrapObservable(params());

    	$(element).autocomplete({
            source: function( request, response ) {
            $.ajax({
                url: paramsLocal.url,
                dataType: "json",
                data: {search: request.term},
                success: function(data) {
                            response($.map(data, function(item) {
                            return item;
                        }));
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
            	params().backFunction(ui.item);
            }
        });
    },
    update: function (element, params) {
    	var paramsLocal = ko.toJS(params());
        //$(element).autocomplete(params());
    }
};

});