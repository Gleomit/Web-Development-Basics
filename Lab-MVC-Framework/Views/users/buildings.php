<?= $model->error ? $model->error : '' ?>

<h1>Buildings</h1>

<h3>
    Resources:
    <p>Gold: <?= $model->user->getGold(); ?></p>
    <p>Food: <?= $model->user->getFood(); ?></p>
</h3>

<table border="1">
    <tr>
        <td>Building name</td>
        <td>Level</td>
        <td>Gold</td>
        <td>Food</td>
    </tr>

    <?php foreach($model->buildings as $building): ?>

        <tr>
            <td><?= $building['name']; ?></td>
            <td><?= $building['level']; ?></td>
            <td><?= $building['gold']; ?></td>
            <td><?= $building['food']; ?></td>
            <td><a href="<? \SoftUni\Helpers\RouteService::getUri('buildings', 'evolve', [$building['id']]); ?>">Evolve</a></td>
        </tr>
    <?php endforeach; ?>
</table>