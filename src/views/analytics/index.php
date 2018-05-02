<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\BaseDataProvider */

$this->title = Yii::t('simialbi/google/analytics', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-google-analytics">
    <h1><?= Html::encode($this->title); ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'accountId',
                'label' => Yii::t('simialbi/google/analytics', 'Account id')
            ],
            [
                'attribute' => 'accountKind',
                'label' => Yii::t('simialbi/google/analytics', 'Account kind')
            ],
            [
                'attribute' => 'accountName',
                'label' => Yii::t('simialbi/google/analytics', 'Account name')
            ],
            [
                'attribute' => 'accountStarred',
                'label' => Yii::t('simialbi/google/analytics', 'Account starred')
            ],
            [
                'attribute' => 'propertyId',
                'label' => Yii::t('simialbi/google/analytics', 'Property id')
            ],
            [
                'attribute' => 'propertyKind',
                'label' => Yii::t('simialbi/google/analytics', 'Property kind')
            ],
            [
                'attribute' => 'propertyLevel',
                'label' => Yii::t('simialbi/google/analytics', 'Property level')
            ],
            [
                'attribute' => 'propertyName',
                'label' => Yii::t('simialbi/google/analytics', 'Property name')
            ],
            [
                'attribute' => 'propertyStarred',
                'label' => Yii::t('simialbi/google/analytics', 'Property starred')
            ],
            [
                'attribute' => 'profileId',
                'label' => Yii::t('simialbi/google/analytics', 'Profile id')
            ],
            [
                'attribute' => 'profileKind',
                'label' => Yii::t('simialbi/google/analytics', 'Profile kind')
            ],
            [
                'attribute' => 'profileName',
                'label' => Yii::t('simialbi/google/analytics', 'Profile name')
            ],
            [
                'attribute' => 'profileType',
                'label' => Yii::t('simialbi/google/analytics', 'Profile type')
            ],
            [
                'attribute' => 'profileStarred',
                'label' => Yii::t('simialbi/google/analytics', 'Profile starred')
            ],
            [
                'attribute' => 'websiteUrl',
                'label' => Yii::t('simialbi/google/analytics', 'Url'),
                'value' => function ($model) {
                    $url = \yii\helpers\ArrayHelper::getValue($model, 'websiteUrl');
                    return empty($url) ? null : Html::a('&#x1f517;', $url, ['target' => '_blank']);
                },
                'format' => 'html'
            ],
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{view}'
            ]
        ]
    ]); ?>
</div>
