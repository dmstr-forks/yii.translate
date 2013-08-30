<h1><?php echo TranslateModule::t('Translations') ?> <small><?php echo TranslateModule::t('Manage') ?></small></h1>
<br />
<?php
$this->widget('TbGridView', array(
    'id' => 'message-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template' => '{pager}{items}{pager}',
    'pager' => array(
        'class' => 'TbPager',
        'displayFirstAndLast' => true,
    ),
    'columns' => array(
        'id',
        array(
            'name' => 'message',
            'filter' => CHtml::listData($model->allTranslations, 'message', 'message'),
        ),
        array(
            'name' => 'category',
            'filter' => CHtml::listData($model->allTranslations, 'category', 'category'),
        ),
        array(
            'name' => 'language',
            'filter' => CHtml::listData($model->findAll(new CDbCriteria(array('group' => 'language'))), 'language', 'language')
        ),
        array(
            'class' => 'editable.EditableColumn',
            'name' => 'translation',
            'editable' => array(
                'url' => $this->createUrl('/translate/edit/editableSaver'),
            )
        ),
        array(
            'class' => 'TbButtonColumn',
            'template' => '{update}{delete}',
            'updateButtonUrl' => 'Yii::app()->getController()->createUrl("update",array("id"=>$data->id,"language"=>$data->language))',
            'deleteButtonUrl' => 'Yii::app()->getController()->createUrl("delete",array("id"=>$data->id,"language"=>$data->language))',
        ),
    )
));
?>
