<?php

    namespace application\controllers;

    use \Yii;
    use \CException;
    use \application\components\Controller;
    use \application\annotations\rbac as Security;

    class HomeController extends Controller
    {

        /**
         * Action: Index
         *
         * @access public
         * @return void
         */
        public function actionIndex()
        {
            $this->render('index');
        }


        /**
         * Action: Restricted
         *
         * @access public
         * @Security\Roles({"admin"})
         * @return void
         */
        public function actionRestricted()
        {
            $this->render('restricted');
        }

    }
