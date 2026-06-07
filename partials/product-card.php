<?php
declare(strict_types=1);
/** @var ProductDTO $product */
$img = $product->getMainImage();
$hasSale = $product->salePrice !== null && $product->salePrice > 0;
?>
<div class="product-card">
    <?php if ($product->badge !== null && $product->badge !== ''): ?>
        <span class="product-badge"><?= e($product->badge) ?></span>
    <?php endif; ?>
    <div class="product-image">
        <a href="/produkt.php?slug=<?= e($product->slug) ?>">
            <?php if ($img !== null): ?>
                <img src="<?= e($img) ?>" alt="<?= e($product->name) ?>">
            <?php else: ?>
                <div class="main-image-placeholder" style="height:220px;"></div>
            <?php endif; ?>
        </a>
    </div>
    <div class="product-info">
        <h3><a href="/produkt.php?slug=<?= e($product->slug) ?>" style="color:inherit;"><?= e($product->name) ?></a></h3>
        <?php if ($product->shortDescription !== ''): ?>
            <p class="product-description"><?= e($product->shortDescription) ?></p>
        <?php endif; ?>
        <p class="price">
            <?php if ($hasSale): ?>
                <span style="text-decoration:line-through;color:#9a9a9a;font-size:0.9em;margin-right:8px;"><?= e(price($product->price)) ?></span>
                <?= e(price($product->getEffectivePrice())) ?>
            <?php else: ?>
                <?= e(price($product->price)) ?>
            <?php endif; ?>
        </p>
        <?php if ($product->hasVariants()): ?>
            <a href="/produkt.php?slug=<?= e($product->slug) ?>" class="btn-secondary">Vybrat variantu</a>
        <?php else: ?>
            <form method="post" action="/kosik.php" style="display:inline;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= (int)$product->id ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn-secondary">Do košíku</button>
            </form>
            <a href="/produkt.php?slug=<?= e($product->slug) ?>" class="btn-secondary" style="margin-left:6px;">Detail</a>
        <?php endif; ?>
    </div>
</div>
