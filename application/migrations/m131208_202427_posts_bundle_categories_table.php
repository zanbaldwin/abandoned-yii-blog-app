<?php

    class m131208_202427_posts_bundle_categories_table extends CDbMigration
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
                '{{post_categories}}',
                array(
                    // Entities.
                    'id'            => 'pk                                          COMMENT ""',
                    'title'         => 'VARCHAR(255)    NOT NULL                    COMMENT "The author of the category."',
                    'slug'          => 'VARCHAR(64)     NOT NULL    UNIQUE          COMMENT "The unique URL slug for the category."',
                    'description'   => 'TEXT                                        COMMENT "An optional description for this category."',
                    'format'        => 'INT                                         COMMENT "Should this category be restricted to one particular post format?"',
                    'parent'        => 'INT                                         COMMENT "Does this category have a parent category?"',
                ),
                implode(' ', array(
                    'ENGINE          = InnoDB',
                    'DEFAULT CHARSET = utf8',
                    'COLLATE         = utf8_general_ci',
                    'COMMENT         = ""',
                    'AUTO_INCREMENT  = 1',
                ))
            );
            $this->addForeignKey('post_categories_fk_format', '{{post_categories}}', 'format', '{{post_formats}}', 'id');
            $this->addForeignKey('post_categories_fk_parent', '{{post_categories}}', 'parent', '{{post_categories}}', 'id');
            $this->createTable(
                '{{post_category_assignments}}',
                array(
                    // Entities.
                    'revision'      => 'INT             NOT NULL                    COMMENT "The ID of the post revision."',
                    'category'      => 'INT             NOT NULL                    COMMENT "The ID of the category."',
                ),
                implode(' ', array(
                    'ENGINE          = InnoDB',
                    'DEFAULT CHARSET = utf8',
                    'COLLATE         = utf8_general_ci',
                    'COMMENT         = ""',
                    'AUTO_INCREMENT  = 1',
                ))
            );
            $this->addPrimaryKey('post_category_assignments_pk', '{{post_category_assignments}}', 'revision, category');
            $this->addForeignKey('post_category_assignments_fk_revision', '{{post_category_assignments}}', 'revision', '{{post_revisions}}', 'id');
            $this->addForeignKey('post_category_assignments_fk_category', '{{post_category_assignments}}', 'category', '{{post_categories}}', 'id');
        }

        /**
         * Migrate: Down
         *
         * @access public
         * @return void
         */
        public function down()
        {
            $this->dropTable('{{post_category_assignments}}');
            $this->dropTable('{{post_categories}}');
        }

    }
