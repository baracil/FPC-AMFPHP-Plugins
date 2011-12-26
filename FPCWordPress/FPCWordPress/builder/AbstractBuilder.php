<?php
/**
 * User: Bastien Aracil
 * Date: 24/12/11
 */
 
abstract class AbstractBuilder {

    public function transform($in) {
        if (is_null($in)) {
            return null;
        }
        if (is_wp_error($in)) {
            return null;
        }
        return $this->doTransform($in);
    }

    protected abstract function doTransform($in);

    public function transformAll(array $ins) {
        $result= array();
        foreach ($ins as $in) {
            $result[] = $this->doTransform($in);
        }
        return $result;
    }
}
