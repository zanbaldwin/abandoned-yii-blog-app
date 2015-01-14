<?php

    class m131208_191122_posts_bundle_format_table extends CDbMigration
    {

        /**
         * Migrate: Up
         *
         * @access public
         * @return void
         */
        public function up()
        {
            $this->createTable(
                '{{post_formats}}',
                array(
                    // Entities.
                    'id'            => 'pk                                          COMMENT ""',
                    'slug'          => 'VARCHAR(64)     NOT NULL                    COMMENT "The URL slug of this particular post format. Should be a singular, lower-case string not exceeding 64 characters."',
                    'series'        => 'VARCHAR(255)    NOT NULL                    COMMENT "The name of the post series, as a Yii translation string."',
                    'post'          => 'VARCHAR(255)    NOT NULL                    COMMENT "The name of the posts, as a Yii translation string."',
                ),
                implode(' ', array(
                    'ENGINE          = InnoDB',
                    'DEFAULT CHARSET = utf8',
                    'COLLATE         = utf8_general_ci',
                    'COMMENT         = ""',
                    'AUTO_INCREMENT  = 1',
                ))
            );
        }

        /**
         * Migrate: Down
         *
         * @access public
         * @return void
         */
        public function down()
        {
            $this->dropTable('{{post_formats}}');
        }

    }
