<?php

namespace mervick\adminlte\debug\controllers;

use Yii;
use yii\base\ViewContextInterface;
use yii\debug\controllers\DefaultController as DebugController;

/**
 * Class DefaultController
 * @package mervick\adminlte\debug\controllers
 * @author Andrey Izman <izmanw@gmail.com>
 */
class DefaultController extends DebugController implements ViewContextInterface
{
    /**
     * @inheritdoc
     */
    public $layout = '@backend/views/layouts/main.php';

    /**
     * @inheritdoc
     */
    public function getViewPath()
    {
        return Yii::getAlias('@vendor/mervick/yii2-adminlte-debug/views/default');
    }
}
