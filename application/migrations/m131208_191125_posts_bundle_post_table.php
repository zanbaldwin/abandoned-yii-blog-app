<?php

    class m131208_191125_posts_bundle_post_table extends CDbMigration
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
                '{{posts}}',
                array(
                    // Entities.
                    'id'            => 'pk                                          COMMENT ""',
                    'slug'          => 'VARCHAR(64)     NOT NULL                    COMMENT "The unique URL slug for the post."',
                    'format'        => 'INT             NOT NULL                    COMMENT "What is the format of the post?"',
                    'series'        => 'INT                                         COMMENT "Which series does this post belong in?"',
                    'created'       => 'INT             NOT NULL                    COMMENT "When was the post created?"',
                    'author'        => 'INT             NOT NULL                    COMMENT "Who is the author of the post?"',
                    'owner'         => 'BOOLEAN                                     COMMENT "Does the post belong to the user (true), their army (false), or the system (null)?"',
                    'archived'      => 'BOOLEAN         NOT NULL    DEFAULT FALSE   COMMENT "Since we don\'t want some users permanently deleting posts, specify here is it has been archived."',
                ),
                implode(' ', array(
                    'ENGINE          = InnoDB',
                    'DEFAULT CHARSET = utf8',
                    'COLLATE         = utf8_general_ci',
                    'COMMENT         = ""',
                    'AUTO_INCREMENT  = 1',
                ))
            );
            $this->createIndex('posts_uq_format_slug', '{{posts}}', 'format, series, slug', true);
            $this->addForeignKey('posts_fk_format', '{{posts}}', 'format', '{{post_formats}}', 'id');
            $this->addForeignKey('posts_fk_series', '{{posts}}', 'series', '{{post_series}}', 'id');
            $this->addForeignKey('posts_fk_author', '{{posts}}', 'author', '{{users}}', 'id');
        }

        /**
         * Migrate: Down
         *
         * @access public
         * @return void
         */
        public function down()
        {
            $this->dropTable('{{posts}}');
        }

    }
