# yii2-growl-js

Configuration

```php
\nadzif\growl\GrowlAsset::register($this);
```

Adding widget to your view

```php
echo \nadzif\growl\Growl::widget([
    'options' => [
        'class' => ' alert-solid'
    ],
    'time'    => 3000 //default
]);
```

Call with js
```php
echo Html::button('abc', ['onclick' => \nadzif\growl\Growl::showMessage($title, $message)]);

echo Html::button('abc', ['onclick' => \nadzif\growl\Growl::showMessage($title, $message, $type, $icon, $time)]);
```