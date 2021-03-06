<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $searchModel bl\cms\cart\models\SearchOrderStatus
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use bl\cms\shop\widgets\ManageButtons;
use bl\multilang\entities\Language;

$this->title = Yii::t('shop', 'Order Statuses');
?>

<div class="panel panel-default">

    <div class="panel-heading">

        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-user-plus']) .
            Yii::t('shop', 'Create status'), ['save', 'languageId' => Language::getCurrent()->id], ['class' => 'btn btn-primary btn-xs pull-right']);
        ?>

        <h5>
            <i class="glyphicon glyphicon-list">
            </i>
            <?= Html::encode($this->title); ?>
        </h5>

    </div>

    <div class="panel-body">

        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                /*Id*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-2'],
                    'attribute' => '',
                    'value' => 'id',
                    'label' => Yii::t('shop', 'Id'),
                    'contentOptions' => ['class' => 'text-center project-title col-md-2'],
                ],

                /*TITLE*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-8'],
                    'attribute' => 'title',
                    'value' => function ($model) {
                        $content = null;
                        if (!empty($model->translation->title)) {
                            $content = Html::a(
                                $model->translation->title,
                                Url::toRoute(['save', 'id' => $model->id, 'languageId' => Language::getCurrent()->id])
                            );
                        }
                        return $content;
                    },
                    'label' => Yii::t('shop', 'Title'),
                    'format' => 'html',
                    'contentOptions' => ['class' => 'text-center project-title col-md-8'],
                ],

                /*ACTIONS*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-2'],
                    'attribute' => \Yii::t('shop', 'Manage'),

                    'value' => function ($model) {
                        return ManageButtons::widget(['model' => $model]);
                    },
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-center col-md-2 text-center'],
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
        <div class="row">
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-user-plus']) .
                Yii::t('shop', 'Create status'), ['save'], ['class' => 'btn btn-primary btn-xs m-r-xs pull-right']);
            ?>
        </div>
    </div>
</div>

