<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model lexxanderdream\deb\Account */
/* @var $searchModel lexxanderdream\deb\models\OperationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'kind_id',
            'type',
            'owner_id',
            'amount',
            'created_at',
        ],
    ]) ?>

    <h2>Account operations</h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'created_at',
            [
                'attribute' => 'id',
                'options'   => ['style' => 'width: 80px;'],
            ],
            'type',
            'amount',
            'transaction.title',
        ],
    ]); ?>

</div>
