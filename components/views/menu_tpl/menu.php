<div class="category-products">
<li value="<?= $category['id']?>" src="<?= $category['image']?>" >
    <a href="#"> <?= $category['product'] ?></a>
   <?php if(isset($category['childs'])):?>
    <a class = "badge pull-right"><i class="fa fa-plus"></i></a> 
    <?php endif;?>
   <?php if(isset($category['childs'])):?>
    <ul>
        <a href="#"><?= getMenuHtml($category['childs'])?> </a>
    </ul>
    <?php endif ?>
</li>
</div>



<style>
    .category-products{
        font-size: large;
        list-style-type: none;
   }

    a{
    cursor: pointer;
    text-decoration:none;
    color:black;
    font-family: "Times New Roman", Times, serif;
    font-weight:bold;
   }
   .dcjq-icon{
    display:none;
   }
</style>
 