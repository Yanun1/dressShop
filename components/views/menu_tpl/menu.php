
<div class="category-products">
    <li value="<?= $category['id']?>" src="<?= $category['image']?>" >
        <a href="#" class="item"> <?= $category['product'] ?></a>
        <a class = "badge pull-right"><input type="checkbox" name="chosed[]"></a>
       <?php if(isset($category['childs'])):?>
        <ul>
            <a href="#"><?= getMenuHtml($category['childs'])?> </a>
        </ul>
        <?php endif ?>
    </li>
</div>



<style>

    .category-products .badge {
        position: relative;
        top: 5px;
        right: 2px;
    }

    .catalog li {
        border: 1px solid grey;
        border-radius: 5px;
        margin-bottom: 5px;
        margin-right: 5px;
        margin-top: 5px;
        background-color: #f0f0f0;
    }

    .item {
        padding: 8px;
    }

    .category-products{
        font-size: large;
        list-style-type: none;
   }

    .category-products a{
        cursor: pointer;
        text-decoration:none;
        color:black;
        font-family: "Times New Roman", Times, serif;
        font-weight:bold;
        display:inline-block;
        width: 80%;
   }

    .category-products .badge {
        display:inline-block;
        width: 20%;
    }

   .dcjq-icon{
        display:none;
   }
</style>
 