<li>
   <?= $category['product'] ?>
   <?php if(isset($category['childs'])):?>
    <span class = "badge pull-right"><i class="fa  fa-plus"></i></span>
    <?php endif;?>
   <?php if(isset($category['childs'])):?>
    <ul>
        <?= getMenuHtml($category['childs'])?> 
    </ul>
    <?php endif;?>
</li>

 