/**
 * Core application
 **/
define(['knockout','jquery','sammy','purpleFunctions'], function (ko, jQuery, Sammy, koPurple) {
    return function myViewModel() {
        var self = this; 
       

        // 
        // Private Properties
        //
        var baseServiceUrl; //defined by constructor
        var useRealTime = false;
        var privateKey = ko.observable(null);

        self.Notification = ko.observableArray(['wOOt']);

        //
        // Public properties
        //
        //// Fruits 
        self.FruitEntityListGenerationTime = ko.observable();
        self.FruitEntityList = ko.observableArray();
        self.FruitEntity = { 
            Name : ko.observable('AlloLeMonde'), 
            Quantity : ko.observable('12'), 
            TypeId : ko.observable('1') 
        };
        FruitEntityListTimer = null;

        //// !Fruits
        //// User
        self.User = { username:ko.observable('eka808'), password:ko.observable('foobar') };
        self.IsLogged = ko.computed(function() { 
            return privateKey() != null;
        });
        //// !User

        // List of the application pages and current page id
        self.CurrentPageKey = ko.observable();
        self.Pages = ['Home','Fruits'];

        // 
        // Constructor 
        //
        self.init = function(currentServerIpPort) {        
            // Propagate the base service Url
            baseServiceUrl = "http://" + currentServerIpPort + "/purpleApi/server/jsonapi.php";        
            // In case of refresh, do that to be sure that needed data is loaded
            loadPageSpecificStuff(self.CurrentPageKey());

            //self.UserLogin(); console.log('===USERLOGINMOCKED===');
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
            koPurple.jsonCall(
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
            koPurple.jsonCall(
                baseServiceUrl, 
                params, 
                function(data) { feedTableFromData(data); },
                'GET'
            );
        };

        /** Method to add a fruit by calling the json service **/
        self.submitAddLine = function()
        {
            var data = self.FruitEntity;
            var params = getEncryptedParamsForData(data);

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
            var params = getEncryptedParamsForData(data);

            koPurple.jsonCall(
                baseServiceUrl + '?action=REMOVEFRUIT', 
                params, 
                function(data) { self.fetchFruitsTable(); },
                'POST'
            );
        };

        /** Remove a local notification from the array **/
        self.submitRemoveNotification = function(obj)
        {
            self.Notification.remove(obj);
        }
        

        /** Callback function of the autocomplete **/
        self.FruitTypeAutocompleteSelect = function(data) { /*console.log(data);*/ };


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
            privateKey(data.PrivateKey);
        }

        /** Long time pooling ajax call for fetching table **/
        var fetchFruitsTableRealtime = function()
        {
            //If there is a current loog time polling, kill it
            if (FruitEntityListTimer != null)
                FruitEntityListTimer.abort();

            var params = { action:'FRUITLISTPERSISTENT', generationTime:self.FruitEntityListGenerationTime() };
            FruitEntityListTimer = 
                koPurple.jsonCall(
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
            self.FruitEntityList(koPurple.objToArray(data.data.FruitList));
        };

        /** Load data specific to each page **/
        var loadPageSpecificStuff = function(data)
        {
            switch(data)
            {
                case 'Fruits':
                    // Launch the long time polling (realtime update of the fruits grid)
                    if (useRealTime)
                    {
                        fetchFruitsTableRealtime();    
                    }
                    else
                        self.fetchFruitsTable();
                break;
            }
        }

        /** Get the hash on an object **/
        var getObjectHash = function(data)
        {
            if (privateKey() == null)
            {
                alert('Please login');
                return;
            }

            var clientQueryString = koPurple.serialize(data);
            var hash = CryptoJS.HmacSHA256(clientQueryString, privateKey());
            return hash;
        }

        var getEncryptedParamsForData = function(data)
        {
            data = ko.toJS(data);
            var params =
                {
                    username : self.User.username(),
                    hash : '' + getObjectHash(data),
                    data : data
                };
            return params;
        };

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
    }
});