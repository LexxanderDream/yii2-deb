<?php

use lexxanderdream\deb\AccountKind;
use lexxanderdream\deb\TransactionType;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel lexxanderdream\deb\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Transaction', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [
                'attribute' => 'id',
                'options'   => ['style' => 'width: 80px;']
            ],
            'created_at:datetime',
            [
                'attribute' => 'type_id',
                'filter'    => \yii\helpers\ArrayHelper::map(TransactionType::find()->all(), 'id', 'name'),
                'value'     => 'title',
            ],
            [
                'attribute' => 'sender_account_id',
                'value' => 'senderAccount.title'
            ],

            [
                'attribute' => 'receiver_account_id',
                'value' => 'receiverAccount.title'
            ],
            'amount',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
