<li value="<?= $category['id']?>" src="<?= $category['image']?>">
    <a href="#"> <?= $category['product'] ?></a>
   <?php if(isset($category['childs'])):?>
    <a class = "badge pull-right dcjq-parent" style="background-color: black"><i class="fa fa-plus"></i></a>
    <?php endif;?>
   <?php if(isset($category['childs'])):?>
    <ul>
        <a href="#"><?= getMenuHtml($category['childs'])?> </a>
    </ul>
    <?php endif ?>
</li>

 