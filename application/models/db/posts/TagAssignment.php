<?php

    namespace application\models\db\posts;

    use \Yii;
    use \CException;
    use \application\components\ActiveRecord;

    /**
     * This is the model class for the database table "{{post_tag_assignments}}".
     *
     * The following are the available columns:
     *     @property integer $revision
     *     @property integer $tag
     */
    class TagAssignment extends ActiveRecord
    {

        /**
         * Table Name
         *
         * Returns the name of the database table associated with this Active Record model.
         *
         * @access public
         * @return string
         */
        public function tableName()
        {
            return '{{post_tag_assignments}}';
        }


        /**
         * Validation Rules
         *
         * Returns an array of validation rules that apply to the attributes of this model.
         *
         * @access public
         * @return array
         */
        public function rules()
        {
            // You should only define rules for attributes that will recieve user input. If an attribute does not have
            // any rules associated with it, then its value will not be changed by mass-assignment (will have to be done
            // manually.)
            return array(
                array('revision, tag', 'required'),
                array('revision, tag', 'numerical', 'integerOnly' => true),
                // The following rule is used by search(). Please remove any attributes that should not be searched.
                array('revision, tag', 'safe', 'on' => 'search'),
            );
        }


        /**
         * Table Relations
         *
         * Returns an array of relational rules that determine how each attribute relates to another model, and the
         * extra class properties that become shortcuts to instances of those models.
         * You will most likely need to adjust these as the generated rules are guesswork at best.
         *
         * @access public
         * @return array
         */
        public function relations()
        {
            return array();
        }


        /**
         * Customised Attribute Labels
         *
         * Returns an array of customised labels for each attribute.
         *
         * @access public
         * @return array
         */
        public function attributeLabels()
        {
            return array(
                'revision'  => Yii::t('application', 'Revision'),
                'tag'       => Yii::t('application', 'Tag'),
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
         * @return CActiveDataProvider
         */
        public function search()
        {
            // Please modify the following code to remove attributes that should not be searched.
            $criteria = new \CDbCriteria;
            $criteria->compare('revision', $this->revision);
            $criteria->compare('tag', $this->tag);
            return new \CActiveDataProvider($this, array(
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
         * @param string $className
         * @return \application\models\db\posts\TagAssignment
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

    }
