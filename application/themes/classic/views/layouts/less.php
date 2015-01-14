<?php

    // Initial options for compiling and caching.
    $lessOptions = array(
        'compress' => true,
        'cache_dir' => Yii::getPathOfAlias('application.runtime.less'),
        'sourceMap' => true,
    );

    /* =================== *\
    |  BOOTSTRAP FRAMEWORK  |
    \* =================== */

    $bootstrapFiles = array(
        Yii::app()->theme->basePath . '/resources/bootstrap.less'               => null,
    );
    // Compile and symlink into the theme assets.
    $bsSymlink = Yii::app()->theme->basePath . '/assets/css/bootstrap.css';
    $bsCompiled = $lessOptions['cache_dir'] . '/' . \Less_Cache::Get($bootstrapFiles, $lessOptions);
    if(!file_exists($bsSymlink) || !is_link($bsSymlink) || readlink($bsSymlink) != $bsCompiled) {
        file_exists($bsSymlink) && unlink($bsSymlink);
        symlink(
            $bsCompiled,
            $bsSymlink
        );
    }

    /* ================= *\
    |  THEME STYLESHEETS  |
    \* ================= */

    $themeFiles = array(
        Yii::getPathOfAlias('composer.twbs.bootstrap') . '/less/theme.less'     => null,
        Yii::app()->theme->basePath . '/resources/glyphicons.less'              => null,
        Yii::app()->theme->basePath . '/resources/main.less'                    => null,
    );
    // Compile and symlink into the theme assets.
    $themeSymlink = Yii::app()->theme->basePath . '/assets/css/styles.css';
    $themeCompiled = $lessOptions['cache_dir'] . '/' . \Less_Cache::Get($themeFiles, $lessOptions);
    if(!file_exists($themeSymlink) || !is_link($themeSymlink) || readlink($themeSymlink) != $themeCompiled) {
        file_exists($themeSymlink) && unlink($themeSymlink);
        symlink(
            $themeCompiled,
            $themeSymlink
        );
    }

?>

<!-- Compiled LESS stylesheets. -->
<?php
    // First grab the URL of the theme's published assets.
    $theme = Yii::app()->assetManager->publish(Yii::app()->theme->basePath . '/assets');
    // Hash the name of the cached compiled stylesheets, so that when it gets recached a new unique URL is rendered.
    // "Asset Path; Styles Path; Unique Hash".
?>
<link href="<?php echo $theme; ?>/css/bootstrap.css?<?php echo md5($bsCompiled); ?>" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo $theme; ?>/css/styles.css?<?php echo md5($themeCompiled); ?>" rel="stylesheet" type="text/css" media="all" />
