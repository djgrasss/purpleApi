/**
 * Namespace of notifications management 
**/
var UserEntityNamespace = function(ko, koPurple)
{
	//console.log('userNamespace loaded');
	var self = this;

    var baseServiceUrl;

	//
	// Public
	// 
    
    self.UserList = ko.observableArray([]);
    
    /** Default constructor **/
    self.init = function(serviceUrl)
    {
        baseServiceUrl = serviceUrl;
    }; 

    /** Refresh the html table of fruit list **/
    self.fetchFruitsTable = function()
    {             
        var params = { action:'USERLIST' };
        koPurple.jsonCall(
            baseServiceUrl, 
            params, 
            function(data) { 
                self.UserList(data);
                //console.log(self.UserList());
            },
            'GET'
        );
    };    

    /** Add a notification **/
    // self.AddUser = function(data)
    // {
    //     self.UserList.push(data);
    // };

    /** Remove a notification **/
    // self.submitRemoveUser = function(obj)
    // {
    //     self.NotificationUser.remove(obj);
    // }

    //
    // Private
    //
};

//Require Js stuff
var dependencies = ['tools/knockout', 'purple/purpleFunctions'];
define(dependencies, function (ko, koPurple) {
	return new UserEntityNamespace(ko, koPurple);
});