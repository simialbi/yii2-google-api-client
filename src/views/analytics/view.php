<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\BaseDataProvider */

$this->title = Yii::t('simialbi/google/analytics', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-google-analytics-view">
    <h1><?= Html::encode($this->title); ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider
    ]); ?>
</div>
