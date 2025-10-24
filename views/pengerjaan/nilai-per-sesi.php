<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<?php foreach ($data as $d): ?>
    <h3>Sesi <?= Html::encode($d['sesi']->nama_sesi) ?></h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Skor</th>
        </tr>
        <?php foreach ($d['pengerjaan'] as $p): ?>
            <tr>
                <td><?= Html::encode($p->mahasiswa->nim) ?></td>
                <td><?= Html::encode($p->mahasiswa->nama) ?></td>
                <td><?= Html::encode($p->skor ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>