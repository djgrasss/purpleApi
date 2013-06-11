/**
 * Core application
 **/
function myViewModel() {
    var self = this; 

    //
    // Public properties
    //
    self.GenerationTime = ko.observable();
    self.EntityList = ko.observableArray();
    self.Entity =         
        {
            Name : ko.observable('RandomFruit')
            ,Quantity : ko.observable('10')
            ,TypeId : ko.observable()
        }

    // List of the application pages and current page id
    self.CurrentPageKey = ko.observable();
    self.Pages = ['Home','Fruits'];

    // 
    // Private Properties
    //
    var serverIpPort; //defined by constructor
    var baseServiceUrl; //defined by constructor
    var useRealTime = false;

    // 
    // Constructor 
    //
    self.init = function(currentServerIpPort) {
        
        // Propagate the server ip & port got by viewmodel instanciation
        serverIpPort = currentServerIpPort;
        // Propagate the base service Url
        baseServiceUrl = "http://" + serverIpPort + "/purpleSoa/server/jsonapi.php";        
        // In case of refresh, do that to be sure that needed data is loaded
        loadPageSpecificStuff(self.CurrentPageKey());
    };

    // 
    // Public Methods 
    //
        
    /** 
     * Refresh the html table of fruit list
    **/
    self.fetchFruitsTable = function()
    {        
        var url = baseServiceUrl;
        var params = 
            {
                action:'FRUITLIST'
            };
        ko.Purple.jsonCall(
            url, 
            params, 
            function(data) { feedTableFromData(data); },
            'GET'
        );
    };

    /**
     * Method to add a fruit by calling the json service
    **/
    self.submitAddLine = function(obj)
    {
        var url = baseServiceUrl + '?action=ADDFRUIT';
        var params = self.Entity;
        ko.Purple.jsonCall(
            url, 
            ko.toJS(params),
            function(data) { self.fetchFruitsTable(); },
            'POST'
        );
    };   
    
    /**
     * Method to remove a fruit by calling the json service
    **/
    self.submitRemoveLive = function(obj) 
    {
        var url = baseServiceUrl + '?action=REMOVEFRUIT';
        var params = { FruitId:obj.Id };
        ko.Purple.jsonCall(
            url, 
            params, 
            function(data) { self.fetchFruitsTable(); },
            'POST'
        );
    };

    /**
     * Callback function of the autocomplete
    **/
    self.FruitTypeAutocompleteSelect = function(item) 
    {
        //console.log(item);
    };

    /**
     * Change page event from the menu
    **/
    self.changePage = function(curPage) 
    { 
        // Tell sammy that the current page selected is curPage
        location.hash = curPage;
    };    

    // 
    // Private Methods 
    //

    /**
     * Long time pooling ajax call for fetching table
    **/
    var fetchFruitsTableRealtime = function()
    {
        var url = baseServiceUrl;
        var params = 
            {
                action:'FRUITLISTPERSISTENT',
                generationTime:self.GenerationTime()
            };
        ko.Purple.jsonCall(
            url, 
            ko.toJS(params), 
            function(data) { 
                feedTableFromData(data); 
                setTimeout(fetchFruitsTableRealtime, 1000);
            },
            'GET',
            function() {
                setTimeout(fetchFruitsTableRealtime, 1500);  
            }
        );
    };
    
    /**
     * Utility method to get feed the view with the data got from ajax call
    **/
    var feedTableFromData = function(data)
    {
        self.GenerationTime(data.generationTime);
        self.EntityList(ko.Purple.objToArray(data.data.FruitList));
    };

    var loadPageSpecificStuff = function(data)
    {
        switch(data)
        {
            case 'Fruits':
                // Launch the long time polling (realtime update of the fruits grid)
                if (useRealTime)
                    fetchFruitsTableRealtime();    
                else
                    self.fetchFruitsTable();
            break;
        }
    }

    //
    // Routing
    //

    Sammy(function() {
        this.get('#:curPage', function() {
            //Sets that the current page key is the one got by sammy
            curPageLocal = this.params.curPage;
            self.CurrentPageKey(curPageLocal);
        });
        //Default routing
        this.get('', function() { this.app.runRoute('get', '#curPage') });
    }).run();    

    // 
    // Events
    //

    this.CurrentPageKey.subscribe(function(data) { loadPageSpecificStuff(data); });
};