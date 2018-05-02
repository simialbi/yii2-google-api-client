<?php
/**
 * @package yii2-google-api-client
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\google\commands;

use yii\console\Controller;

/**
 * Class ApiController
 * @package simialbi\yii2\google\commands
 *
 * @property-read \simialbi\yii2\google\Module $module
 */
class ApiController extends Controller
{
    const DISCOVERY_URL = 'https://www.googleapis.com/discovery/v1/apis';
}