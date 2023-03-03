<?php
namespace App\Models\Traits;

use function PHPUnit\Framework\isNull;

trait CleanString{  

    /**
     * Boot trait
     *
     * @return void
     */
    protected static function bootCleanString()
    {
        static::creating(function ($model) {
            foreach ($model->cleanable() as $attribute) {
                if(is_numeric($model->{$attribute}) || is_null($model->{$attribute})) {
                    dd($model->{$attribute});
                    continue;
                }

                $model->setAttribute(
                    $attribute, 
                    $model->cleanStr($model->{$attribute})
                );
            }
        });
    }

    /**
     * Revove especial charaters
     *
     * @param string $str
     * @return void
     */
    private static function cleanStr(string $str) {
        $str = htmlentities($str, ENT_COMPAT, "UTF-8");
        $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|ring);/','$1',$str);
        return html_entity_decode($str);
    }

    /**
     * attributes to clean
     *
     * @return array
     */
    abstract public function cleanable(): array;
}