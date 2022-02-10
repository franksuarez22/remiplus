<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use mdm\admin\components\MenuHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php

if (Yii::$app->user->isGuest) {
    $menuItems = [/*['label' => 'Contáctanos', 'url' => ['/site/contact']],*/
        ['label' => 'Iniciar sesión', 'url' => ['/admin/user/login']],
        ['label' => 'Ingresar incidencia', 'url' => ['/site/logindenunciante']],
        /*['label' => 'Registrarse', 'url' => ['/site/signup']]*/];
} else {
    $menu = [['label' => 'Inicio', 'url' => ['/site/index']],
        ['label' => 'Contáctanos', 'url' => ['/site/contact']]];
    $logout = [[
        'label' => 'Salir (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ]];
    if (!empty(MenuHelper::getAssignedMenu(Yii::$app->user->id,null,null,true))) {
        $menuItems = array_merge($menu, MenuHelper::getAssignedMenu(Yii::$app->user->id,null,null,true),$logout);
    }else{
        $menuItems = array_merge($menu,$logout);
    }
}

    NavBar::begin([
        'brandLabel' => 'Inicio',//Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; MINCYT <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
