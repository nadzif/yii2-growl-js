<?php

namespace nadzif\growl;

use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\base\Widget;
use yii\bootstrap4\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

class Growl extends Widget
{

    const TYPE_ERROR   = 'error';
    const TYPE_DANGER  = 'danger';
    const TYPE_SUCCESS = 'success';
    const TYPE_INFO    = 'info';
    const TYPE_WARNING = 'warning';

    public $icon;
    public $containerOptions = ['class' => 'd-sm-flex align-items-center justify-content-start alert-content'];
    public $title            = 'title';
    public $message          = 'message';
    public $options          = [];
    public $background       = 'alert-success';
    public $time             = 3000;

    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        self::TYPE_DANGER  => 'alert-danger',
        self::TYPE_ERROR   => 'alert-danger',
        self::TYPE_SUCCESS => 'alert-success',
        self::TYPE_INFO    => 'alert-info',
        self::TYPE_WARNING => 'alert-warning'
    ];

    public static function showMessage($title, $message, $type = 'success', $icon = 'fa fa-check', $time = 5000)
    {
        return new JsExpression("window.FloatAlert.alert('" . $title . "', '" . $message . "', '" . $type . "', '"
            . $icon . "', " . $time . ");");
    }

    public function init()
    {
        parent::init();
        $this->options = ['options' => $this->options];

        $containerId = ArrayHelper::getValue($this->containerOptions, 'id', 'growl-container');

        $this->containerOptions = ArrayHelper::merge($this->containerOptions, [
            'id' => $containerId
        ]);
    }

    public function run()
    {
        $templateContainerOptions = ArrayHelper::merge($this->containerOptions, [
            'data-time' => '_TIME_'
        ]);

        echo Html::beginTag('template', ['class' => 'alert-template']);
        Alert::begin($this->options);
        echo Html::beginTag('div', $templateContainerOptions);
        echo Html::tag('div', '_ICON_', ['class' => 'alert-icon']);
        echo Html::beginTag('div', ['class' => 'mg-sm-l-15 mg-t-15 mg-sm-t-0']);
        echo Html::tag('h5', '_TITLE_', ['class' => 'mg-b-2 pd-t-2']);
        echo Html::tag('p', '_MESSAGE_', ['class' => 'mg-b-0 tx-xs op-8']);
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::tag('div', '', ['class' => 'loading']);
        Alert::end();
        echo Html::endTag('template');

        $session = \Yii::$app->session;
        $flashes = $session->getAllFlashes();

        echo Html::beginTag('div', ['class' => 'alert-box box-side']);

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array)$flash as $i => $message) {

                $defaultAttributes = self::defaultAttributes()[$type];

                if (is_array($message)) {
                    $alertTitle   = ArrayHelper::getValue($message, 'title', $defaultAttributes['title']);
                    $alertMessage = ArrayHelper::getValue($message, 'message', '');
                    $alertIcon    = ArrayHelper::getValue($message, 'icon', $defaultAttributes['icon']);
                } else {
                    $alertTitle   = ArrayHelper::getValue($defaultAttributes, 'title', '');
                    $alertMessage = $message;
                    $alertIcon    = ArrayHelper::getValue($defaultAttributes, 'icon', '');
                }

                Html::addCssClass($this->options['options'], 'alert-' . $type);

                Alert::begin($this->options);
                echo Html::beginTag('div', $this->containerOptions);

                if ($alertIcon) {
                    echo Html::tag('div', '<i class="' . $alertIcon . '"></i>', ['class' => 'alert-icon']);
                }

                echo Html::beginTag('div', ['class' => 'mg-sm-l-15 mg-t-15 mg-sm-t-0']);
                echo Html::tag('h5', $alertTitle, ['class' => 'mg-b-2 pd-t-2']);
                echo Html::tag('p', $alertMessage, ['class' => 'mg-b-0 tx-xs op-8']);
                echo Html::endTag('div');
                echo Html::endTag('div');
                echo Html::tag('div', '', ['class' => 'loading']);
                Alert::end();
            }

            $session->removeFlash($type);
        }

        echo Html::endTag('div');

    }

    public static function defaultAttributes()
    {
        return [
            self::TYPE_ERROR   => [
                'title' => \Yii::t('app', 'Error'),
                'icon'  => 'fas fa-exclamation-circle '
            ],
            self::TYPE_DANGER  => [
                'title' => \Yii::t('app', 'Error'),
                'icon'  => 'fas fa-exclamation-circle '
            ],
            self::TYPE_SUCCESS => [
                'title' => \Yii::t('app', 'Success'),
                'icon'  => 'fa fa-check'
            ],
            self::TYPE_INFO    => [
                'title' => \Yii::t('app', 'Information'),
                'icon'  => 'fa fa-info'
            ],
            self::TYPE_WARNING => [
                'title' => \Yii::t('app', 'Warning'),
                'icon'  => 'fa fa-warning'
            ]
        ];
    }
}