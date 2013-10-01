<?php

    /**
     * Description of PhTranslationConverter
     *
     * @author marc
     */
    class TranslationConverter {

        /**
         * method that handles the on missing translation event
         * 
         * @param CMissingTranslationEvent $event
         * @return string the message to translate or the translated message if local message source file exist
         */        
        static public function findInPhpMessageSource($event) {
            Yii::setPathOfAlias('translate',dirname(__FILE__).'/..');
            Yii::import('translate.models.MessageSource');
            $attributes = array('category' => $event->category, 'message' => $event->message);
            if (($model = MessageSource::model()->find('message=:message AND category=:category', $attributes)) === null) {
                $model = new MessageSource();
                $model->attributes = $attributes;
                if (!$model->save())
                    return Yii::log(TranslateModule::t('Message ' . $event->message . ' could not be added to messageSource table'));
            }

            if ($model->id) {
                if (substr($event->language, 0, 2) !== substr(Yii::app()->sourceLanguage, 0, 2)) {
                    Yii::import('translate.models.Message');

                    if (strstr($event->category,".")) {
                        $basePath = strstr($event->category,".",true);
                        $class=new ReflectionClass(strstr($event->category,".",true));
                        $basePath = dirname($class->getFileName());
                        $catalog = substr(strstr($event->category,"."),1);
                    } else {
                        $basePath = Yii::app()->basePath;
                        $catalog = $event->category;
                    }

                    //$dir = $basePath . '/messages/' . $event->language . '/*';
                    $dir = $basePath . '/messages/' . $event->language . '/'.$catalog.'.php';

                    foreach (glob($dir) as $file) {
                        foreach (require($file) AS $key => $translation) { // TODO: remove, we don't need to look at several files
                            $attributes = array('translation' => $translation, 'language' => $event->language);
                            if ($key == $event->message && ($messageModel = Message::model()->find('translation=:translation AND language=:language', $attributes)) === null) {
                                $messageModel = new Message;
                                $messageModel->attributes = array('id' => $model->id, 'language' => $event->language, 'translation' => $translation);
                                if ($messageModel->save())
                                    $event->message = $translation;
                                else
                                    return Yii::log(TranslateModule::t('Message ' . $event->message . ' could not be translated from local file'));
                            }
                        }
                    }
                }

                if (substr($event->language, 0, 2) !== substr(Yii::app()->sourceLanguage, 0, 2) || Yii::app()->getMessages()->forceTranslation) {
                    MPTranslate::$messages[$model->id] = array('language' => $event->language, 'message' => $event->message, 'category' => $event->category);
                }
            }

            return $event;
        }
    }

?>
