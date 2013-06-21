/**
 * Core application
 **/
var dependencies = 
    [
        'tools/knockout'
        ,'purple/purpleFunctions'
        ,'models/NotificationNamespace'
        ,'models/SpaNamespace'
        ,'models/SecurityNamespace'
        ,'models/FruitEntityNamespace'
        ,'models/UserEntityNamespace'
    ];
define(dependencies, function (ko, koPurple, notificationsObj, spaObj, securityObj, fruitEntityObj, userEntityObj) {
    var myViewModel = function() {
        var self = this; 

        //
        // Public properties
        //
        
        //@refactor (used for view rendering of list)
        self.notificationObj = notificationsObj;
        self.spaObj = spaObj;
        self.securityObj = securityObj;
        self.fruitEntityObj = fruitEntityObj;
        self.userEntityObj = userEntityObj;


        // 
        // Private properties
        //
        
        var baseServiceUrl;
        var useRealTime = false;

        var authenticationCallbackFunction = function(data)
        {
            notificationsObj.AddNotification(data);
        }
        
        // 
        // Private methods
        // 

        /** Load data specific to each page **/
        var loadPageSpecificStuff = function(data)
        {

            // Kill long time polling if existing
            if (self.fruitEntityObj.FruitEntityListAjaxRef != null)
                self.fruitEntityObj.FruitEntityListAjaxRef.abort();

            // Routing
            switch(data)
            {
                case 'Fruits':
                    // Launch the long time polling (realtime update of the fruits grid)
                    if (useRealTime)
                        self.fruitEntityObj.fetchFruitsTableRealtime();    
                    else
                        self.fruitEntityObj.fetchFruitsTable();
                break;
                case 'Users':
                    self.userEntityObj.fetchFruitsTable();
                break;
            }
        }

        //
        // Public methods
        //

        /** Default constructor **/
        self.init = function(currentServerIpPort) {    

            // Propagate the base service Url
            baseServiceUrl = "http://" + currentServerIpPort + "/purpleApi/server/jsonapi.php";        

            // Inits
            securityObj.init(baseServiceUrl, authenticationCallbackFunction);
            fruitEntityObj.init(baseServiceUrl);
            userEntityObj.init(baseServiceUrl);

            // In case of refresh, to be sure that needed data is loaded
            loadPageSpecificStuff(spaObj.CurrentPageKey());
            

            self.securityObj.UserLogin(); console.log('===USERLOGINMOCKED===');
        };

        /** Callback function of the autocomplete **/
        self.FruitTypeAutocompleteSelect = function(data) { /*console.log(data);*/ };

        // 
        // Events
        //
        self.spaObj.CurrentPageKey.subscribe(function(data) { loadPageSpecificStuff(data); });
    };

    return new myViewModel();
});