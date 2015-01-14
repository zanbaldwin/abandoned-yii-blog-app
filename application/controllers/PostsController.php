<?php

    namespace application\controllers;

    use \Yii;
    use \CException;
    use \CHttpException;
    use \application\components\Controller;

    class PostsController extends Controller
    {

        /**
         * Action: Index
         *
         * The following are the permissions used in this action:
         *  - view site draft posts
         *  - view site pending posts
         *  - view site published posts (default)
         *
         * @access public
         * @param string $format "The post format to list."
         * @param integer $user "An optional user ID. Specifying this will return the users posts instead of site-wide posts."
         * @param integer $page "The page number to control the paginated post list."
         * @return void
         */
        public function actionIndex($format)
        {
            // Before we continue. Make sure that the post format specified actually exists within the database.
            if(!is_object($format)) {
                $format = \application\models\db\posts\Format::model()->findByPk($format);
            }
            if($format === null) {
                throw new CHttpException(
                    400,
                    Yii::t('application', 'The post format you specified does not exist.')
                );
            }

            // Create the database criteria by using the command builder. Yeah, I know it's stupid, but without going
            // into using pure SQL it's the best thing we have. And since we want to use Yii's pagination component we
            // have to. Stupid Yii. I'm going to have to build my own mashup of CDbCriteria and CDbCommand soon. I'll go
            // insane trying to work like this for the rest of the project...
            $command = Yii::app()->db->createCommand();
            // Create the condition, concatenating the main elements with AND.
            $command->where = array('and',
                // We only want posts of the current format, obviously. Blog posts belong in the blog list, and so on.
                'post.format = :format',
                // This controller deals with site-wide posts, not army or user specific posts. Specify that we want
                // global ownership (the site).
                'post.owner IS NULL',
                // Concatenate elements in the sub-condition for published status with OR.
                array('or',
                    // We want to select posts that are either:
                    // 1) Published (providing they have permission to).
                    Yii::app()->user->checkAccess('view site published posts')  ? 'revision.published = :published' : 'false',
                    // 2) The post revision was written by the current user (either the post belongs to them, or they
                    //    have made edits to other peoples posts).
                    'revision.author = :author',
                    // 3) Pending (providing they have permission to).
                    Yii::app()->user->checkAccess('view site pending posts')    ? 'revision.published = :pending'   : 'false',
                    // 4) Draft (providing they have permission to).
                    //    Oh look, this one has to be different because PDO can't handle null values. WHAT A SURPRISE.
                    Yii::app()->user->checkAccess('view site draft posts')      ? 'revision.published IS NULL'      : 'false',
                ),
            );
            // See what I mean? The database criteria object can only accept string conditions. The command builder's
            // where is a magic property which accepts an array format and output a string format.
            $criteria = new \CDbCriteria;
            // Assign the string condition to the database criteria. At least we can do everything else with the
            // crtieria object rather than import from the command builder (such as joins).
            $criteria->condition = $command->where;
            // Provide an alias for the table we are selecting from (since it may be prefixed). Then specifiy that we
            // only want to select the columns from the post table (we do not want the revision columns coming into the
            // post model, plus there would be a clash of primary keys).
            $criteria->alias = 'post';
            $criteria->select = 'post.*';
            // We need information from the post revision table to determine which posts we need, join the two tables
            // together. Note we are using the command builder again for this (we can do it with the database criteria,
            // but since it only accepts string format this is better).
            $command->leftJoin('{{post_revisions}} revision', 'revision.post = post.id');
            $criteria->join = implode($command->join);
            $criteria->order = 'created DESC';
            // Finally, we only want to return one record for each post. Group by post ID because the left join will
            // return many for each ID if there are multiple revisions for any of them.
            $criteria->group = 'post.id';

            $parameters = array(
                ':published' => true,
                ':author' => Yii::app()->user->id,
                ':pending' => false,
                ':format' => $format->id,
            );
            foreach($parameters as $placeholder => $param) {
                if(strpos($criteria->condition, $placeholder) !== false) {
                    $criteria->params[$placeholder] = $param;
                }
            }

            // Get the total number of posts available.
            $postCount = \application\models\db\posts\Post::model()->count($criteria);
            // Create the pagination object, and set the parameters. Remember that the $pageVar defaults to "page" so we
            // don't have to set that.
            $pagination = new \CPagination($postCount);
            $pagination->pageSize = 10;
            $pagination->applyLimit($criteria);

            $posts = \application\models\db\Posts\Post::model()->findAll($criteria);

            $this->render('list', array(
                'format' => $format,
                'posts' => $posts,
                'pagination' => $pagination,
            ));
        }

        /**
         * Action: View
         *
         * The following are the permissions used in this action:
         *  - view site draft posts
         *  - view site pending posts
         *  - view site published posts
         *
         * @access public
         * @throws \CHttpException
         * @param integer $id "The ID of the post to view."
         * @param integer $format "The ID of the post format that the post should be in."
         * @param integer $revision "An optional revision ID. If this is not specified, then the latest revision will be used."
         * @param integer $user "An optional user ID. If this is specified, search for a user post rather than a site-wide post."
         * @return void
         */
        public function actionView($id, $format, $revision = null)
        {
            if(!is_object($format)) {
                $format = \application\models\db\posts\Post::model()->findByPk($format);
            }
            if(!$format instanceof \application\models\db\posts\Format) {
                throw new CHttpException(
                    400,
                    Yii::t('application', 'The post format you specified does not exist.')
                );
            }

            $command = Yii::app()->db->createCommand();
            $command->where = array('and',
                'post.owner IS NULL',
                'post.format = :format',
                'post.id = :id',
                array('or',
                    Yii::app()->user->checkAccess('view site published posts')  ? 'revision.published = :published' : 'false',
                    Yii::app()->user->checkAccess('view site pending posts')    ? 'revision.published = :pending'   : 'false',
                    Yii::app()->user->checkAccess('view site draft posts')      ? 'revision.published IS NULL'      : 'false',
                    'revision.author = :author',
                ),
            );
            $command->leftJoin(
                \application\models\db\posts\Revision::model()->tableName() . ' revision',
                'revision.post = post.id'
            );
            $criteria = new \CDbCriteria;
            $criteria->alias = 'post';
            $criteria->condition = $command->where;
            $criteria->join = implode($command->join);

            $parameters = array(
                ':format' => $format->id,
                ':id' => $id,
                ':published' => true,
                ':pending' => false,
                ':author' => Yii::app()->user->id,
            );
            foreach($parameters as $placeholder => $param) {
                if(strpos($criteria->condition, $placeholder) !== false) {
                    $criteria->params[$placeholder] = $param;
                }
            }

            $post = \application\models\db\posts\Post::model()->find($criteria);
            if($post === null) {
                throw new CHttpException(404, Yii::t('application', 'The post you specified could not be found.'));
            }

            // The function end() only works on variables, but $post->Revisions is a magic property. Copy its contents
            // into a temporary variable.
            $revision = ($revision === null)
                ? $post->getRevision()
                : \application\models\db\posts\Revision::model()->findByAttributes(array(
                    'id' => $revision,
                    'post' => $post->id,
                ));
            if($revision === null) {
                throw new CHttpException(404, Yii::t('application', 'The particular post revision you specified could not be found.'));
            }

            $this->render('view', array(
                'post' => $post,
                'revision' => $revision,
            ));
        }


        /**
         * Action: Create Post
         *
         * The following are the permissions used in this action:
         *  - create site posts
         *  - publish site posts
         *  - create post tags
         *
         * @access public
         * @param integer $format
         * @return void
         */
        public function actionCreate($format)
        {
            if(!is_object($format)) {
                $format = \application\models\db\posts\Post::model()->findByPk($format);
            }
            if(!$format instanceof \application\models\db\posts\Format) {
                throw new CHttpException(
                    400,
                    Yii::t('application', 'The post format you specified does not exist.')
                );
            }

            if(!Yii::app()->user->checkAccess('create site posts')) {
                if(Yii::app()->user->isGuest) {
                    Yii::app()->user->loginRequired();
                }
                else {
                    throw new \CHttpException(
                        403,
                        Yii::t(
                            'application',
                            'You do not have sufficient privileges to create site-wide {posts}.',
                            array(
                                '{posts}' => Yii::t('application', $format->post, array(2))
                            )
                        )
                    );
                }
            }

            $form = new \application\components\form\Form('application.forms.posts.post', new \application\models\form\posts\Post);
            if($form->submitted() && $form->validate()) {

            }
            $this->render('create', array(
                'format' => $format,
                'form' => $form,
            ));
        }


        /**
         * Action: Tag
         *
         * @access public
         * @param integer $id "The ID of the tag to view."
         * @param integer $format "The ID of the post format the tag is for."
         * @param integer $user "An optional user ID. If this is specified, search for a user tag rather than a site-wide tag."
         * @return void
         */
        public function actionTag($id, $format, $user = null)
        {
            if(!is_object($format)) {
                $format = \application\models\db\posts\Format::model()->findByPk($format);
            }
            if(!$format instanceof \application\models\db\posts\Format) {
                throw new CHttpException(
                    400,
                    Yii::t('application', 'The post format you specified does not exist.')
                );
            }

            $tag = is_object($id)
                ? $id
                : \application\models\db\posts\Tag::model()->findByAttributes(array(
                    'id' => $id,
                    'format' => $format->id,
                ));
            if(!$tag instanceof \application\models\db\posts\Tag) {
                throw new CHttpException(
                    404,
                    Yii::t('application', 'The tag you specified does not exist.')
                );
            }

            // We only want to select the posts that have tags assigned to their most recent revisions. This subquery
            // returns the ID of each posts most recent revision. We are using the command builder because quite frankly
            // CDbCriteria is shockingly SHITE at subqueries. Really.
            $subQuery = Yii::app()->db->createCommand()
                ->select('MAX(revision.id) id')
                ->from('{{post_revisions}} revision')
                ->leftJoin('{{posts}} post', 'revision.post = post.id')
                ->group('post.id');

            // Next we need to create an instance of a database criteria object.
            $criteria = new \CDbCriteria;
            $criteria->select = 'post.*';
            $criteria->alias = 'post';
            $criteria->order = 'post.created DESC';
            $criteria->join = implode(' ', array(
                'LEFT JOIN {{post_revisions}}         revision        ON revision.post            = post.id',
                'LEFT JOIN {{post_tag_assignments}}   tag_assignment  ON tag_assignment.revision  = revision.id',
                'LEFT JOIN {{post_tags}}              tag             ON tag_assignment.tag       = tag.id',
            ));
            $criteria->addCondition(array(
                'tag.id = :tag',
                'revision.id IN (' . $subQuery->getText() . ')',
            ));
            // Define the parameters that should replace the named placeholders.
            $criteria->params = array(
                ':tag' => $tag->id,
            );

            $count = \application\models\db\posts\Post::model()->count($criteria);
            $pagination = new \CPagination($count);
            $pagination->pageSize = 20;
            $pagination->applyLimit($criteria);

            $posts = \application\models\db\posts\Post::model()->findAll($criteria);

            $this->render('tag', array(
                'tag' => $tag,
                'posts' => $posts,
            ));
        }


        /**
         * Action: Category
         *
         * @access public
         * @param integer $id "The ID of the category to view."
         * @param integer $format "The ID of the post format the category is for."
         * @param integer $user "An optional user ID. If this is specified, search for a user category rather than a site-wide category."
         * @return void
         */
        public function actionCategory($id, $format, $user = null)
        {
            if(!is_object($format)) {
                $format = \application\models\db\posts\Format::model()->findByPk($format);
            }
            if(!$format instanceof \application\models\db\posts\Format) {
                throw new CHttpException(
                    400,
                    Yii::t('application', 'The post format you specified does not exist.')
                );
            }

            $category = is_object($id)
                ? $id
                : \application\models\db\posts\Category::model()->findByAttributes(array(
                    'id' => $id,
                    'format' => $format->id,
                ));
            if(!$category instanceof \application\models\db\posts\Category) {
                throw new CHttpException(
                    404,
                    Yii::t('application', 'The category you specified does not exist.')
                );
            }

            // We only want to select the posts that have tags assigned to their most recent revisions. This subquery
            // returns the ID of each posts most recent revision. We are using the command builder because quite frankly
            // CDbCriteria is shockingly SHITE at subqueries. Really.
            $subQuery = Yii::app()->db->createCommand()
                ->select('MAX(revision.id) id')
                ->from('{{post_revisions}} revision')
                ->leftJoin('{{posts}} post', 'revision.post = post.id')
                ->group('post.id');

            // Next we need to create an instance of a database criteria object.
            $criteria = new \CDbCriteria;
            $criteria->select = 'post.*';
            $criteria->alias = 'post';
            $criteria->order = 'post.created DESC';
            $criteria->join = implode(' ', array(
                'LEFT JOIN {{post_revisions}}               revision            ON revision.post                = post.id',
                'LEFT JOIN {{post_category_assignments}}    category_assignment ON category_assignment.revision = revision.id',
                'LEFT JOIN {{post_categories}}              category            ON category_assignment.category = category.id',
            ));
            $criteria->addCondition(array(
                'category.id = :category',
                'revision.id IN (' . $subQuery->getText() . ')',
            ));
            // Define the parameters that should replace the named placeholders.
            $criteria->params = array(
                ':category' => $category->id,
            );

            $posts = \application\models\db\posts\Post::model()->findAll($criteria);
            $this->render('category', array(
                'category'  => $category,
                'posts'     => $posts,
            ));
        }


        /**
         * Action: List Tags
         *
         * @access public
         * @param integer $format "The ID of the post format to list tags for."
         * @return void
         */
        public function actionTags($format, $sort = null, $user = null)
        {
            if(!is_object($format)) {
                $format = \application\models\db\posts\Format::model()->findByPk($format);
            }
            if(!$format instanceof \application\models\db\posts\Format) {
                throw new CHttpException(
                    400,
                    Yii::t('application', 'The post format you specified does not exist.')
                );
            }
            $criteria = new \CDbCriteria;
            $criteria->alias = 'tags';
            $criteria->addCondition(array(
                'tag.format = :format',
            ));
            $criteria->params = array(
                ':format' => $format->id,
            );
            $tags = \application\models\db\posts\Tag::model()->findAll($criteria);
        }


        public function actionCategories($format) {}
        public function commentForm() {} // Create and return the form object, so actionComment() can process it, and others can display it.
        public function actionComment($post, $format) {} // posting a comment
        public function actionSeries($id) {} // List posts within a series
        public function actionEdit($post, $format) {}
        public function actionDelete($post, $format) {}
        public function actionPublish($post, $format) {} // Publish a draft post, or approve a pending post.

        // Admin functionality:
        //     manage (create, edit, delete) formats.
        //     manage (create, edit, delete) tags and categories.
        //     manage post series.

    }
