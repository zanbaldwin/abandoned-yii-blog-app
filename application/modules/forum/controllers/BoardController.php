<?php

    use \Yii;
    use \CException;
    use \CHttpException as HttpException;
    use \application\components\Controller;

    class BoardController extends Controller
    {
        public function filters()
        {
            return array(
                'accessControl',
            );
        }

        public function accessRules()
        {
            return array(
                // Allow authenticated (logged-in) users to access this controller.
                // @ = Authenticated Users, ? = Non-Authenticated Users, * = All Users (Anyone), Can also specify usernames, 'priv' => 70 for user levels
                array('allow',
                    'users' => array('*'),
                ),
                // Deny access to everyone else.
                array('deny'),
            );
        }

        public function actionIndex()
        {   
            $categories = \application\models\db\Forum::model()->findAllByAttributes(array(
                'parent' => NULL,
            ));

            $this->render('index', array(
                'categories' => $categories,
            ));
        }

        public function actionForum()
        {
            // First check if a category has been supplied
            if(!isset($_GET['id']))
                throw new HttpException(400, "A forum has not been supplied, please go back and try again.");

            $id = $_GET['id'];

            $forum = \application\models\db\Forum::model()->findByPk($id);

            if(!$forum)
                throw new HttpException(400, "A valid forum has not been supplied, please go back and try again.");

            if($forum->Parent != NULL && $forum->Parent->Parent == NULL){} else {
                throw new HttpException(400, "A valid forum has not been supplied, please go back and try again.");
            }

            $this->render('forum', array(
                'forum' => $forum,
            ));
        }

        public function actionTopic()
        {
            // First check if a category has been supplied
            if(!isset($_GET['id']))
                throw new HttpException(400, "A topic has not been supplied, please go back and try again.");

            $id = $_GET['id'];

            $topic = \application\models\db\Forum::model()->findByPk($id);

            if($topic->Parent != NULL && $topic->Parent->Parent != NULL && $topic->Parent->Parent->Parent == NULL){} else {
                throw new HttpException(400, "A valid topic has not been supplied, please go back and try again.");
            }

            if(!$topic)
                throw new HttpException(400, "A valid topic has not been supplied, please go back and try again.");

            $this->render('topic', array(
                'topic' => $topic,
            ));
        }

        public function actionDelete()
        {
            if(!isset($_GET['id']))
                throw new HttpException(400, "An ID has not been supplied.");

            $element = \application\models\db\Forum::model()->findByPk($_GET['id']);

            if($element){
                if(Yii::app()->user->checkAccess('admin')){
                    $element->delete();
                }
            }
        }

        public function actionCreate()
        {
            $form = NULL;
            $elementName = NULL;

            if(!isset($_GET['id'])){ // This means that the user is trying to create a category
                if(Yii::app()->user->checkAccess('create forum category')){ // The user is an admin, thus, allowed to create a category.
                    $form = $this->postCreate(NULL);
                    $elementName = "Category";
                }
            } else {
                $id = $_GET['id'];
                $parent = \application\models\db\Forum::model()->findByPk($id);

                if($parent){
                    if(Yii::app()->user->checkAccess($parent->use)){
                        $form = $this->postCreate($id);
                        if($parent->Parent == NULL)
                            $elementName = "Forum";
                        elseif($parent->Parent->Parent == NULL)
                            $elementName = "Topic";
                        else
                            $elementName = "Post";
                    }
                }
            }

            $variables = array(
                'form' => $form,
                'render' => false,
                'elementName' => $elementName,
            );

            if(Yii::app()->request->isAjaxRequest){
                $this->renderPartial('create', $variables);
            } else {
                $variables['render'] = true;
                $this->render('create', $variables);
            }
        }

        public function postCreate($parent)
        {
            $form = new \application\components\Form('application.forms.forum.create', new \application\models\form\forum\Create);

            if($form->submitted()){
                if($form->validate()){
                    // Check that the parent exists.
                    $parentElement = \application\models\db\Forum::model()->findByPk($parent);

                    if($parentElement || $parent == NULL){
                        $forumElement = new \application\models\db\Forum;
                        $forumElement->attributes = $form->model->attributes;
                        $forumElement->parent = $parent;
                        
                        if(!$form->model->use || !$form->model->view){
                            if($parent != NULL){
                                $forumElement->use = $parentElement->use;
                                $forumElement->view = $parentElement->view;
                            } else {
                                $forumElement->use = NULL;
                                $forumElement->view = NULL;
                            }
                        } else {
                            $forumElement->use = $form->model->use;
                            $forumElement->view = $form->model->view;
                        }

                        // Posts do not require names.
                        if($parent != NULL
                            && $parentElement->Parent != NULL
                            && $parentElement->Parent->Parent != NULL
                            && $parentElement->Parent->Parent->Parent == NULL)
                                $forumElement->name = NULL;

                        if($parent == NULL 
                            || ($parent != NULL
                            && $parentElement->Parent != NULL
                            && $parentElement->Parent->Parent == NULL) )
                            $forumElement->text = NULL;

                        $forumElement->user = Yii::app()->user->id;
                        $forumElement->created = time();
                        $forumElement->save();

                        // A topic needs a starting post.
                        if($parent != NULL
                            && $parentElement->Parent != NULL
                            && $parentElement->Parent->Parent == NULL){
                            // Topic has been created, make the first post.
                            $postElement = new \application\models\db\Forum;
                            $postElement->name = NULL;
                            $postElement->text = $form->model->text;
                            $postElement->parent = $forumElement->id;
                            $postElement->user = Yii::app()->user->id;
                            $postElement->created = time();
                            $postElement->save();
                        }



                        if($parentElement == NULL) // Category made.
                            $this->redirect(array('/forums/index'));
                        elseif($parentElement->Parent == NULL) // Forum made.
                            $this->redirect(array('/forums/forum', 'id' => $forumElement->id));
                        elseif($parentElement->Parent->Parent == NULL) // Topic made.
                            $this->redirect(array('forums/topic', 'id' => $forumElement->id));
                        else // Post made
                            $this->redirect(array('forums/topic', 'id' => $parent, 'show' => 'p'.$forumElement->id));
                    } else {
                        $form->model->addError('name', 'The parent you are trying to create an element for does not exist.');
                    }
                }
            }

            return $form;
        }

        public function actionEdit($id)
        {
            $post = \application\models\db\Forum::model()->findByPk($id);

            if($post){
                // If the post has parent.
                // If the topic has a parent.
                // If the forum has a parent.
                // If the category does not have a parent.
                if(    $post->Parent != NULL
                    && $post->Parent->Parent != NULL
                    && $post->Parent->Parent->Parent != NULL
                    && $post->Parent->Parent->Parent->Parent == NULL){
                    // Check if the post belongs to the user or the user is an admin.
                    if(Yii::app()->user->priv >= 90 || Yii::app()->user->id == $post->user){
                        $form = $this->postEdit($post);

                        // Render the edit view with the post included.
                        $variables = array(
                            'form' => $form,
                            'post' => $post,
                            'render' => false,
                        );

                        if(Yii::app()->request->isAjaxRequest){
                            $this->renderPartial('edit', $variables);
                        } else {
                            $variables['render'] = true;
                            $this->render('edit', $variables);
                        }
                    }
                }
            }
        }

        public function postEdit($post)
        {
            $form = new \application\components\Form('application.forms.forum.edit', new \application\models\form\forum\Edit);

            if($form->submitted()){
                if($form->validate()){
                    if($post){
                        $post->text = $form->model->text;
                        $post->edited = 1;
                        $post->edited_time = time();
                        $post->edited_user = Yii::app()->user->id;
                        $post->save();

                        Yii::app()->user->setFlash('success', 'Post edited Successfully');
                        $this->redirect(array('/forums/topic', 'id' => $post->parent, 'show' => 'p'.$post->id));
                    }
                }
            }

            return $form;
        }

    }
