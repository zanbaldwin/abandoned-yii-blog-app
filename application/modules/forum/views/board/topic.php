<?php
$this->pageTitle = Yii::t('chaser', 'Topic - '.$topic->name);
$this->breadcrumbs = array(
    Yii::t('chaser', 'Board Index') => array('/forums/index'),
    Yii::t('chaser', $topic->Parent->Parent->name) => array('/forums/index'),
    Yii::t('chaser', $topic->Parent->name) => array('/forums/forum', 'id' => $topic->Parent->id),
    Yii::t('chaser', $topic->name) => array('/forums/topic', 'id' => $topic->id),
    );

$ranks = array(
    NULL => 'Basic User',
    10 => 'Basic User',
    20 => 'Branch Manager',
    30 => 'Regional Manager',
    40 => 'Branch Admin',
    70 => 'Organisation Admin',
    90 => 'Nosco Staff',
    100 => 'Nosco Admin',
);
?>

<?php if(Yii::app()->user->priv < $topic->Parent->view): ?>
    <div class="alert alert-warning">
        <strong>Access Denied</strong>
        <br />
        You do not have a sufficient permission level to view this topic.
    </div>
<?php endif; ?>

<hgroup>
    <h2><?php echo CHtml::encode($topic->name); ?></h2>
</hgroup>

<?php if(count($topic->Children) > 0): ?>
    <?php foreach($topic->Children as $post): ?>
        <div id="p<?php echo $post->id ?>" class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <h4>
                            <strong>
                                <?php echo CHtml::link(ucwords(strtolower($post->User->username)), array('/profile', 'id' => $post->user), array()); ?>
                            </strong>
                        </h4>
                        <?php 
                        $imgSrc = "";
                        if($post->User->avatar){
                            if(strpos($post->User->avatar, "http://") !== false || strpos($post->User->avatar, "www.") !== false || strpos($post->User->avatar, "https://") !== false){
                                $imgSrc = $post->User->avatar;
                            } else {
                                $imgSrc = Yii::app()->assetManager->publish(Yii::app()->theme->basePath . '/assets') . '/' . $post->User->avatar;
                            }
                        } elseif($post->User->Contact && $post->User->Contact->email){
                            $imgSrc = 'http://www.gravatar.com/avatar/'.md5( strtolower( trim( $post->User->Contact->email ) ) ). '&d=mm';
                        } else {
                            $imgSrc = Yii::app()->assetManager->publish(Yii::app()->theme->basePath . '/assets') . '/images/mm.png';
                        }
                        ?>
                        <?php echo CHtml::image($imgSrc, 'Avatar', array('class'=>'img-circle', 'width' => 80, 'height' => 80)); ?>
                        <br />
                        <?php if($post->User->privilege >= 90): ?>
                            <span class="text-danger">
                                <?php echo \application\components\Rank::getName($post->User->privilege);; ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">
                                <?php echo \application\components\Rank::getName($post->User->privilege);; ?>
                            </span>
                        <?php endif; ?>
                        <br />
                        <span class="text-muted">
                            <?php $posts = count($post->User->posts); ?>
                            <?php echo $posts; ?> post<?php if($posts != 1) echo "s"; ?>
                        </span>
                    </div>

                    <div class="col-md-10">
                        <span class="text-muted pull-right">
                            <small><i>Posted:</i> <?php echo Yii::app()->dateFormatter->formatDateTime($post->created, 'short', 'short'); ?></small>
                        </span>
                        <br />
                        <div id="textArea<?php echo $post->id ?>">
                            <?php echo $post->text; ?>
                        </div>
                        <?php if(Yii::app()->user->priv >= 90 || $post->user == Yii::app()->user->id): ?>
                            <br />
                            <div class="row">
                                <div class="col-md-1 col-md-offset-10">
                                    <table border="0">
                                        <tr>
                                            <td id="editElementLink"><?php echo CHtml::link('Edit', NULL, array('class' => 'btn btn-xs btn-primary', 'edit' => $post->id)); ?></td>
                                            <?php if($topic->Children[0]->id != $post->id && Yii::app()->user->priv >= 90 ): ?>
                                                <td id="deleteElementLink"><?php echo CHtml::link('Delete', NULL, array('class' => 'btn btn-xs btn-danger', 'del' => $post->id)); ?></td>
                                            <?php endif; ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <br />
    <?php if(Yii::app()->user->priv >= $topic->Parent->use): ?>
        <div id="reply"></div>
    <?php endif; ?>

    <script>
    $(document).ready( function(){
        <?php if(Yii::app()->user->priv >= $topic->Parent->use): ?>
            $('#reply').load( baseUrl + '/forums/create?id=<?php echo $topic->id; ?>');
        <?php endif; ?>

        <?php if(Yii::app()->user->priv >= 90): ?>
            $('#deleteElementLink a').click( function(){
                var del = $(this).attr("del");
                $('#p' + del).fadeOut();
                $('#gArea').load( baseUrl + '/forums/delete?id=' + del);
            });
        <?php endif; ?>

        $('#editElementLink a').click( function(){
            var edit = $(this).attr("edit");
            var area = '#textArea' + edit;
            $(area).load( baseUrl + '/forums/edit?id=' + edit);
        });

        <?php if(isset($_GET['show'])): ?>
            $("html, body").animate({ scrollTop: $("#<?php echo $_GET['show'] ?>").offset().top }, 1000);
        <?php endif; ?>
    });
    </script>
<?php else: ?>
    <div class="alert alert-warning">
        <strong>No Posts Found</strong>
        <br />
        Please alert a Nosco Administrator on <?php echo CHtml::link('developer@nosco-systems.com', 'mailto:developer@nosco-systems.com', array('class' => 'alert-link')); ?> with the following details:
        <br /><br />
        <?php echo __FILE__; ?>:<?php echo __LINE__; ?>
        ID: <?php echo $topic->id; ?>
    </div>
<?php endif; ?>