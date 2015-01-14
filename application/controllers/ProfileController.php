<?php

    namespace application\controllers;

    use \Yii;
    use \CException;
    use \application\components\Controller;

    class ProfileController extends Controller
    {


        /**
         * Action: View
         *
         * @access public
         * @return void
         */
        public function actionIndex($id)
        {
            $user = \application\models\db\User::model()->findByPk($id);
            var_dump($user);
        }

    }
