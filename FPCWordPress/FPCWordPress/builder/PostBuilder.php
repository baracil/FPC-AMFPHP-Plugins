<?php
/**
 * User: Bastien Aracil
 * Date: 24/12/11
 */
 
class FPCWordPress_PostBuilder extends AbstractBuilder {

    /**
     * @param $wp_data
     * @return null|Post
     */
    protected function doTransform($wp_data) {
        $result = new FPCWordPress_Post();
        $result->id = $wp_data->ID;
        $result->authorId = $wp_data->post_author;
        $result->content = $wp_data->post_content;
        $result->title = $wp_data->post_title;

        $result->date = FPCWordPress_WordPressUtils::toAmfPhpDate($wp_data->post_date);
        $result->dateGMT = FPCWordPress_WordPressUtils::toAmfPhpDate($wp_data->post_date_gmt);

        return $result;
    }

}
