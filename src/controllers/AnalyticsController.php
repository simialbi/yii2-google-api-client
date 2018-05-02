<?php
/**
 * @package yii2-google-api-client
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\google\controllers;

use Google_Service_Analytics;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_OrderBy;
use Google_Service_AnalyticsReporting_ReportRequest;
use yii\base\InvalidConfigException;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class AnalyticsController
 *
 * @property-read \simialbi\yii2\google\Module $module
 */
class AnalyticsController extends ServiceController
{
    /**
     * @var string
     */
    public $analytics_id;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->analytics_id)) {
            throw new InvalidConfigException(Yii::t(
                'simialbi/google/notifications',
                'The "analytics_id" param is mandatory'
            ));
        }
    }

    /**
     *
     */
    public function actionIndex()
    {
        $analytics = new Google_Service_Analytics($this->module->client->api);
        $items = $analytics->management_accountSummaries->listManagementAccountSummaries()->getItems();

        $data = [];
        foreach ($items as $item) {
            /* @var $item \Google_Service_Analytics_AccountSummary */
            foreach ($item->getWebProperties() as $property) {
                /* @var $property \Google_Service_Analytics_WebPropertySummary */
                foreach ($property->getProfiles() as $profile) {
                    /* @var $profile \Google_Service_Analytics_ProfileSummary */
                    $data[] = [
                        'accountId' => ArrayHelper::getValue($item, 'id'),
                        'accountKind' => ArrayHelper::getValue($item, 'kind'),
                        'accountName' => ArrayHelper::getValue($item, 'name'),
                        'accountStarred' => ArrayHelper::getValue($item, 'starred'),
                        'propertyId' => ArrayHelper::getValue($property, 'id'),
                        'propertyKind' => ArrayHelper::getValue($property, 'kind'),
                        'propertyLevel' => ArrayHelper::getValue($property, 'level'),
                        'propertyName' => ArrayHelper::getValue($property, 'name'),
                        'propertyStarred' => ArrayHelper::getValue($property, 'starred'),
                        'websiteUrl' => ArrayHelper::getValue($property, 'websiteUrl'),
                        'profileId' => ArrayHelper::getValue($profile, 'id'),
                        'profileKind' => ArrayHelper::getValue($profile, 'kind'),
                        'profileName' => ArrayHelper::getValue($profile, 'name'),
                        'profileStarred' => ArrayHelper::getValue($profile, 'starred'),
                        'profileType' => ArrayHelper::getValue($profile, 'type')
                    ];
                }
            }

        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'profileId',
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param $profileId
     */
    public function actionView($profileId)
    {
        $analytics = new Google_Service_AnalyticsReporting($this->module->client->api);

        $request = new Google_Service_AnalyticsReporting_GetReportsRequest([
            'reportRequests' => [
                new Google_Service_AnalyticsReporting_ReportRequest([
                    'viewId' => $profileId,
                    'dateRanges' => [
                        new Google_Service_AnalyticsReporting_DateRange([
                            'startDate' => '30daysAgo',
                            'endDate' => 'today'
                        ])
                    ],
                    'dimensions' => [
                        new Google_Service_AnalyticsReporting_Dimension([
                            'name' => 'ga:pageTitle'
                        ]),
                        new Google_Service_AnalyticsReporting_Dimension([
                            'name' => 'ga:pagePath'
                        ]),
                        new Google_Service_AnalyticsReporting_Dimension([
                            'name' => 'ga:landingPagePath'
                        ]),
                        new Google_Service_AnalyticsReporting_Dimension([
                            'name' => 'ga:previousPagePath'
                        ]),
                        new Google_Service_AnalyticsReporting_Dimension([
                            'name' => 'ga:exitPagePath'
                        ])
                    ],
                    'metrics' => [
                        new Google_Service_AnalyticsReporting_Metric([
                            'expression' => 'ga:pageviews'
                        ]),
                        new Google_Service_AnalyticsReporting_Metric([
                            'expression' => 'ga:pageValue'
                        ]),
                        new Google_Service_AnalyticsReporting_Metric([
                            'expression' => 'ga:avgTimeOnPage'
                        ])
                    ],
                    'orderBys' => [
                        new Google_Service_AnalyticsReporting_OrderBy([
                            'fieldName' => 'ga:pageviews',
                            'sortOrder' => 'DESCENDING'
                        ])
                    ]
                ])
            ]
        ]);

//        if (class_exists('\simialbi\yii2\chart\Chart')) {
//
//        }

        $result = $analytics->reports->batchGet($request);

        $data = [];
        foreach ($result->getReports() as $report) {
            /* @var $report \Google_Service_AnalyticsReporting_Report */
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader();
            $rows = $report->getData()->getRows();
            $data = [];

            for ($i = 0; $i < count($rows); $i++) {
                /* @var $row \Google_Service_AnalyticsReporting_ReportRow */
                $row = $rows[$i];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                $item = [];

                for ($k = 0; $k < count($dimensionHeaders); $k++) {
                    $item[$dimensionHeaders[$k]] = $dimensions[$k];
                }
                foreach ($metrics as $metric) {
                    /* @var $metric \Google_Service_AnalyticsReporting_DateRangeValues */
                    $values = $metric->getValues();
                    for ($k = 0; $k < count($values); $k++) {
                        $item[$metricHeaders[$k]->getName()] = $values[$k];
                    }
                }

                $data[] = $item;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 30,
            ]
        ]);

        $this->render('view', [
            'result' => $result,
            'dataProvider' => $dataProvider
        ]);
    }
}