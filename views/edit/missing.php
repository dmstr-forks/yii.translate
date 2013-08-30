<h1><?php echo TranslateModule::translator()->acceptedLanguages[TranslateModule::translator()->getLanguage()] ?> <small><?php echo TranslateModule::t('Missing Translations') ?></small></h1>
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
            'filter' => CHtml::listData($model->missingTranslations, 'message', 'message'),
        ),
        array(
            'name' => 'category',
            'filter' => CHtml::listData($model->missingTranslations, 'category', 'category'),
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