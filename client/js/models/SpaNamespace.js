
/**
 * Namespace of notifications management 
**/
var SpaNamespace = function(ko, Sammy)
{
	var self = this;

	//
	// Public
	// 

    // List of the application pages and current page id
    self.CurrentPageKey = ko.observable();
    self.Pages = ['Home','Fruits'];	

    /** Change page event from the menu, Tell sammy that the current page selected is curPage **/
    self.changePage = function(curPage) { 
        location.hash = curPage; 
    };  
    
    //
    // Private
    //

    /** Sammy routing **/
    Sammy(function() {
        this.get('#:curPage', function() {
            //Sets that the current page key is the one got by sammy
            curPageLocal = this.params.curPage;
            self.CurrentPageKey(curPageLocal);
        });
        //Default routing
        this.get('', function() { this.app.runRoute('get', '#Home') });
    }).run();

};

//Require Js stuff
var dependencies = ['tools/knockout','tools/sammy'];
define(dependencies, function (ko, Sammy) {
	return new SpaNamespace(ko, Sammy);
});