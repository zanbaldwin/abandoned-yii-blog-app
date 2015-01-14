<h1>
    <?php
        echo CHtml::encode($revision->title);
    ?>
</h1>
<p>
    <?php
        echo Yii::t(
            'application',
            'Posted by {author} on {date}.',
            array(
                '{author}' => is_string($post->Author->username) && !empty($post->Author->username)
                    ? CHtml::link(CHtml::encode($post->Author->displayName), array('profile/index', 'id' => $post->Author->id))
                    : CHtml::encode($post->Author->displayName),
                '{date}' => Yii::app()->dateFormatter->formatDateTime($post->created, 'long', null),
            )
        );
    ?>
</p>

<p><hr /></p>
<div class="content">
    <?php echo Yii::app()->markdown->render(CHtml::encode($revision->content)); ?>
</div>

<p>&nbsp;</p>

<div class="row">
    <!-- Revisions -->
    <div class="col-xs-12 col-md-6">
        <?php if(!empty($post->getRevisions()) && is_array($post->getRevisions()) && !empty($post->getRevisions())): ?>
            <?php $count = $i = count($post->getRevisions()); ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'Revisions'); ?></h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo Yii::t('application', '#'); ?></th>
                            <th><?php echo Yii::t('application', 'Author'); ?></th>
                            <th><?php echo Yii::t('application', 'Date'); ?></th>
                            <th><?php echo Yii::t('application', 'Status'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php /* Reverse the array of revisions, so that the latest is at the top, and iterate over each one. */ ?>
                        <?php foreach($post->getRevisions() as $postRevision): ?>
                            <tr>
                                <td>
                                    <strong>
                                        <?php
                                            switch($i) {
                                                case 1:
                                                    echo $postRevision->id === $revision->id
                                                        ? Yii::t('application', 'Original (#{revision})', array('{revision}' => $i))
                                                        : CHtml::link(
                                                            Yii::t('application', 'Original (#{revision})', array('{revision}' => $i)),
                                                            $postRevision->id === $post->getRevision()->id
                                                                ? array('posts/view', 'id' => $post->id, 'format' => $post->format)
                                                                : array('posts/view', 'id' => $post->id, 'format' => $post->format, 'revision' => $postRevision->id)
                                                        );
                                                    break;
                                                case $count:
                                                    echo $postRevision->id === $revision->id
                                                        ? Yii::t('application', 'Latest (#{revision})', array('{revision}' => $i))
                                                        : CHtml::link(
                                                            Yii::t('application', 'Latest (#{revision})', array('{revision}' => $i)),
                                                            $postRevision->id === $post->getRevision()->id
                                                                ? array('posts/view', 'id' => $post->id, 'format' => $post->format)
                                                                : array('posts/view', 'id' => $post->id, 'format' => $post->format, 'revision' => $postRevision->id)
                                                        );
                                                    break;
                                                default:
                                                    echo $postRevision->id === $revision->id
                                                        ? Yii::t('application', 'Revision #{revision}', array('{revision}' => $i))
                                                        : CHtml::link(
                                                            Yii::t('application', 'Revision #{revision}', array('{revision}' => $i)),
                                                            $postRevision->id === $post->getRevision()->id
                                                                ? array('posts/view', 'id' => $post->id, 'format' => $post->format)
                                                                : array('posts/view', 'id' => $post->id, 'format' => $post->format, 'revision' => $postRevision->id)
                                                        );
                                                    break;
                                            }
                                            $i--;
                                        ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php
                                        echo is_string($postRevision->Author->username) && !empty($postRevision->Author->username)
                                            ? CHtml::link(CHtml::encode($postRevision->Author->displayName), array('profile/index', 'id' => $postRevision->Author->id))
                                            : CHtml::encode($postRevision->Author->displayName);
                                    ?>
                                </td>
                                <td><?php echo Yii::app()->dateFormatter->formatDateTime($postRevision->created, 'long', 'short'); ?></td>
                                <td>
                                    <?php
                                        switch($postRevision->published) {
                                            case null:
                                                echo Yii::t('application', 'Draft');
                                                break;
                                            case 0:
                                                echo Yii::t('application', 'Pending');
                                                break;
                                            case 1:
                                                echo Yii::t('application', 'Published');
                                                break;
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'No Revisions'); ?></h3>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- Comments -->
    <div class="col-xs-12 col-md-6">
        <?php if(isset($post->Comments) && is_array($post->Comments) && !empty($post->Comments)): ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'Comments'); ?></h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo Yii::t('application', 'Author'); ?></th>
                            <th><?php echo Yii::t('application', 'Date'); ?></th>
                            <th><?php echo Yii::t('application', 'Comment'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($post->Comments as $comment): ?>
                            <tr>
                                <td>
                                    <?php
                                        echo is_string($revision->Author->username) && !empty($revision->Author->username)
                                            ? CHtml::link(CHtml::encode($revision->Author->displayName), array('profile/index', 'id' => $revision->Author->id))
                                            : CHtml::encode($revision->Author->displayName);
                                    ?>
                                </td>
                                <td><?php echo Yii::app()->dateFormatter->formatDateTime($comment->created, 'long', null); ?></td>
                                <td><?php echo CHtml::encode($comment->content); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'No Comments'); ?></h3>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- Categories -->
    <div class="col-xs-12 col-md-6">
        <?php if(isset($revision->Categories) && is_array($revision->Categories) && !empty($revision->Categories)): ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'Categories'); ?></h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo Yii::t('application', 'Category'); ?></th>
                            <th><?php echo Yii::t('application', 'Number of Posts'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Categories are linked to revisions, not posts. We'll have to so some lovely SQL magic
                            // here. We are selecting the number of posts whose latest revision contains a link to the
                            // category through the link table `{{post_category_assignments}}`.
                            $sql = 'SELECT COUNT(DISTINCT(`revisions`.`post`)) as `count`
                                    FROM `{{post_category_assignments}}` as `categories`
                                    LEFT JOIN `{{post_revisions}}` as `revisions`
                                        ON `revisions`.`id` = `categories`.`revision`
                                    WHERE `categories`.`category` = :category
                                      AND `revisions`.`id` = (
                                              SELECT MAX(`revision`.`id`) as `revision`
                                              FROM `{{post_revisions}}` as `revision`
                                              WHERE `revision`.`post` = `revisions`.`post`
                                          )';
                            $dbq = Yii::app()->db->createCommand($sql);
                        ?>
                        <?php foreach($revision->Categories as $category): ?>
                            <tr>
                                <td><?php echo CHtml::link(CHtml::encode($category->title), array('posts/category', 'id' => $category->id, 'format' => $post->format)); ?></td>
                                <td><?php echo $dbq->queryScalar(array(':category' => $category->id)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'No Categories'); ?></h3>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- Tags -->
    <div class="col-xs-12 col-md-6">
        <?php if(isset($revision->Tags) && is_array($revision->Tags) && !empty($revision->Tags)): ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'Tags'); ?></h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo Yii::t('application', 'Tag'); ?></th>
                            <th><?php echo Yii::t('application', 'Number of Posts'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Categories are linked to revisions, not posts. We'll have to so some lovely SQL magic here.
                            $sql = 'SELECT COUNT(DISTINCT(`revisions`.`post`)) as `count`
                                    FROM `{{post_tag_assignments}}` as `tags`
                                    LEFT JOIN `{{post_revisions}}` as `revisions`
                                        ON `revisions`.`id` = `tags`.`revision`
                                    WHERE `tags`.`tag` = :tag
                                      AND `revisions`.`id` = (
                                              SELECT MAX(`revision`.`id`) as `revision`
                                              FROM `{{post_revisions}}` as `revision`
                                              WHERE `revision`.`post` = `revisions`.`post`
                                          )';
                            $dbq = Yii::app()->db->createCommand($sql);
                        ?>
                        <?php foreach($revision->Tags as $tag): ?>
                            <tr>
                                <td><?php echo CHtml::link(CHtml::encode($tag->title), array('posts/tag', 'id' => $tag->id, 'format' => $post->format)); ?></td>
                                <td><?php echo $dbq->queryScalar(array(':tag' => $tag->id)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('application', 'No Tags'); ?></h3>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
