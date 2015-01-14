<?php
    $this->pageTitle = ucwords(Yii::t('application', $format->series, array(1)));
    $this->breadcrumbs = array(
        $this->pageTitle,
    );
?>

<?php if(Yii::app()->user->checkAccess('create site post')): ?>
    <div class="pull-right">
        <?php
            echo CHtml::link(
                Yii::t(
                    'application',
                    'Create new {post}',
                    array(
                        '{post}' => Yii::t(
                            'application',
                            $format->post,
                            array(1)
                        )
                    )
                ),
                array('/posts/create', 'format' => $format->id),
                array('class' => 'btn btn-success')
            );
        ?>
    </div>
<?php endif; ?>


<h1><?php echo ucwords(Yii::t('application', $format->post, array(2))); ?></h1>

<section class="posts">
    <?php if(isset($posts) && is_array($posts) && !empty($posts)): ?>
        <?php foreach($posts as $post): ?>
            <?php if(!empty($post->getRevision()) && $post->getRevision() instanceof \application\models\db\posts\Revision): ?>
                <article class="post">

                    <!-- DEFINITION DESIGN BLOCK -->
                    <?php
                        switch($post->getRevision()->published) {
                            case null:
                                $panelClass = 'danger';
                                break;
                            case 0:
                                $panelClass = 'info';
                                break;
                            default:
                                $panelClass = 'primary';
                                break;
                        }
                    ?>
                    <div class="panel panel-<?php echo $panelClass; ?>">

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-book"></span>
                                <?php
                                    echo CHtml::link(
                                        $post->title,
                                        array('/posts/view', 'id' => $post->id, 'format' => $post->format)
                                    );
                                ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <p>
                                <?php
                                    echo Yii::t(
                                        'application',
                                        'Posted by {author} on {date}. This post has {ncomments}.',
                                        array(
                                            '{author}' => CHtml::link($post->Author->displayName, array('profile/index', 'id' => $post->Author->id)),
                                            '{date}' => Yii::app()->dateFormatter->formatDateTime($post->created, 'long', null),
                                            '{ncomments}' => ($count = count($post->Comments))
                                                ? CHtml::link(
                                                    Yii::t('application', '{n} comment|{n} comments', array($count)),
                                                    array('/posts/view', 'id' => $post->id, 'format' => $post->format, '#' => 'comments')
                                                  )
                                                : Yii::t('application', 'no comments'),
                                        )
                                    );
                                ?>
                                <?php if(count($post->getRevisions()) > 1): ?>
                                    <br />
                                    <?php
                                        echo Yii::t(
                                            'application',
                                            'This post was last revised on {date}.',
                                            array(
                                                '{date}' => Yii::app()->dateFormatter->formatDateTime($post->getRevision()->created, 'long', null),
                                            )
                                        );
                                    ?>
                                <?php endif; ?>
                            </p>
                        </div>

                    <!-- DEFINITION DESIGN BLOCK -->
                    </div>

                </article>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php
            /*
                $this->widget('\\CLinkPager', array(
                    'cssFile'               => null,
                    'footer'                => null,
                    'header'                => null,
                    'maxButtonCount'        => 7,
                    'pages'                 => $pagination,
                    'htmlOptions'           => array('class' => 'pagination'),
                    'firstPageLabel'        => Yii::t('application', '&lt;'),
                    'lastPageLabel'         => Yii::t('application', '&gt;'),
                    'nextPageLabel'         => Yii::t('application', '&raquo;'),
                    'prevPageLabel'         => Yii::t('application', '&laquo;'),
                    'firstPageCssClass'     => null,
                    'hiddenPageCssClass'    => 'disabled',
                    'internalPageCssClass'  => null,
                    'lastPageCssClass'      => null,
                    'nextPageCssClass'      => null,
                    'previousPageCssClass'  => null,
                    'selectedPageCssClass'  => 'active',
                ));
            /**/
        ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <strong><?php echo Yii::t('application', 'Sorry'); ?></strong><br />
            <?php
                echo Yii::t(
                    'application',
                    'There are no {postformat} available.',
                    array(
                        '{postformat}' => Yii::t('application', $format->post, array(2)),
                    )
                );
            ?>
        </div>
    <?php endif; ?>
</section>
