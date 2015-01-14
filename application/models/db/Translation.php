<?php

    namespace application\models\db;

    use \Yii;
    use \CException;
    use \application\components\ActiveRecord;

    /**
     * This is the model class for table "{{translations}}".
     *
     * The followings are the available columns in table '{{translations}}':
     * @property integer $id
     * @property string $language
     * @property string $translation
     *
     * The followings are the available model relations:
     * @property Messages $id0
     */
    class Translation extends ActiveRecord
    {

        /**
         * Table Name
         *
         * @access public
         * @return string
         */
        public function tableName()
        {
            return '{{translations}}';
        }


        /**
         * Validation Rules
         *
         * @access public
         * @return array
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, language, translation', 'required'),
                array('id', 'numerical', 'integerOnly' => true, 'min' => 1),
                array('language', 'length', 'max' => 16),
                array('id, language, translation', 'safe', 'on' => 'search'),
            );
        }


        /**
         * Table Relations
         *
         * @access public
         * @return array
         */
        public function relations()
        {
            return array(
                'Message' => array(self::BELONGS_TO, '\\application\\models\\db\\Message', 'id'),
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
                'id'            => Yii::t('application', 'ID'),
                'language'      => Yii::t('application', 'Language'),
                'translation'   => Yii::t('application', 'Translation'),
            );
        }


        /**
         * Search
         *
         * Retrieves a list of models based on the current search/filter conditions. A typical usecase involves:
         * - Initialize the model fields with values from filter form.
         * - Execute this method to get CActiveDataProvider instance which will filter models according to data in
         *   model fields.
         * - Pass data provider to CGridView, CListView or any similar widget.
         *
         * @access public
         * @return \CActiveDataProvider
         */
        public function search()
        {
            $criteria = new CDbCriteria;
            $criteria->compare('id', $this->id);
            $criteria->compare('language', $this->language, true);
            $criteria->compare('translation', $this->translation, true);
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }


        /**
         * Model Instance
         *
         * Returns a static model of the specified ActiveRecord class. This exact method should be in all classes that
         * extend CActiveRecord.
         *
         * @access public
         * @param string $class_name
         * @return User
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

    }
