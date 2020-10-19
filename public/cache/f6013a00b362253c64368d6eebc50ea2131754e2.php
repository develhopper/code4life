
<?php ob_start(); ?>

<?php foreach($posts as $post): ?>
    <div class="card post rtl bg-grey">
        <div class="card-title">
            <h2><?= $post->title ?></h2>
        </div>
        <div class="card-body">
            <?= $post->body ?>
        </div>
        <a href="<?= BASEURL.'/p/'.$post->slug ?>" class="btn btn-primary mr-auto">بیشتر</a>
    </div>
<?php endforeach; ?>

<?php $content_Y29u=ob_get_contents();ob_end_clean(); ?>
<?php include_once '/srv/http/code4life/public/cache/fc6276edb99abafcc5747aea8b1d9f0dff47179b.php'; ?>