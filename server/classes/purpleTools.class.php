<?php
/*
$index = array_search($fruitName,$this->dao->PersistenceDataObj->data->FruitList);
if($index !== FALSE){
    unset($fruitName,$this->dao->PersistenceDataObj->data->FruitList[$index]);
}
*/

/**
 * Swiss army knife
 * @author eka808
**/
class purpleTools
{

    /**
     * Get JSON object from url
    **/
    public static function getJsonObjFromUrl($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $json_txt = curl_exec($curl);
        curl_close($curl);
        return json_decode($json_txt);
    }

    /**
     * Uber simple sanitize method
     * @param theString : the string we want to sanitize
     * @return sanitized theString
    **/
    public static function sanitizeString($theString)
    {
        if (is_null($theString))
            return;
        return preg_replace('/[^-a-zA-Z0-9_]/', '', $theString);
    }

    public static function sanitizeArray($theArray)
    {
        foreach ($theArray as $key => $value)
            $theArray[$key] = self::sanitizeString($value);
        return $theArray;
    }


    /**
     * Standard debug configuration init
    **/
    public static function initDebug()
    {
        //Debug init
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'On');
    }

    /**
     * Simply cast an array as a proper class instanciation
     * @param array : the array we want to convert as className object
     * @param className : the class name to cast as
     * @return object
    **/
    static function arrayToObject(array $array, $className) {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(serialize($array), ':')
        ));
    }

    static function arrayToQueryString($array)
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }


    /**
     * Simply cast an object as a class type
     * @param instance : the instance we want to convert as className object
     * @param className : the class name to cast as
     * @return object
    **/
    static function objectToObject($instance, $className) {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
    }

    /**
     * Call a function by his reference
     * @param $func : the name or the function reference
     * @param $paramsArray : the parameters to pass to the function as an array
     * @author eka808
    **/
    static function functionCaller ($callingObj, $func) 
    {
        // Manage the null function case
        if ($func == null)
            throw new PurpleUnknownRouteException();

        ////$func('moo');
        // Get the function parameters by reflection
        $functionArgs = func_get_args();

        // Remove the first one (the referent call object)
        // the second one (the function reference), 
        // and tada ! the array of the params to pass to the function ! ^^
        unset($functionArgs[0]);
        unset($functionArgs[1]);

        // Make the function reference call
        return
            call_user_func_array(
                array(
                    &$callingObj,
                    (string)$func
                ), 
                $functionArgs
            );
    }
}

class PurpleUnknownRouteException extends Exception { }
?>