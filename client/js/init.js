/**
 * Application modules loading
**/

// Define the modules to include
var includes = [
    //"test"  // client/js/test.js
    "jquery"
    ,"knockout"
    ,"sammy"
    ,"purpleFunctions"
    ,"cryptojssha1"
    ,"cryptojshmacsha256"
    ,"jqueryui"
    ,"bootstrap"
    ,"behavior"
    //,"knockoutExtensions"
];

require(includes, function($,ko, Sammy, koPurple, CryptoJSsha1, cryptojshmacsha256, jqeryUi, bootstrapUi, myViewModel) {
    //All modules loaded : application init

    //View model creation
    document.viewModel = new myViewModel();
    ko.applyBindings(document.viewModel);
    
    var currentServerIpPort = document.jsonServerIpAndPort;
    document.viewModel.init(currentServerIpPort);
});