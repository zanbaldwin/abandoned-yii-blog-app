<?php

    namespace application\models\form\posts;

    use \Yii;
    use \CException;
    use \application\components\form\Model;

    class Post extends Model
    {

        public $slug;
        public $title;
        public $content;
        public $categories;
        public $tags;


        /**
         * Validation Rules
         *
         * @access public
         * @return array
         */
        public function rules()
        {
            return array(
                array('slug, title, content', 'required'),
                array('slug', 'length', 'max' => 64),
                array('title', 'length', 'max' => 255),
            );
        }


        /**
         * Attribute Labels
         *
         * @access public
         * @return array
         */
        public function attributeLabels()
        {
            return array(
                'slug'          => Yii::t('application', 'URL Slug'),
                'title'         => Yii::t('application', 'Post Title'),
                'content'       => Yii::t('application', 'Post Contents'),
                'categories'    => Yii::t('application', 'Categories'),
                'tags'          => Yii::t('application', 'Tags'),
            );
        }

    }
