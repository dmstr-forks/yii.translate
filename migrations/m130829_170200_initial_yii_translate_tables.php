
<?php

/**
 * Created with https://github.com/schmunk42/database-command
 */
class m130829_170200_initial_yii_translate_tables extends CDbMigration
{

    public function up()
    {
        if (Yii::app()->db->schema instanceof CMysqlSchema) {
            $this->execute('CREATE TABLE SourceMessage
                            (
                                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                                category VARCHAR(32),
                                message TEXT
                            );
                            CREATE TABLE Message
                            (
                                id INTEGER,
                                language VARCHAR(16),
                                translation TEXT,
                                PRIMARY KEY (id, language),
                                CONSTRAINT FK_Message_SourceMessage FOREIGN KEY (id)
                                     REFERENCES SourceMessage (id) ON DELETE CASCADE ON UPDATE RESTRICT
                            );
        ');
            $options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8';
        } else {
            $options = '';
        }
    }

    public function down()
    {
        $this->dropTable("Message");
        $this->dropTable("SourceMessage");
    }

}

?>
