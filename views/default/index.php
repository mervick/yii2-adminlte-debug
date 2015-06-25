<?php
/* @var $this \yii\web\View */
/* @var $manifest array */
/* @var $searchModel \yii\debug\models\search\Debug */
/* @var $dataProvider ArrayDataProvider */
/* @var $panels \yii\debug\Panel[] */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$this->title = 'Yii Debugger';
yii\debug\DebugAsset::register($this);

$this->params['breadcrumbs'][] = $this->title;
$this->params['title_desc'] = 'Available Debug Data';
?>
<div class="box box-success debug default-index">
    <div class="box-header with-border yii-debug-toolbar">
        <h3 class="box-title">
            <img width="29" height="30" alt="" src="<?= \yii\debug\Module::getYiiLogo() ?>">
            Yii Debugger
        </h3>
        <div class="pull-right">
            <?php foreach ($panels as $panel): ?>
                <?= $panel->getSummary() ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="box-body">
<?php

if (isset($this->context->module->panels['db']) && isset($this->context->module->panels['request'])) {

    $codes = [];
    foreach ($manifest as $tag => $vals) {
        if (!empty($vals['statusCode'])) {
            $codes[] = $vals['statusCode'];
        }
    }
    $codes = array_unique($codes, SORT_NUMERIC);
    $statusCodes = !empty($codes) ? array_combine($codes, $codes) : null;

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) use ($searchModel) {
            $dbPanel = $this->context->module->panels['db'];

            if ($searchModel->isCodeCritical($model['statusCode']) || $dbPanel->isQueryCountCritical($model['sqlCount'])) {
                return ['class'=>'danger'];
            } else {
                return [];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'tag',
                'value' => function ($data) {
                    return Html::a($data['tag'], ['view', 'tag' => $data['tag']]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'time',
                'value' => function ($data) {
                    return '<span class="nowrap">' . Yii::$app->formatter->asDatetime($data['time'], 'short') . '</span>';
                },
                'format' => 'html',
            ],
            'ip',
            [
                'attribute' => 'sqlCount',
                'label' => 'Query Count',
                'value' => function ($data) {
                    $dbPanel = $this->context->module->panels['db'];

                    if ($dbPanel->isQueryCountCritical($data['sqlCount'])) {

                        $content = Html::tag('b', $data['sqlCount']) . ' ' . Html::tag('span', '', ['class' => 'glyphicon glyphicon-exclamation-sign']);

                        return Html::a($content, ['view', 'panel' => 'db', 'tag' => $data['tag']], [
                            'title' => 'Too many queries. Allowed count is ' . $dbPanel->criticalQueryThreshold,
                        ]);

                    } else {
                        return $data['sqlCount'];
                    }
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'mailCount',
                'visible' => isset($this->context->module->panels['mail']),
            ],
            [
                'attribute' => 'method',
                'filter' => ['get' => 'GET', 'post' => 'POST', 'delete' => 'DELETE', 'put' => 'PUT', 'head' => 'HEAD']
            ],
            [
                'attribute'=>'ajax',
                'value' => function ($data) {
                    return $data['ajax'] ? 'Yes' : 'No';
                },
                'filter' => ['No', 'Yes'],
            ],
            [
                'attribute' => 'url',
                'label' => 'URL',
            ],
            [
                'attribute' => 'statusCode',
                'filter' => $statusCodes,
                'label' => 'Status code'
            ],
        ],
    ]);

} else {
    echo "<div class='alert alert-warning'>No data available. Panel <code>db</code> or <code>request</code> not found.</div>";
}

?>
    </div>
</div>
