<?php
/**
 * User: Bastien Aracil
 * Date: 24/12/11
 */
 
class FPCWordPress_UserBuilder extends AbstractBuilder {

    protected function doTransform($data) {
        $result = new FPCWordPress_User();
        $result->id = $data->ID;
        $result->login = $data->user_login;
        $result->niceName = $data->user_nicename;
        $result->email = $data->user_email;
        $result->url = $data->user_url;
        $result->registered = FPCWordPress_WordPressUtils::toAmfPhpDate($data->user_registered);
        $result->displayName = $data->display_name;
        $result->firstName = $data->user_firstname;
        $result->lastName = $data->user_lastname;
        $result->nickName = $data->nickname;
        $result->description = $data->user_description;
        $result->capabilities = $data->wp_capabilities;
        $result->adminColor = $data->admin_color;
        $result->primaryBlog = $data->primary_blog;
        $result->richEditing = $data->rich_editing;
        $result->sourceDomain = $data->source_domain;
        return $result;
    }

}
