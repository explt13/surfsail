<main class="page page_product">
    <div class="page__header">
        <div class="page__container container">
            <nav class="page__breadcrumbs breadcrumbs">
                <ul class="breadcrumbs__list">
                    <li class="breadcrumbs__item">
                        <a href="/" class="breadcrumbs__link">Main page</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <a href="./catalog.html" class="breadcrumbs__link">Surfboards</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <a href="./catalog.html" class="breadcrumbs__link">Surfboards</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <span class="breadcrumbs__current"><?= htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8');?></span>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <?php
    use app\views\helpers\CatalogHelper;
    if ($product): ?>
        <div class="product">
            <div class="product__container container">
                <div class="product__main main-product">
                    <div class="main-product__images images-product">
                        <div class="images-product__show-image show-image">
                            <div class="show-image__wrapper swiper-wrapper">
                                <div class="show-image__slide swiper-slide">
                                    <picture>
                                        <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" type="image/webp">
                                        <img src="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.jpg" data-zoom="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" alt="product-image">
                                    </picture>
                                </div>
                                <?php if ($gallery_images): ?>
                                    <?php foreach ($gallery_images as $image):?>
                                        <div class="show-image__slide swiper-slide">
                                            <picture>
                                                <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" type="image/webp">
                                                <img src="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.jpg" data-zoom="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" alt="product-image">
                                            </picture>
                                        </div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="images-product__thumbs thumbs-images swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide thumbs-images__slide">
                                    <picture>
                                        <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" type="image/webp">
                                        <img src="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.jpg" data-zoom="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" alt="product-image">
                                    </picture>
                                </div>
                                <?php if ($gallery_images): ?>
                                    <?php foreach($gallery_images as $image):?>
                                        <div class="swiper-slide thumbs-images__slide">
                                            <picture>
                                                <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" type="image/webp">
                                                <img src="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.jpg" data-zoom="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8')?>.webp" alt="product-image">
                                            </picture>
                                        </div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>
                            <div class="pagination-bullets"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                        <div class="images-product__zoom-pane zoom-pane" hidden>
                            <div class="zoom-pane__container"></div>
                        </div>
                    </div>
                    <div class="main-product__body body-product">
                        <div class="body-product__header header-product">
                            <div class="header-product__line">
                                <h1 class="header-product__title">AquaRide Shortboard</h1>
                                <?php if($product['new']): ?>
                                    <span class="header-product__new">new</span>
                                <?php endif;?>
                                <div class="header-product-card__actions product-card-actions">
                                    <span class="_icon-comp product-card-actions__comp"></span>
                                    <span class="_icon-fav product-card-actions__like"></span>
                                </div>
                            </div>
                            <div class="header-product__line header-product__line_secondary">
                                <div class="body-product__rating rating">
                                    <div class="rating__body">
                                        <div class="rating__active"></div>
                                        <div class="rating__items">
                                            <input type="radio" name="product__rating" value="1" class="rating__item">
                                            <input type="radio" name="product__rating" value="2" class="rating__item">
                                            <input type="radio" name="product__rating" value="3" class="rating__item">
                                            <input type="radio" name="product__rating" value="4" class="rating__item">
                                            <input type="radio" name="product__rating" value="5" class="rating__item">
                                        </div>
                                    </div>
                                    <div class="rating__value">4.6</div>
                                </div>
                                <?php if ($product['qty'] > 0): ?>
                                    <div class="body-product__available _true">In stock</div>
                                <?php else: ?>
                                    <div class="body-product__available">Out of stock</div>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="body-product__table table-product">
                            <?php if ($product_brand):?>
                                <div class="table-product__label">Brand:</div>
                                <a href="catalog?brand=<?= htmlspecialchars($product_brand['alias'], ENT_QUOTES, 'UTF-8');?>" class="table-product__value"><?= htmlspecialchars($product_brand['title'], ENT_QUOTES, 'UTF-8');?></a>
                            <?php endif;?>
                            <?PHP if ($product['length']): ?>
                                <div class="table-product__label">Length:</div>
                                <div class="table-product__value"><?= htmlspecialchars($product['length'], ENT_QUOTES, 'UTF-8');?></div>
                            <?php endif;?>
                            <?php if ($product['width']): ?>
                                <div class="table-product__label">Width:</div>
                                <div class="table-product__value"><?= htmlspecialchars($product['width'], ENT_QUOTES, 'UTF-8');?></div>
                            <?php endif;?>
                            <?php if ($product['thickness']): ?>
                                <div class="table-product__label">Thickness:</div>
                                <div class="table-product__value"><?= htmlspecialchars($product['thickness'], ENT_QUOTES, 'UTF-8');?></div>
                            <?php endif;?>
                            <?php if ($product['weight']): ?>
                                <div class="table-product__label">Weight:</div>
                                <div class="table-product__value"><?= htmlspecialchars($product['weight'], ENT_QUOTES, 'UTF-8');?> kg</div>
                            <?php endif;?>
                        </div>
                        <div class="body-product__options options-product">
                            <?php foreach ($modificators as $mod): ?>
                            <div class="options-product__label"><?= htmlspecialchars($mod['title'], ENT_QUOTES 'UTF-8');?></div>
                            <div class="options-product__value">
                                <select name="material" class="product">
                                    <option default selected>Select material</option>
                                    <?php foreach($mod['opts'] as $opt): ?>
                                     <option value="<?= htmlspecialchars($opt['value'], ENT_QUOTES, 'UTF-8');?>">
                                        <?= htmlspecialchars($opt['title'], ENT_QUOTES, 'UTF-8');?>
                                      </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <?php endforeach;?>
                            <div class="options-product__label">Color</div>
                            <div class="options-product__value">
                                <select name="color" class="product">
                                    <option disabled selected>Select color</option>
                                    <option value="blue">Blue</option>
                                    <option value="red">Red</option>
                                    <option value="yellow">Yellow</option>
                                    <option value="green">Green</option>
                                </select>
                            </div>
                            <div class="options-product__label">Deck Surface</div>
                            <div class="options-product__value">
                                <select name="front" class="product">
                                    <option disabled selected>Select surface</option>
                                    <option value="slick">Slick</option>
                                    <option value="matte">Matte</option>
                                    <option value="glossy">Glossy</option>
                                </select>
                            </div>
                            <div class="options-product__label">Reaction System</div>
                            <div class="options-product__value">
                                <select name="reaction" class="product">
                                    <option disabled selected>Select system</option>
                                    <option value="fcs">FCS</option>
                                    <option value="future">Future</option>
                                    <option value="glasson">Glass-on</option>
                                </select>
                            </div>
                        </div>
                        <div class="body-product__actions actions-product">
                            <div class="actions-product__price product-price product-price_big-card">
                                <?php
                                if ($product['discount_price']): ?>
                                    <span class="product-price__current">
                                        <span class="product-price__symbol"><?= htmlspecialchars($currency['symbol'], ENT_QUOTES, 'UTF-8');?></span>
                                        <span class="product-price__value"> <?= htmlspecialchars(number_format($product['discount_price'] * $currency['value'], 2, ',', ' '), ENT_QUOTES, 'UTF-8');?></span>
                                    </span>
                                    <span class="product-price__old">
                                        <span class="product-price__symbol"><?= htmlspecialchars($currency['symbol'], ENT_QUOTES, 'UTF-8');?></span>
                                        <span class="product-price__value"> <?= htmlspecialchars(number_format($product['price'] * $currency['value'], 2, ',', ' '), ENT_QUOTES, 'UTF-8');?></span>
                                    </span>
                                <?php else: ?>
                                    <span class="product-price__current">
                                        <span class="product-price__symbol"><?= htmlspecialchars($currency['symbol'], ENT_QUOTES, 'UTF-8');?></span>
                                        <span class="product-price__value"><?= htmlspecialchars(number_format($product['price'] * $currency['value'], 2, ',', ' '), ENT_QUOTES, 'UTF-8');?></span>
                                    </span>
                                <?php endif;?>
                            </div>
                            <div class="actions-product__row">
                                <div class="actions-product__quantity quantity">
                                    <button class="quantity__button quantity__button_minus" type="button"></button>
                                    <div class="quantity__input">
                                        <input type="text" name="form[]" value="1" autocomplete="off">
                                    </div>
                                    <button class="quantity__button quantity__button_plus" type="button"></button>
                                </div>
                                <div class="actions-product__buttons">
                                    <a href="/cart/add?id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8')?>" class="actions-product__cart button"><span class="_icon-cart"></span>To cart</a>
                                    <a href="" class="actions-product__buy button button_black">Buy now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($product['description'] || $product['additional_information'] || $product_reviews):?>
                    <section class="product__information information-product">
                        <div class="information-product__body _tabs">
                            <div class="information-product__tabs">
                                <?php if ($product['description']):?>
                                    <div class="information-product__tab _tabs-item _active">Description</div>
                                <?php endif;?>
                                <?php if ($product['additional_information']): ?>
                                    <div class="information-product__tab _tabs-item">Additional Information</div>
                                <?php endif;?>
                                <?php if ($reviews): ?>
                                    <div class="information-product__tab _tabs-item">Reviews</div>
                                <?php endif;?>
                            </div>
                            <div class="information-product__blocks">
                                <?php if ($product['description']):?>
                                    <div class="information-product__block product-block-description _tabs-block _active">
                                        <?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8');?>
                                    </div>
                                <?php endif;?>
                                <?php if ($product['additional_information']): ?>
                                    <div class="information-product__block product-block-additional-info _tabs-block">
                                        <ul class="product-block-additional-info__list">
                                            <?php 
                                            $addit_info = explode(';', trim($product['additional_information'], ';'));
                                            foreach ($addit_info as $a_info): ?>
                                                <li class="product-block-additional-info__item"><?= htmlspecialchars($a_info, ENT_QUOTES, 'UTF-8');?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <?php if ($reviews): ?>
                                    <div class="information-product__block product-block-review _tabs-block">
                                        <ul class="product-block-review__list">
                                            <?php foreach($reviews as $review): ?>
                                                <li class="product-block-review__item">
                                                    <div class="product-block-review__header">
                                                        <picture>
                                                            <source srcset="img/user/<?= htmlspecialchars($review['user']['image'], ENT_QUOTES, 'UTF-8');?>.webp" type="image/webp">
                                                            <img class="product-block-review__user-image" src="img/user/<?= htmlspecialchars($review['user']['image'], ENT_QUOTES, 'UTF-8');?>.jpg" />
                                                        </picture>
                                                        <div class="product-block-review__user-name"><?= htmlspecialchars($review['user']['first_name'], ENT_QUOTES, 'UTF-8')?></div>
                                                        <div class="product-block-review__user-rating">
                                                            <div class="rating">
                                                                <div class="rating__body">
                                                                    <div class="rating__active"></div>
                                                                    <div class="rating__items">
                                                                        <input type="radio" name="product__rating" value="1" class="rating__item">
                                                                        <input type="radio" name="product__rating" value="2" class="rating__item">
                                                                        <input type="radio" name="product__rating" value="3" class="rating__item">
                                                                        <input type="radio" name="product__rating" value="4" class="rating__item">
                                                                        <input type="radio" name="product__rating" value="5" class="rating__item">
                                                                    </div>
                                                                </div>
                                                                <div class="rating__value" hidden><?= htmlspecialchars($review['rating'], ENT_QUOTES, 'UTF-8')?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-block-review__body"><?= htmlspecialchars($review['text'], ENT_QUOTES, 'UTF-8');?></div>
                                                </li>
                                            <?php endforeach;?>
                                        </ul>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </section>
                <?php endif;?>
                <?php if ($related_products) {
                    CatalogHelper::renderCatalog($related_products, 'You might like', 'related', false);
                }
                ?>
            </div>
        </div>
    <?php endif;?>
</main>