/**
 * Application modules loading
**/

// Define the modules to include
var includes = [
    "tools/jquery"
    ,"tools/knockout"
    ,"tools/sammy"
    ,"purple/purpleFunctions"
    ,"tools/cryptojssha1"
    ,"tools/cryptojshmacsha256"
    ,"tools/jqueryui"
    ,"tools/bootstrap"
    ,"behavior"
    ,"purple/knockoutExtensions"
];

require(
    includes, 
    function(
        $,
        ko, 
        Sammy, 
        koPurple, 
        CryptoJSsha1, 
        cryptojshmacsha256, 
        jqeryUi, 
        bootstrapUi, 
        myViewModel
    ) {
            //
            // Application init
            //
            //View model creation
            document.viewModel = myViewModel;
            ko.applyBindings(document.viewModel);
            document.viewModel.init(document.jsonServerIpAndPort);
        }
    );