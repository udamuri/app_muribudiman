<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'PT TRI SINAR PURNAMA',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/home']],
        //['label' => 'About', 'url' => ['/about']],
        //['label' => 'Contact', 'url' => ['/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/login']];
    } else {
        $menuItems[] = ['label' => 'Create Ticket / Reports', 'url' => ['/user-ticket']];
        if(Yii::$app->mycomponent->isUserRole('admin-administrasi-support', Yii::$app->user->identity->level_user))
        {
            $menuItems[] = ['label' => 'Check Ticket / Reports', 'url' => ['/all-ticket']];
        }
        if(Yii::$app->mycomponent->isUserRole('admin', Yii::$app->user->identity->level_user))
        {
            $menuItems[] = '<li class="dropdown padding_null">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" >Administrator</a>
                          <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">Menu Administrator</li>
                            <li class="divider"></li>
                            <li><a href="'.Yii::$app->homeUrl.'user">Data User</a></li>
                          </ul>
                        </li>';
        }
        //
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; PT TRI SINAR PURNAMA <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>
<div id="content-all-alert"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
