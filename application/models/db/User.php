<?php

    namespace application\models\db;

    use \Yii;
    use \CException;
    use \application\components\ActiveRecord;
    use \application\components\IP;

    /**
     * This is the model class for the database table "{{users}}".
     *
     * The following are the available columns:
     *     @property integer $id
     *     @property string $username
     *     @property string $email
     *     @property string $password
     *     @property string $firstname
     *     @property string $nickname
     *     @property string $lastname
     *     @property integer $ag
     *     @property integer $points
     *     @property string $created
     *     @property double $lastLogin
     *     @property integer $active
     *
     * The following are the available model relations:
     *     @property AuthItems[] $yiiAuthItems
     *     @property FailedLogins[] $failedLogins
     *     @property Forum[] $forums
     *     @property Forum[] $forums1
     *     @property News[] $news
     *     @property PostComments[] $postComments
     *     @property PostRevisions[] $postRevisions
     *     @property PostSeries[] $postSeries
     *     @property Posts[] $posts
     *     @property UserMeta $userMeta
     */
    class User extends ActiveRecord
    {

        /**
         * @var string $displayName
         */
        protected $displayName;

        /**
         * @var string $fullName
         */
        protected $fullName;

        /* ------------------ *\
        |  GII AUTOMATED CODE  |
        \* ------------------ */

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
            return '{{users}}';
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
            // manually).
            return array(
                array('email, password, firstname, lastname, created', 'required'),
                array('ag, points, active', 'numerical', 'integerOnly' => true),
                array('lastLogin', 'numerical'),
                array('username', 'length', 'max' => 64),
                array('email', 'length', 'max' => 255),
                array('password', 'length', 'max' => 60),
                array('firstname, nickname, lastname', 'length', 'max' => 128),
                array('created', 'length', 'max' => 10),
                // The following rule is used by search(). Please remove any attributes that should not be searched.
                array('id, username, email, password, firstname, nickname, lastname, ag, points, created, lastLogin, active', 'safe', 'on' => 'search'),
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
                'Auth'          => array(self::MANY_MANY,   '\\application\\models\\db\\auth\\Item',        '{{auth_assignments}}(user, item)'),
                'FailedLogins'  => array(self::HAS_MANY,    '\\application\\models\\db\\FailedLogin',       'user'),
                'PostComments'  => array(self::HAS_MANY,    '\\application\\models\\db\\posts\\Comment',    'author'),
                'Posts'         => array(self::HAS_MANY,    '\\application\\models\\db\\posts\\Post',       'author'),
                'Meta'          => array(self::HAS_ONE,     '\\application\\models\\db\\UserMeta',          'user'),
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
                'id'        => Yii::t('application', 'ID'),
                'username'  => Yii::t('application', 'Username'),
                'email'     => Yii::t('application', 'Email'),
                'password'  => Yii::t('application', 'Password'),
                'firstname' => Yii::t('application', 'First Name'),
                'nickname'  => Yii::t('application', 'Nickname'),
                'lastname'  => Yii::t('application', 'Last Name'),
                'ag'        => Yii::t('application', 'Army Group'),
                'points'    => Yii::t('application', 'Points'),
                'created'   => Yii::t('application', 'Created'),
                'lastLogin' => Yii::t('application', 'Last Login'),
                'active'    => Yii::t('application', 'Active?'),
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
            $criteria->compare('username', $this->username, true);
            $criteria->compare('email', $this->email, true);
            $criteria->compare('password', $this->password, true);
            $criteria->compare('firstname', $this->firstname, true);
            $criteria->compare('nickname', $this->nickname, true);
            $criteria->compare('lastname', $this->lastname, true);
            $criteria->compare('ag', $this->ag);
            $criteria->compare('points', $this->points);
            $criteria->compare('created', $this->created, true);
            $criteria->compare('lastLogin', $this->lastLogin);
            $criteria->compare('active', $this->active);
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
         * @return \application\models\db\User
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /* ------------------------- *\
        |  END:   GII AUTOMATED CODE  |
        |  BEGIN: NAMING METHODS      |
        \* ------------------------- */

        /**
         * Display Name
         *
         * @access public
         * @return string
         */
        public function getDisplayName()
        {
            if(!is_null($this->displayName)) {
                return $this->displayName;
            }
            $firstname = is_string($this->nickname) && $this->nickname
                ? ucwords($this->nickname)
                : ucwords($this->firstname);
            $this->displayName = $firstname . ' ' . ucwords(substr($this->lastname, 0, 1));
            return $this->displayName;
        }

        /**
         * Full Name
         *
         * @access public
         * @return string
         */
        public function getFullName()
        {
            if(!is_null($this->fullName)) {
                return $this->fullName;
            }
            $this->fullName = ucwords($this->firstname) . ' ' . ucwords($this->lastname);
            return $this->fullName;
        }

        /* -------------------------------- *\
        |  END:   NAMING METHODS             |
        |  BEGIN: PASSWORD-SPECIFIC METHODS  |
        \* -------------------------------- */

        /**
         * Hash Password
         *
         * A useful function that can be called without creating a new instance of the User model, to transform a
         * string into a password hash.
         *
         * @static
         * @access public
         * @param string
         * @return string
         */
        public static function hashPassword($password)
        {
            $phpass = new \Phpass\Hash;
            return $phpass->hashPassword($password);
        }

        /**
         * Check Password
         *
         * Check that the password supplied to this method equates to the same password hash that is stored in the
         * database for the user identified by the current (this) model instance.
         *
         * @access public
         * @param string $password
         * @return boolean
         */
        public function password($password)
        {
            return \CPasswordHelper::verifyPassword($password, $this->password);
        }

        /**
         * PHP Magic Function: Set
         *
         * Override the method to extend the functionality (hash a password that is set as an attribute before adding it
         * to the model).
         *
         * @access public
         * @param string $name
         * @param mixed $value
         * @return void
         */
        public function __set($property, $value)
        {
            // If an override method exists for a certain property, call it to alter the value before passing it to the
            // model to be saved to the database.
            $method = 'set' . ucwords($property);
            if(method_exists($this, $method)) {
                $value = $this->{$method}($value);
            }
            // Carry on setting it to the model as normal.
            parent::__set($property, $value);
        }

        /**
         * Set: Password
         *
         * @access protected
         * @param string $password
         * @return void
         */
        protected function setPassword($password)
        {
            return self::hashPassword($password);
        }

        /* -------------------------------- *\
        |  END:   PASSWORD-SPECIFIC METHODS  |
        |  BEING: IP-SPECIFIC METHODS        |
        \* -------------------------------- */

        public function ipAllowed($ip)
        {
            // Return true, whitelist and blacklists haven't been developed properly yet. This method, for now is just a
            // placeholder for the functionality provided to the UserIdentity component.
            return true;
        }

    }
