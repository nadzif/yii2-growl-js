<?php

namespace nadzif\growl;


use yii\web\AssetBundle;

class GrowlAsset extends AssetBundle
{
    public $sourcePath = "@nadzif/growl/assets";
    public $js         = [
        "js/growl.js",
    ];
    public $css        = [
        "css/growl.css"
    ];
    public $depends    = [
        "yii\web\YiiAsset",
        "rmrevin\yii\fontawesome\AssetBundle"
    ];
}