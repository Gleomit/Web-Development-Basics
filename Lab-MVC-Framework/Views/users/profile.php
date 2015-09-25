<h1>Hello, <?= $model->user->getUsername(); ?></h1>
<h3>
    Resources:
    <p>Gold: <?= $model->user->getGold(); ?></p>
    <p>Food: <?= $model->user->getFood(); ?></p>
</h3>
<form method="post">
    <div>
        <input type="text" name="username" value="<?= $model->user->getUsername(); ?>">
        <input type="password" name="password">
        <input type="password" name="confirm">
        <input type="submit" value="Edit" name="edit">
    </div>
</form>
<?php if($model->error): ?>
    <h2><?= $model->error; ?></h2>
<?php elseif($model->success): ?>
    <h2><?= $model->success; ?></h2>
<?php endif; ?>
Go to:
<div class="menu">
    <a href="logout">Logout</a>
    <a href="buildings">Buildings</a>
</div>