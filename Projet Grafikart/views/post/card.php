<?php 

    $categories = array_map(function($category) use ($router) {

        $category_url = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);
        $nameCategory = esc($category->getName());

        return "<a href='$category_url'>$nameCategory</a>";

    }, $post->getCategories());

?>

<div class="card">
    <?php if($post->getImage()): ?>
        <img src="<?= $post->getImageURL('small')?>" class="card-img-top" alt="">
    <?php endif ?>
    <div class="card-body">
        <h5 class="card-title">
            <?= htmlentities($post->getName())?>
        </h5>
        <p class="text-muted">
            <?= $post->getCreatedAt()->format('d F Y ')?>
        </p>

        <?php if(!empty($post->getCategories())): ?>

            <?= implode(',', $categories); ?>
        <?php endif ?>

        <p><?= $post->getExcerpt()?></p>
        <p>
            <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]) ?>" class="btn btn-primary">
            Voir plus
            </a>
        </p>
    </div>
</div>