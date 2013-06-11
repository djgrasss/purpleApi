/**
 * Core application
 **/
function myViewModel() {
    var self = this; 

    //
    // Public properties
    //
    //// Fruits 
    self.FruitEntityListGenerationTime = ko.observable();
    self.FruitEntityList = ko.observableArray();
    self.FruitEntity = { Name : ko.observable(''), Quantity : ko.observable(''), TypeId : ko.observable() }    
    //// !Fruits
    //// User
    self.User = { username:ko.observable(), password:ko.observable() };
    //// !User

    // List of the application pages and current page id
    self.CurrentPageKey = ko.observable();
    self.Pages = ['Home','Fruits'];

    // 
    // Private Properties
    //
    var baseServiceUrl; //defined by constructor
    var useRealTime = false;

    var privateKey = null;

    // 
    // Constructor 
    //
    self.init = function(currentServerIpPort) {        
        // Propagate the base service Url
        baseServiceUrl = "http://" + currentServerIpPort + "/purpleApi/server/jsonapi.php";        
        // In case of refresh, do that to be sure that needed data is loaded
        loadPageSpecificStuff(self.CurrentPageKey());
    };

    // 
    // Public Methods 
    //
    self.UserLogin = function()
    {        
        var params = { 
            action:'GETPRIVATEKEY', 
            username:self.User.username, 
            encryptedPassword:"" + CryptoJS.SHA1(self.User.password()) //Encrypt the password before passing
        };
        ko.Purple.jsonCall(
            baseServiceUrl, 
            ko.toJS(params), 
            function(data) { UserLoginCallBack(data); },
            'GET'
        );        
    }

    /** Refresh the html table of fruit list **/
    self.fetchFruitsTable = function()
    {                
        var params = { action:'FRUITLIST' };
        ko.Purple.jsonCall(
            baseServiceUrl, 
            params, 
            function(data) { feedTableFromData(data); },
            'GET'
        );
    };

    /** Method to add a fruit by calling the json service **/
    self.submitAddLine = function(obj)
    {
        var params = self.FruitEntity;
        ko.Purple.jsonCall(
            baseServiceUrl + '?action=ADDFRUIT', 
            ko.toJS(params),
            function(data) { self.fetchFruitsTable(); },
            'POST'
        );
    };   
    
    /** Method to remove a fruit by calling the json service **/
    self.submitRemoveLive = function(obj) 
    {
        var params = { FruitId:obj.Id };
        ko.Purple.jsonCall(
            baseServiceUrl + '?action=REMOVEFRUIT', 
            params, 
            function(data) { self.fetchFruitsTable(); },
            'POST'
        );
    };

    /** Callback function of the autocomplete **/
    self.FruitTypeAutocompleteSelect = function(data) 
    {
        //console.log(data);
    };

    // 
    // Private Methods 
    //

    /** Callback after try to login the user */
    var UserLoginCallBack = function(data)
    {
        // Manage the bad authentication return
        if (data == "IncorrectAuthParameters")
        {
            alert("Incorrect username/password");
            return;
        }
        // Set the private key locally
        privateKey = data.PrivateKey;
    }


    /** Long time pooling ajax call for fetching table **/
    var fetchFruitsTableRealtime = function()
    {
        var params = { action:'FRUITLISTPERSISTENT', generationTime:self.FruitEntityListGenerationTime() };
        ko.Purple.jsonCall(
            baseServiceUrl, 
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
    
    /** Utility method to get feed the view with the data got from ajax call **/
    var feedTableFromData = function(data)
    {
        self.FruitEntityListGenerationTime(data.generationTime);
        self.FruitEntityList(ko.Purple.objToArray(data.data.FruitList));
    };

    /** Load data specific to each page **/
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
        this.get('', function() { this.app.runRoute('get', '#Home') });
    }).run();

    /** Change page event from the menu, Tell sammy that the current page selected is curPage **/
    self.changePage = function(curPage) { location.hash = curPage; };    

    // 
    // Events
    //
    this.CurrentPageKey.subscribe(function(data) { loadPageSpecificStuff(data); });
};