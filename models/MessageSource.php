<?php
class MessageSource extends CActiveRecord{
    
    public $language;
        
	static function model($className=__CLASS__){return parent::model($className);}
	function tableName(){return Yii::app()->getMessages()->sourceMessageTable;}

	function rules(){
		return array(
            array('category,message','required'),
			array('category', 'length', 'max'=>32),
			array('message', 'safe'),
			array('id, category, message,language', 'safe', 'on'=>'search'),
		);
	}
    
	function relations(){
		return array(
                    'mt'=>array(self::HAS_MANY,'Message',array('id','id')),
		);
	}
	function attributeLabels(){
		return array(
			'id'=> TranslateModule::t('ID'),
			'category'=> TranslateModule::t('Category'),
			'message'=> TranslateModule::t('Message'),
		);
	}

	function search()
        {
            $criteria = new CDbCriteria;

            $criteria->with = array('mt');

            $criteria->addCondition('not exists (select `id` from `Message` `m` where `m`.`language`=:lang and `m`.`id` = `t`.`id`)');

            $criteria->compare('t.id', $this->id);
            $criteria->compare('t.category', $this->category);
            $criteria->compare('t.message', $this->message);

            $criteria->params[':lang'] = $this->language;
            $criteria->order = 'message ASC';

            return new CActiveDataProvider(get_class($this), array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => 10,
                ),
            ));
        }
        
        /**
         * get remaining missing translations in the current language
         */
        public function getMissingTranslations()
        {
            $criteria = new CDbCriteria;
            
            $criteria->with = array('mt');

            $criteria->addCondition('not exists (select `id` from `Message` `m` where `m`.`language`=:lang and `m`.`id` = `t`.`id`)');

            $criteria->params[':lang'] = $this->language;
            $criteria->order = 'message ASC';

            return MessageSource::model()->findAll($criteria);
        }

}