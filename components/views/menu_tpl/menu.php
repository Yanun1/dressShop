
<div class="category-products">
    <li value="<?= $category['id']?>" src="<?= $category['image']?>" >
        <a href="#" class="item"> <?= $category['product'] ?></a>
        <div class = "badge pull-right"><input type="radio" name="chosed" class="checked-product"></div>
       <?php if(isset($category['childs'])):?>
        <ul class="child-ul">
            <a href="#"><?= getMenuHtml($category['childs'])?> </a>
        </ul>
        <?php endif ?>
    </li>
</div>