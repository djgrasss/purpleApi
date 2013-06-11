<?php
    //Just used to avoid server ip configuration ^^
    $jsonServerIpAndPort = $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'];;
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="client/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="client/css/ui-lightness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="client/css/styles.css" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <script src="client/js/jquery-1.9.1.min.js"></script>
    <script src="client/js/jquery-ui-1.10.2.custom.min.js"></script>
    <script src="client/js/knockout-2.2.1.js"></script>
    <script src="client/js/sammy-0.7.4.min.js"></script>
    <script src="client/js/bootstrap.min.js"></script>
    <script src="client/js/purpleFunctions.js"></script>
    <script src="client/js/knockoutExtensions.js"></script>
    <script src="client/js/cryptojs/sha1.js"></script>
    <script src="client/js/cryptojs/sha256.js"></script>
    <script src="client/js/cryptojs/hmac-sha256.js"></script>
    
    <script src="client/js/behavior.js"></script>
    <script type="text/javascript">
        /**
         * ViewModel instanciation
        **/
        $(function () 
        {
            document.viewModel = new myViewModel();
            ko.applyBindings(document.viewModel);
            
            var currentServerIpPort = "<?php echo $jsonServerIpAndPort; ?>";
            document.viewModel.init(currentServerIpPort);
        });
    </script>


    <!-- Top NavBar -->
    <div class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="#">PurpleApi Sample</a>
            <ul class="nav" data-bind="foreach: Pages">
                <li data-bind="css: { active: $data == $root.CurrentPageKey()} /*Set class='active' if bool ok */ "><a href="#" data-bind='text: $data, click: $root.changePage'></a></li>
            </ul>
        </div>
    </div>

    <!-- Home page -->
    <div class='container' data-bind="visible:CurrentPageKey() == 'Home'">
        <input class="span2" placeholder="username" data-bind="value:User.username" type="text"><br />
        <input class="span2" placeholder="password" data-bind="value:User.password" type="password"><br />
        <button type="submit" class="btn" data-bind="click:UserLogin">Sign in</button>
    </div>
    <!-- Fruits page -->
    <div class='container' data-bind="visible:CurrentPageKey() == 'Fruits'">

        <button id='refreshFruitTable' class='btn' data-bind='click:fetchFruitsTable'><i class="icon-refresh"></i></button>
        <!--<div>Generation timestamp : <apan id='FruitEntityListGenerationTimelbl' data-bind='text:FruitEntityListGenerationTime'></span></div>-->
        <table class='table table-striped table-bordered' id='fruitTable' data-bind="with:FruitEntityList /*, visible:FruitEntityList().length > 0*/">
            <thead>
                <tr>
                    <th>Fruit</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody data-bind="foreach:$data">
                <tr>
                    <td><a href="#" data-bind="text:Name"></a></td>
                    <td><a href="#" data-bind="text:Quantity"></a></td>
                    <td><button class='btn removeLineBtn' data-bind="click:$root.submitRemoveLive"><i class='icon-remove'></i></button></td>
                </tr>
            </tbody>
        </table>

        
        <label>Fruit</label>
        <input type='text' data-bind='value:FruitEntity.Name' />
        <label>Quantity</label>
        <input type='text' data-bind='value:FruitEntity.Quantity' />
        <label>Type</label>
        <input type="text" data-bind="value:FruitEntity.TypeId, autoComplete:{url:'server/jsonapi.php?action=FRUITTYPELIST', backFunction:$root.FruitTypeAutocompleteSelect}">
        <br />
        <button class='btn btn-primary' data-bind="click:submitAddLine">Add</button>    
    </div>
</body>
</html>