<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

$slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
if ($slug === '') redirect('/index.php');

$productRepo = new ProductRepository();
$product = $productRepo->getBySlug($slug);
if ($product === null) {
    header('HTTP/1.0 404 Not Found');
    require __DIR__ . '/404.php';
    exit;
}

$pageTitle = e($product->name) . ' — Boston Bruins Fan Shop';
$pageDescription = $product->shortDescription;
require __DIR__ . '/partials/header.php';

$hasSale = $product->salePrice !== null && $product->salePrice > 0;
?>

<section class="product-detail">
    <div class="container">
        <div class="breadcrumb">
            <a href="/index.php">Domů</a> /
            <a href="/kategorie.php">Kategorie</a> /
            <a href="/produkty.php?slug=<?= e($product->categorySlug) ?>"><?= e($product->categoryName) ?></a> /
            <?= e($product->name) ?>
        </div>

        <div class="product-detail-container">
            <div class="product-gallery">
                <div class="main-image">
                    <?php if ($product->getMainImage() !== null): ?>
                        <img src="<?= e($product->getMainImage()) ?>" alt="<?= e($product->name) ?>">
                    <?php else: ?>
                        <div class="main-image-placeholder"></div>
                    <?php endif; ?>
                </div>
                <?php if (count($product->images) > 1): ?>
                    <div class="thumbnail-gallery">
                        <?php foreach ($product->images as $img): ?>
                            <div class="thumbnail"><img src="<?= e($img->path) ?>" alt="<?= e($img->alt) ?>"></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-detail-info">
                <h1><?= e($product->name) ?></h1>

                <p class="price-detail">
                    <?php if ($hasSale): ?>
                        <span style="text-decoration:line-through;color:#9a9a9a;font-size:0.6em;margin-right:12px;"><?= e(price($product->price)) ?></span>
                        <?= e(price($product->getEffectivePrice())) ?>
                    <?php else: ?>
                        <?= e(price($product->price)) ?>
                    <?php endif; ?>
                </p>

                <div class="product-description-detail">
                    <?php foreach (explode("\n", $product->description) as $para): ?>
                        <p><?= e($para) ?></p>
                    <?php endforeach; ?>
                </div>

                <form method="post" action="/kosik.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= (int)$product->id ?>">

                    <?php if ($product->hasVariants()): ?>
                        <?php
                        // seskupit varianty podle name
                        $groups = [];
                        foreach ($product->variants as $v) {
                            $groups[$v->name][] = $v;
                        }
                        ?>
                        <div class="product-parameters">
                            <?php foreach ($groups as $name => $list): ?>
                                <div class="parameter-group">
                                    <label for="variant"><?= e($name) ?>:</label>
                                    <select name="variant_id" id="variant" required>
                                        <?php foreach ($list as $v): ?>
                                            <option value="<?= (int)$v->id ?>"><?= e($v->value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php break; // podporujeme zatím jednu skupinu variant ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="parameter-group" style="margin:16px 0;">
                        <label for="quantity">Množství:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="20" style="width:80px;padding:6px;">
                    </div>

                    <button type="submit" class="btn-primary">Přidat do košíku</button>
                </form>

                <?php if ($product->parameters !== []): ?>
                    <h3 style="margin-top:32px;">Parametry</h3>
                    <table style="width:100%;margin-top:12px;border-collapse:collapse;">
                        <?php foreach ($product->parameters as $par): ?>
                            <tr>
                                <td style="padding:8px;border-bottom:1px solid #e5e5e5;color:#4a4a4a;"><?= e($par->name) ?></td>
                                <td style="padding:8px;border-bottom:1px solid #e5e5e5;font-weight:600;"><?= e($par->value) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
