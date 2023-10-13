<li>
    <a href="#"> <?= $category['product'] ?></a>
   <?php if(isset($category['childs'])):?>
    <span class = "badge pull-right"><i class="fa  fa-plus"></i></span>
    <?php endif;?>
   <?php if(isset($category['childs'])):?>
    <ul>
        <a href="#"><?= getMenuHtml($category['childs'])?> </a>
    </ul>
    <?php endif;?>
</li>

 