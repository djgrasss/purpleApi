/**
 * Namespace of notifications management 
**/
var NotificationNamespace = function(ko)
{
	var self = this;

	//
	// Public
	// 
	
	self.NotificationList = ko.observableArray([]);

    /** Add a notification **/
    self.AddNotification = function(data)
    {
        self.NotificationList.push(data);
        setTimeout(function() { removeNotificationTimer(data); }, 1000);
    };

    /** Remove a notification **/
    self.submitRemoveNotification = function(obj)
    {
        self.NotificationList.remove(obj);
    }

    //
    // Private
    //

    /** If a notification is added, automatically remove after 1 second **/
    var removeNotificationTimer = function(data)
    {
        self.submitRemoveNotification(data);
    };
};

//Require Js stuff
var dependencies = ['tools/knockout'];
define(dependencies, function (ko) {
	return new NotificationNamespace(ko);
});