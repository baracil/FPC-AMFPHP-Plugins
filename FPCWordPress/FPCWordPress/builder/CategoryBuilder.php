<?php
/**
 * User: Bastien Aracil
 * Date: 24/12/11
 */
 
class FPCWordPress_CategoryBuilder extends AbstractBuilder {

    protected function doTransform($wpCategory)
    {
        $result = new FPCWordPress_Category();

        if (is_object($wpCategory)) {
            $result->id = $wpCategory->cat_ID;
            $result->count = $wpCategory->category_count;
            $result->description = $wpCategory->category_description;
            $result->name = $wpCategory->cat_name;
            $result->niceName = $wpCategory->category_nicename;
            $result->parent = $wpCategory->category_parent;
        }
        elseif (is_array($wpCategory)) {
            $result->id = $wpCategory['cat_ID'];
            $result->count = $wpCategory['category_count'];
            $result->description = $wpCategory['category_description'];
            $result->name = $wpCategory['cat_name'];
            $result->niceName = $wpCategory['category_nicename'];
            $result->parent = $wpCategory['category_parent'];
        }

        return $result;
    }


}
