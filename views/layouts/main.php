<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerCssFile('@web/css/tailwind.css', ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerJsFile('https://cdn.tailwindcss.com', ['position' => \yii\web\View::POS_HEAD]);

$currentController = Yii::$app->controller->id;
$currentAction = Yii::$app->controller->action->id;

function isActive($controllerId, $currentController)
{
    return $controllerId === $currentController ? 'active text-warning fw-bold' : 'text-light';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* ✅ Modal tidak ikut terdorong sidebar */
        .modal {
            z-index: 2000;
            /* lebih tinggi dari sidebar */
        }

        .modal-backdrop {
            z-index: 1999;
        }
    </style>
</head>

<body class="h-100 bg-gray-100">
    <?php $this->beginBody() ?>

    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        <aside id="sidebar-menu" class="bg-dark text-white p-4 position-fixed top-0 start-0 min-vh-100" style="width: 250px; z-index: 1030;">
            <h4 class="mb-5 text-uppercase fw-bold border-bottom border-secondary pb-3">
                <i class="bi bi-mortarboard-fill me-2 text-warning"></i>
                <?= Html::encode(Yii::$app->name) ?>
            </h4>
            <ul class="nav flex-column">
                <?php if (Yii::$app->user->isGuest): ?>
                    <li class="nav-item">
                        <a class="nav-link p-3 rounded transition-all <?= isActive('site', $currentController) ?>"
                            href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </a>
                    </li>
                <?php else: ?>
                    <?php if (in_array(Yii::$app->user->identity->type, [1, 2])): ?>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('pengguna', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/pengguna/index']) ?>">
                                <i class="bi bi-people me-2"></i> Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('laboratorium', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/laboratorium/index']) ?>">
                                <i class="bi bi-building me-2"></i> Laboratorium
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('matakuliah', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/matakuliah/index']) ?>">
                                <i class="bi bi-journal me-2"></i> Mata Kuliah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('jadwal', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/jadwal/index']) ?>">
                                <i class="bi bi-calendar-event me-2"></i> Jadwal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('soal', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/soal/index']) ?>">
                                <i class="bi bi-list-task me-2"></i> Soal Ujian
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('pengerjaan', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/pengerjaan/index']) ?>">
                                <i class="bi bi-pencil-square me-2"></i> Penilaian
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('mahasiswa', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/mahasiswa/index']) ?>">
                                <i class="bi bi-person-badge me-2"></i> Data Mahasiswa
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item mt-5 pt-3 border-top border-secondary">
                        <?= Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                '<i class="bi bi-box-arrow-right me-2"></i> Logout (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'btn btn-link nav-link text-danger p-0 fw-bold']
                            )
                            . Html::endForm()
                        ?>
                    </li>
                <?php endif; ?>
            </ul>
        </aside>

        <!-- Konten utama -->
        <div class="flex-grow-1 d-flex flex-column">
            <div style="margin-left: 250px;">
                <main class="flex-grow-1 p-4">
                    <?php if (!empty($this->params['breadcrumbs'])): ?>
                        <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
                    <?php endif ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </main>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>