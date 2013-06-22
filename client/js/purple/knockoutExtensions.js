define(['tools/knockout','jquery' ,'purple/purpleUiTable.jquery'], function (ko, jQuery) {
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

/**
 * Table generator binding handler
 */
// ko.bindingHandlers.purpleTable = {
//     // init: function(element, valueAccessor, allBindingsAccessor, viewModel) {
//     // },
//     update: function(element, valueAccessor, allBindingsAccessor, viewModel) {
//         //Get properties from the data bind parameters
//         var columns = valueAccessor().columns;
//         var tableCssClass = valueAccessor().tableCssClass;
        
//         //Get what's in the data: binding
//         var data = allBindingsAccessor().data();
        
//         //Call the jquery plugin
//         if (data.length > 0)
//             $(element).purpleUiTable({
//                 data:data,
//                 columns:columns,
//                 tableCssClass:tableCssClass
//             });
//     }
// };

ko.bindingHandlers.createTheadRow =
{
    update: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext)
    {
        if($(element).html() == '')
        if (valueAccessor() != null)
        for (var key in valueAccessor())
            $(element).prepend('<th>' + key + '</th>');
    }
};
ko.bindingHandlers.createTbodyRow =
{
    update: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext)
    {
        if (valueAccessor() != null)
        for (var key in valueAccessor())
            $(element).prepend('<td data-bind="text:' + key + '"></td>');
    }
};

});