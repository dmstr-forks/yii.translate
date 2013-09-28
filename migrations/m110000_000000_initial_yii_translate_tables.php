<?php

/**
 * Created with https://github.com/schmunk42/database-command
 */
class m110000_000000_initial_yii_translate_tables extends CDbMigration
{

    public function up()
    {
        if ($this->dbConnection->schema instanceof CMysqlSchema) {
            $this->execute(
                'CREATE TABLE SourceMessage
                                            (
                                                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                                                category VARCHAR(32),
                                                message TEXT
                                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
            );
            $this->execute(
                'CREATE TABLE Message
                                            (
                                                id INTEGER,
                                                language VARCHAR(16),
                                                translation TEXT,
                                                PRIMARY KEY (id, language),
                                                CONSTRAINT FK_Message_SourceMessage FOREIGN KEY (id)
                                                     REFERENCES SourceMessage (id) ON DELETE CASCADE ON UPDATE RESTRICT
                                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        '
            );
        } else {
            $this->execute(
                'CREATE TABLE SourceMessage
                                            (
                                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                                category VARCHAR(32),
                                                message TEXT
                                            );'
            );
            $this->execute(
                'CREATE TABLE Message
                                            (
                                                id INTEGER,
                                                language VARCHAR(16),
                                                translation TEXT,
                                                PRIMARY KEY (id, language),
                                                CONSTRAINT FK_Message_SourceMessage FOREIGN KEY (id)
                                                     REFERENCES SourceMessage (id) ON DELETE CASCADE ON UPDATE RESTRICT
                                            );
                        '
            );
        }
    }

    public function down()
    {
        $this->dropTable("Message");
        $this->dropTable("SourceMessage");
    }

}

?>
