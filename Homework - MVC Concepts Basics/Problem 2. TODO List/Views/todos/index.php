<ul>
    <?php foreach($model->todos as $todo): ?>
    <li><?= $todo['todo_item']; ?> <a href="<?= \Framework\Helpers\RouteService::getUri('todos', 'delete', [$todo['id']]); ?>">Delete</a></li>
    <?php endforeach; ?>
</ul>
<a href="<?= \Framework\Helpers\RouteService::getUri('todos', 'add'); ?>">Add todo</a>
<a href="<?= \Framework\Helpers\RouteService::getUri('users', 'logout'); ?>">Logout</a>