<?php
/**
 * User: Bastien Aracil
 * Date: 21/12/11
 */
 
class FPCWordPress_WordPressUtils {

    public static function toAmfPhpDate($strTime) {
        $tmp = new DateTime($strTime);
        return new Amfphp_Core_Amf_Types_Date($tmp->getTimestamp()*1000);
    }


}
