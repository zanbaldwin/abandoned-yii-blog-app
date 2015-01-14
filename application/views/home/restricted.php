<?php
    $this->pageTitle = Yii::t('application', 'Restricted Area');
    $this->breadcrumbs = array(
        $this->pageTitle,
    );
?>
<h1><?php echo Yii::t('application', 'Restricted Area'); ?></h1>

<p>
    <?php
        echo Yii::t(
            'application',
            'Sorry to disappoint you, but this restricted area only shows off the capabilities of the {rbac} system the current project implements.',
            array(
                '{rbac}' => CHtml::tag(
                    'abbr',
                    array(
                        'title' => Yii::t('application', 'Role-based Access Control'),
                    ),
                    Yii::t('application', 'RBAC')
                ),
            )
        );
    ?>

</p>
<p><?php echo Yii::t('application', 'Nothing to see here, move along!'); ?></p>
