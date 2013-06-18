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
    <script type='text/javascript'>
        document.jsonServerIpAndPort = "<?php echo $jsonServerIpAndPort; ?>";
    </script>
    <script type="text/javascript" data-main="client/js/init.js" src="client/js/tools/require.js"></script>



    <!-- Top NavBar -->
    <div class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="#">PurpleApi Sample</a>
            <ul class="nav" data-bind="foreach: spaObj.Pages">
                <li data-bind="css: { active: $data == $root.spaObj.CurrentPageKey()} /*Set class='active' if bool ok */ "><a href="#" data-bind='text: $data, click: $root.spaObj.changePage'></a></li>
            </ul>
        </div>
    </div>

    <script type="text/html" id="alertTemplate">
    <div class="alert">
        <button type="button" class="close" data-bind="click:$root.submitRemoveNotification">&times;</button>
        <span data-bind="text:note"></span>
    </div>    
    </script>
    <div data-bind="template:{ name:'alertTemplate', foreach:notificationObj.NotificationList, as:'note' }"></div>

    <!-- Home page -->
    <div class='container' data-bind="visible:spaObj.CurrentPageKey() == 'Home'">
        <input class="span2" placeholder="username" data-bind="value:securityObj.User.username" type="text"><br />
        <input class="span2" placeholder="password" data-bind="value:securityObj.User.password" type="password"><br />
        <button type="submit" class="btn" data-bind="click:securityObj.UserLogin">Sign in</button>
    </div>
    <!-- Fruits page -->
    <div class='container' data-bind="visible:spaObj.CurrentPageKey() == 'Fruits'">

        <button id='refreshFruitTable' class='btn' data-bind='click:fruitEntityObj.fetchFruitsTable'><i class="icon-refresh"></i></button>
        <!--<div>Generation timestamp : <apan id='FruitEntityListGenerationTimelbl' data-bind='text:FruitEntityListGenerationTime'></span></div>-->
        <table class='table table-striped table-bordered' id='fruitTable' data-bind="with:fruitEntityObj.FruitEntityList">
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
                    <td><button class='btn removeLineBtn' data-bind="click:$root.fruitEntityObj.submitRemoveLive, visible:$root.securityObj.IsLogged()"><i class='icon-remove'></i></button></td>
                </tr>
            </tbody>
        </table>

        <div data-bind="visible:securityObj.IsLogged()">        
            <label>Fruit</label>
            <input type='text' data-bind='value:fruitEntityObj.FruitEntity.Name' />
            <label>Quantity</label>
            <input type='text' data-bind='value:fruitEntityObj.FruitEntity.Quantity' />
            <label>Type</label>
            <input type="text" data-bind="value:fruitEntityObj.FruitEntity.TypeId, autoComplete:{url:'server/jsonapi.php?action=FRUITTYPELIST', backFunction:$root.FruitTypeAutocompleteSelect}">
            <br />
            <button class='btn btn-primary' data-bind="click:fruitEntityObj.submitAddLine">Add</button>    
        </div>
    </div>
</body>
</html>