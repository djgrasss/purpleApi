/**
 * Namespace of notifications management 
**/
var ____Namespace = function(ko)
{
	var self = this;

	//
	// Public
	// 
	
	self.____List = ko.observableArray([]);

    /** Add a notification **/
    self.Add____ = function(data)
    {
        self.____List.push(data);
    };

    /** Remove a notification **/
    self.submitRemove____ = function(obj)
    {
        self.Notification____.remove(obj);
    }

    //
    // Private
    //
};

//Require Js stuff
var dependencies = ['tools/knockout'];
define(dependencies, function (ko) {
	return new ____Namespace(ko);
});