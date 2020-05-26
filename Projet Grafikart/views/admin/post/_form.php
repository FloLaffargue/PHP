<form method="POST" enctype="multipart/form-data">

        <?= $form->input('name', 'Titre') ?>
        <?= $form->input('slug', 'URL') ?>
        <?= $form->select('categories_ids', 'Catégories', $categories) ?>
        <?= $form->textarea('content', 'Contenu') ?>

        <?= $form->file('image', 'Image à la une') ?>
        <?php if($post->getImage()): ?>
            <div>
                <img src="<?=$post->getImageURL('small')?>" style="max-width:250px">
            </div>
        <?php endif ?>

    <button type="submit" class="btn btn-primary"><?=$button?></button>

</form>
