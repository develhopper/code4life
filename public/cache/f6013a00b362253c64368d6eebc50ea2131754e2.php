<h1>Hello</h1>

<?php foreach($posts as $post): ?>
<h2><?= $post->title ?></h2>
<pre>
    <?= $post->body ?>
</pre>
<?php endforeach; ?>
