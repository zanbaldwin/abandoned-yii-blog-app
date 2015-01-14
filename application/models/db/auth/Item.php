<?php

    namespace application\models\db;

    use \Yii;
    use \CException;
    use \application\components\ActiveRecord;

    /**
     * This is the model class for the database table "{{auth_items}}".
     *
     * The following are the available columns:
     *     @property integer $id
     *     @property string $name
     *     @property integer $type
     *     @property string $description
     *     @property string $rule
     *     @property string $data
     *     @property integer $default
     *
     * The following are the available model relations:
     *     @property Users[] $yiiUsers
     *     @property AuthHierarchy[] $authHierarchies
     *     @property AuthHierarchy[] $authHierarchies1
     */
    class Item extends ActiveRecord
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
            return '{{auth_items}}';
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
                array('name, type', 'required'),
                array('type, default', 'numerical', 'integerOnly'=>true),
                array('name', 'length', 'max'=>64),
                array('description, rule, data', 'safe'),
                // The following rule is used by search(). Please remove any attributes that should not be searched.
                array('id, name, type, description, rule, data, default', 'safe', 'on' => 'search'),
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
            return array(
                'yiiUsers' => array(self::MANY_MANY, 'Users', '{{auth_assignments}}(item, user)'),
                'authHierarchies' => array(self::HAS_MANY, 'AuthHierarchy', 'child'),
                'authHierarchies1' => array(self::HAS_MANY, 'AuthHierarchy', 'parent'),
            );
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
                'id' => Yii::t('category', 'ID'),
                'name' => Yii::t('category', 'Name'),
                'type' => Yii::t('category', 'Type'),
                'description' => Yii::t('category', 'Description'),
                'rule' => Yii::t('category', 'Rule'),
                'data' => Yii::t('category', 'Data'),
                'default' => Yii::t('category', 'Default'),
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
            $criteria->compare('id', $this->id);
            $criteria->compare('name', $this->name, true);
            $criteria->compare('type', $this->type);
            $criteria->compare('description', $this->description, true);
            $criteria->compare('rule', $this->rule, true);
            $criteria->compare('data', $this->data, true);
            $criteria->compare('default', $this->default);
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
         * @return Item
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

    }
