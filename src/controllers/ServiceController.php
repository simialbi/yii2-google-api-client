<?php
/**
 * @package yii2-google-api-client
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\google\controllers;


use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\web\Controller;
use Yii;

/**
 * Class ServiceController
 *
 * @property-read \simialbi\yii2\google\Module $module
 */
class ServiceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $class = strtolower(str_replace('Controller', '', StringHelper::basename(static::class)));
        Yii::configure($this, ArrayHelper::getValue($this->module->serviceConfiguration, $class, []));
    }
}