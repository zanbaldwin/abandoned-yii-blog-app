<?php
    $this->pageTitle = Yii::t(
        'application',
        '{format} with the tag "{tag}"',
        array(
            '{format}' => ucwords(Yii::t('application', $tag->Format->post, array(2))),
            '{tag}' => $tag->title,
        )
    );
?>
<h1>
    <?php
        echo CHtml::encode($tag->title);
    ?>
</h1>
<p>
    <?php
        echo Yii::t(
            'application',
            'Found {n} post with the tag "{tag}".|Found {n} posts with the tag "{tag}".',
            array(
                count($posts),
                '{tag}' => CHtml::encode($tag->title),
            )
        );
    ?>
</p>

<p><hr /></p>
<p>&nbsp;</p>

<?php if(isset($posts) && is_array($posts) && !empty($posts)): ?>
    <ul>
        <?php foreach($posts as $post): ?>

            <li>
                <h3>
                    <?php
                        echo CHtml::link(
                            CHtml::encode($post->title),
                            array('posts/view', 'id' => $post->id, 'format' => $tag->format ?: $post->format)
                        );
                    ?>
                </h3>
            </li>

        <?php endforeach; ?>
    </ul>
<?php endif; ?>
