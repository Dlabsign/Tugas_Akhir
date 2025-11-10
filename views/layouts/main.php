<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;

\yii\bootstrap5\BootstrapAsset::register($this);
\yii\bootstrap5\BootstrapPluginAsset::register($this);

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['depends' => [\yii\web\JqueryAsset::class]]);



$currentController = Yii::$app->controller->id;
$currentAction = Yii::$app->controller->action->id;
$user = Yii::$app->user->identity;

function isActive($controllerId, $currentController)
{
    return $controllerId === $currentController ? 'active text-warning fw-bold' : 'text-light';
}

if ($user) {
    switch ($user->type) {
        case 1:
            $dashboardUrl = Url::to(['/dashboard/index']);
            break;
        case 2:
            $dashboardUrl = Url::to(['/dashboard/index']);
            break;
        case 3:
            $dashboardUrl = Url::to(['/dashboard/index']);
            break;
        default:
            $dashboardUrl = Url::to(['/dashboard/index']);
            break;
    }
} else {
    $dashboardUrl = Url::to(['/site/login']);
}
?>

<?php $this->beginPage() ?>



<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $this->registerCssFile('@web/css/output.css', ['depends' => [\yii\web\YiiAsset::class]]);
    $this->head();
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <script src="https://cdn.tailwindcss.com"></script>


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
        <aside id="sidebar-menu" class="bg-dark text-white p-4 d-flex flex-column justify-between position-fixed top-0 start-0 min-vh-100" style="width: 250px; z-index: 1030;">
            <div>
                <h4 class=" text-uppercase fw-bold border-bottom border-secondary pb-3">
                    <i class="bi bi-mortarboard-fill me-2 text-warning"></i>
                    Praktikum
                </h4>
            </div>
            <div class="flex-grow-1">
                <ul class="nav flex-column">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <li class="nav-item">
                            <a class="nav-link p-3 rounded transition-all <?= isActive('site', $currentController) ?>"
                                href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </a>
                        </li>
                    <?php else: ?>
                        <?php if (in_array(Yii::$app->user->identity->type, [1])): ?>
                            <li class="nav-item">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('dashboard', $currentController) ?>"
                                    href="<?= Html::encode($dashboardUrl) ?>">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
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
                                <a class="nav-link p-3 rounded transition-all <?= isActive('mahasiswa', $currentController) ?>"
                                    href="<?= Yii::$app->urlManager->createUrl(['/mahasiswa/index']) ?>">
                                    <i class="bi bi-person-badge me-2"></i> Data Mahasiswa
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('pengerjaan', $currentController) ?>"
                                    href="#">
                                    <i class="bi bi-pencil-square me-2"></i> Penilaian
                                </a>
                                <ul class="dropdown-menu show" style="position: static; float: none;">
                                    <li>
                                        <?= Html::a(
                                            'Penilaian Akhir',
                                            Url::to(['pengerjaan/penilaian-akhir']),
                                            ['class' => 'dropdown-item']
                                        ) ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            'Penilaian Soal',
                                            Url::to(['pengerjaan/penilaian-soal']),
                                            ['class' => 'dropdown-item']
                                        ) ?>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if (in_array(Yii::$app->user->identity->type, [2])): ?>
                            <li class="nav-item">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('dashboard', $currentController) ?>"
                                    href="<?= Html::encode($dashboardUrl) ?>">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
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
                                <a class="nav-link p-3 rounded transition-all <?= isActive('mahasiswa', $currentController) ?>"
                                    href="<?= Yii::$app->urlManager->createUrl(['/mahasiswa/index']) ?>">
                                    <i class="bi bi-person-badge me-2"></i> Data Mahasiswa
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('pengerjaan', $currentController) ?>"
                                    href="#">
                                    <i class="bi bi-pencil-square me-2"></i> Penilaian
                                </a>
                                <ul class="dropdown-menu show" style="position: static; float: none;">
                                    <li>
                                        <?= Html::a(
                                            'Penilaian Akhir',
                                            Url::to(['pengerjaan/penilaian-akhir']),
                                            ['class' => 'dropdown-item']
                                        ) ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            'Penilaian Soal',
                                            Url::to(['pengerjaan/penilaian-soal']),
                                            ['class' => 'dropdown-item']
                                        ) ?>
                                    </li>
                                </ul>
                            </li>

                        <?php endif; ?>
                        <?php if (in_array(Yii::$app->user->identity->type, [3])): ?>
                            <li class="nav-item">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('dashboard', $currentController) ?>"
                                    href="<?= Html::encode($dashboardUrl) ?>">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('soal', $currentController) ?>"
                                    href="<?= Yii::$app->urlManager->createUrl(['/soal/index']) ?>">
                                    <i class="bi bi-list-task me-2"></i> Soal Ujian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('mahasiswa', $currentController) ?>"
                                    href="<?= Yii::$app->urlManager->createUrl(['/mahasiswa/index']) ?>">
                                    <i class="bi bi-person-badge me-2"></i> Data Mahasiswa
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link p-3 rounded transition-all <?= isActive('pengerjaan', $currentController) ?>"
                                    href="#">
                                    <i class="bi bi-pencil-square me-2"></i> Penilaian
                                </a>
                                <ul class="" style="position: static; float: none;">
                                    <li class="flex justify-center items-center py-3 <?= Yii::$app->controller->action->id === 'penilaian-akhir' ? 'bg-gray-700 text-slate-100 rounded-md' : 'text-slate-50' ?>">
                                        <i class="bi bi-chevron-right ms-1 mr-2"></i>
                                        <?= Html::a(
                                            'Penilaian Akhir',
                                            Url::to(['pengerjaan/penilaian-akhir']),
                                            [
                                                'class' => 'dropdown-item ' . (Yii::$app->controller->action->id === 'penilaian-akhir' ? 'text-slate-100 font-semibold' : 'text-slate-50')
                                            ]
                                        ) ?>
                                    </li>
                                    <li class="flex justify-center items-center py-3 <?= Yii::$app->controller->action->id === 'penilaian-soal' ? 'bg-gray-700 text-slate-100 rounded-md' : 'text-slate-50' ?>">
                                        <i class="bi bi-chevron-right ms-1 mr-2"></i>
                                        <?= Html::a(
                                            'Penilaian Soal',
                                            Url::to(['pengerjaan/penilaian-soal']),
                                            [
                                                'class' => 'dropdown-item ' . (Yii::$app->controller->action->id === 'penilaian-soal' ? 'text-slate-100 font-semibold' : 'text-slate-50')
                                            ]
                                        ) ?>
                                    </li>
                                    <li class="flex justify-center items-center py-3 <?= Yii::$app->controller->action->id === 'penilaian-soal' ? 'bg-gray-700 text-slate-100 rounded-md' : 'text-slate-50' ?>">
                                        <i class="bi bi-chevron-right ms-1 mr-2"></i>
                                        <?= Html::a(
                                            'Upload Gambar',
                                            Url::to(['pengerjaan/penilaian-soal']),
                                            [
                                                'class' => 'dropdown-item ' . (Yii::$app->controller->action->id === 'penilaian-soal' ? 'text-slate-100 font-semibold' : 'text-slate-50')
                                            ]
                                        ) ?>
                                    </li>
                                </ul>
                            </li>

                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- FOOTER -->
            <div class="mt-auto border-top border-secondary pt-3">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            '<i class="bi bi-box-arrow-right me-2"></i> Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link nav-link text-danger p-0 fw-bold']
                        )
                        . Html::endForm()
                    ?>
                <?php endif; ?>
            </div>


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