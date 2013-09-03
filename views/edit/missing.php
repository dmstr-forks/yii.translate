<?php
/* @var $this EditController */

$this->breadcrumbs[] = Yii::t('Translate', TranslateModule::translator()->acceptedLanguages[TranslateModule::translator()->getLanguage()]);
$this->breadcrumbs[] = '';

$this->widget("TbBreadcrumbs", array("links" => $this->breadcrumbs)) 
?>

<h1><small><?php echo Yii::t('Translate', TranslateModule::t('Missing Translations')) ?></small></h1>
<br />

<?php
$this->widget('TbGridView', array(
    'id' => 'message-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template'=>'{pager}{items}{pager}',
    'pager' => array(
        'class' => 'TbPager',
        'displayFirstAndLast' => true,
    ),
    'columns' => array(
        'id',
        array(
            'name' => 'message',
            'filter' => CHtml::listData($model->getMissingTranslations('message'), 'message', 'message'),
        ),
        array(
            'name' => 'category',
            'filter' => CHtml::listData($model->getMissingTranslations('category'), 'category', 'category'),
        ),
        array(
            'class' => 'TbButtonColumn',
            'template' => '{create} {delete}',
            'deleteButtonUrl' => 'Yii::app()->getController()->createUrl("missingdelete",array("id"=>$data->id))',
            'buttons' => array(
                'create' => array(
                    'label' => 'Create',
                    'url' => 'Yii::app()->getController()->createUrl("Create",array("id"=>$data->id,"language"=>Yii::app()->getLanguage()))'
                )
            ),
            'header' => (!TranslateModule::translator()->useApplicationLanguage) ? TranslateModule::translator()->dropdown() : false,
        )
    ),
));
?>