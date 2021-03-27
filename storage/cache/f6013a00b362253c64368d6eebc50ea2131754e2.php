
<?php ob_start(); ?>
  <h1 class="rtl">کد برای زندگی | <?= $h1 ?></h1>
<?php foreach($posts as $post): ?>
    <a href="/p/<?= $post->slug ?>" class="article rtl">
      <h2 class="article-title"><?= $post->title ?></h2>
      <div class="article-body"><?= $post->description ?></div>
      <div class="col ltr">
        <small class="mr-auto fdigit"><?= $post->jdate() ?></small>
      </div>
    </a>
<?php endforeach; ?>
    <div class="pagination">

    </div>
<?php $content_9a03=ob_get_contents();ob_end_clean(); ?>

<?php ob_start(); ?>
  <script type="text/javascript">
    <?=  "make_pagination('.pagination',$pagination[current],$pagination[total],'$pagination[link]');" ?>
  </script>
<?php $js_3298=ob_get_contents();ob_end_clean(); ?>

<?php include_once '/home/alireza/work/www/code4life/storage/cache/fc6276edb99abafcc5747aea8b1d9f0dff47179b.php'; ?>