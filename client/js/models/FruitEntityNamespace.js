/**
 * Namespace of notifications management 
**/
var FruitEntityNamespace = function(ko, koPurple, securityObj)
{
	var self = this;

	//
    // Private
    //
    var baseServiceUrl;
    self.FruitEntityListAjaxRef = null; 
	
	/** Utility method to get feed the view with the data got from ajax call **/
    var feedTableFromData = function(data)
    {
        self.FruitEntityListGenerationTime(data.generationTime);
        self.FruitEntityList(koPurple.objToArray(data.data.FruitList));
    };

	//
	// Public
	// 

	/** Default constructor **/
	self.init = function(serviceUrl)
	{
		baseServiceUrl = serviceUrl;
	};

    self.FruitEntityListGenerationTime = ko.observable();
    self.FruitEntityList = ko.observableArray();
    self.FruitEntity = { 
        Name : ko.observable('AlloLeMonde'), 
        Quantity : ko.observable('12'), 
        TypeId : ko.observable('1') 
    };

   	/** Refresh the html table of fruit list **/
	self.fetchFruitsTable = function()
	{             
	    var params = { action:'FRUITLIST' };
	    koPurple.jsonCall(
	        baseServiceUrl, 
	        params, 
	        function(data) { feedTableFromData(data); },
	        'GET'
	    );
	};

    /** Long time pooling ajax call for fetching table **/
    self.fetchFruitsTableRealtime = function()
    {
        //If there is a current loog time polling, kill it
        if (self.FruitEntityListAjaxRef != null)
        {
        	self.FruitEntityListAjaxRef.abort();
        }
        var params = { action:'FRUITLISTPERSISTENT', generationTime:self.FruitEntityListGenerationTime() };
        self.FruitEntityListAjaxRef = 
            koPurple.jsonCall(
                baseServiceUrl, 
                ko.toJS(params), 
                function(data) { 
                    feedTableFromData(data);
                    setTimeout(self.fetchFruitsTableRealtime, 1);
                },
                'GET'
                ,function() {
					
                }
            );
    };



	/** Method to add a fruit by calling the json service **/
	self.submitAddLine = function()
	{
	    var data = self.FruitEntity;
	    var params = securityObj.getEncryptedParamsForData(data);

	    koPurple.jsonCall(
	        baseServiceUrl + '?action=ADDFRUIT', 
	        params,
	        function(data) { self.fetchFruitsTable(); },
	        'POST'
	    );
	};   

	/** Method to remove a fruit by calling the json service **/
	self.submitRemoveLive = function(obj) 
	{
	    var data = { FruitId:obj.Id };
	    var params = securityObj.getEncryptedParamsForData(data);

	    koPurple.jsonCall(
	        baseServiceUrl + '?action=REMOVEFRUIT', 
	        params, 
	        function(data) { self.fetchFruitsTable(); },
	        'POST'
	    );
	};
};

//Require Js stuff
var dependencies = ['tools/knockout','purple/purpleFunctions', 'models/SecurityNamespace'];
define(dependencies, function (ko, koPurple, securityObj) {
	return new FruitEntityNamespace(ko, koPurple, securityObj);
});