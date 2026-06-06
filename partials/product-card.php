<?php

/**
 * PARTIAL: Produktová karta
 *
 * Očekává proměnnou:
 *   $product (ProductDTO) – produkt k zobrazení
 *
 * Pokud má produkt volitelné varianty (velikost, barva...),
 * zobrazí se odkaz na detail místo tlačítka "Přidat do košíku".
 */

?>
<article class="product-card">
    <a href="produkt.php?slug=<?= htmlspecialchars($product->slug) ?>">
        <img
            class="product-card__image"
            src="<?= htmlspecialchars($product->image) ?>"
            alt="<?= htmlspecialchars($product->name) ?>"
        >
    </a>

    <div class="product-card__body">
        <span class="product-card__category">
            <?= htmlspecialchars($product->categoryName ?? '') ?>
        </span>

        <h2 class="product-card__name">
            <a href="produkt.php?slug=<?= htmlspecialchars($product->slug) ?>">
                <?= htmlspecialchars($product->name) ?>
            </a>
        </h2>

        <div class="product-card__price">
            <span class="product-card__price-current">
                <?= number_format($product->price, 0, ',', ' ') ?> Kč
            </span>

            <?php if ($product->hasDiscount()): ?>
                <span class="product-card__price-original">
                    <?= number_format($product->originalPrice, 0, ',', ' ') ?> Kč
                </span>
                <span class="product-card__discount">
                    -<?= $product->getDiscountPercent() ?> %
                </span>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($product->hasVariants): ?>
        <a href="produkt.php?slug=<?= htmlspecialchars($product->slug) ?>" class="product-card__btn product-card__btn--variant">
            Vybrat variantu
        </a>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="product_id" value="<?= $product->id ?>">
            <button type="submit" name="add_to_cart" class="product-card__btn">
                Přidat do košíku
            </button>
        </form>
    <?php endif; ?>
</article>
