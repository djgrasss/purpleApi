/**
 * Namespace of notifications management 
**/
var SecurityNamespace = function(ko, koPurple)
{
	var self = this;

	// 
	// Private
	// 

	var privateKey = ko.observable(null); //This needs to be an observable for the computed function IsLogged
	var baseServiceUrl = null;
    var AuthenticationCallbackFunction = null;

    /** Callback after try to login the user */
    var UserLoginCallBack = function(data)
    {
        // Manage the bad authentication return
        if (data == "IncorrectAuthParameters")
        {
        	AuthenticationCallbackFunction('Incorrect username/password');
            return;
        }
        // Set the private key locally
        privateKey(data.PrivateKey);
        // Notify user
        AuthenticationCallbackFunction('Logged in');
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

	//
	// Public
	// 
    
    self.User = { username:ko.observable('eka808'), password:ko.observable('foobar') };
	
	/** Default constructor **/
	self.init = function(serviceUrl, callbackFunction)
	{
		baseServiceUrl = serviceUrl;
		AuthenticationCallbackFunction = callbackFunction;
	};

	/** Check if a user has got a private key **/
    self.IsLogged = ko.computed(function() { 
        return privateKey() != null;
    });	

    /** Make the login call **/
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

    /** Get the hash for the data sent **/
	self.getEncryptedParamsForData = function(data)
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
};

//Require Js stuff
var dependencies = ['tools/knockout','purple/purpleFunctions'];
define(dependencies, function (ko, koPurple) {
	return new SecurityNamespace(ko, koPurple);
});