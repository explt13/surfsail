<main class="page page_home">
    <section class="main-screen">
        <div class="main-screen__wrapper">
            <div class="main-screen__container container">
                <div class="main-screen__body">
                    <div class="swiper main-screen__slider slider-main">
                        <div class="swiper-wrapper">
                            <div class="slider-main__slide swiper-slide slide-main-block slide-main-block-first">
                                <div class="slide-main-block__content">
                                    <h2 class="slide-main-block__title">Online Store</h2>
                                    <div class="slide-main-block__sub-title">for Surfing Gear</div>
                                    <div class="slide-main-block__text">
                                        Welcome to the official SurfSail website! Our store offers the widest selection
                                        of surfboards from renowned surfboard manufacturers. We are the official suppliers.
                                    </div>
                                    <a href="" class="slide-main-block__button button">More</a>
                                </div>
                            </div>
                            <div class="slider-main__slide swiper-slide slide-main-block">
                                <div class="slide-main-block__content">
                                    <h2 class="slide-main-block__title">The best online surf shop</h2>
                                    <div class="slide-main-block__sub-title">in the US</div>
                                    <div class="slide-main-block__text">
                                        Discover the Ultimate Destination for Surfers! Explore our premier online surf shop,
                                        offering top-quality boards, expert guidance, and unbeatable service. Dive in and ride the waves of excellence!
                                    </div>
                                    <a href="" class="slide-main-block__button button">More</a>
                                </div>
                            </div>
                            <div class="slider-main__slide swiper-slide slide-main-block">
                                <div class="slide-main-block__content">
                                    <h2 class="slide-main-block__title">Shipping</h2>
                                    <div class="slide-main-block__sub-title">Seamless Surfboard Shipping Across the US</div>
                                    <div class="slide-main-block__text">
                                        Experience hassle-free shipping with SurfSail! We ensure your surfboard reaches you safely and swiftly, no matter where you are in the US.
                                        Trust our reliable delivery service to bring the waves to your doorstep, so you can focus on catching them.
                                    </div>
                                    <a href="" class="slide-main-block__button button">More</a>
                                </div>
                            </div>
                        </div>
                        <div class="pagination">
                            <div class="pagination-bullets"></div>
                            <div class="pagination-fraction">
                                <span class="pagination-fraction__active"></span>
                                <span class="pagination-fraction__total"></span>
                            </div>
                        </div>

                    </div>
                    <div class="main-screen__media media-main-block">
                        <div class="media-main-block__image">
                            <picture>
                                <source srcset="img/home/surfboard01.webp" type="image/webp">
                                <source srcset="img/home/surfboard01.jpg" type="image/jpeg">
                                <img class="media-main-block__img media-main-block__img_1" src="img/home/surfboard01.png" alt="surfboard-image">
                            </picture>
                            <picture>
                                <source srcset="img/home/surfboard02.webp" type="image/webp">
                                <source srcset="img/home/surfboard02.jpg" type="image/jpeg">
                                <img class="media-main-block__img media-main-block__img_2" src="img/home/surfboard02.png" alt="surfboard-image">
                            </picture>
                        </div>
                        <div class="media-main-block__tips">
                            <button id="tip_1" data-tippy-content="Selecting the correct surfboard is crucial for your skill level and the type of waves you plan to ride. Beginners should opt for longer, more stable boards, while advanced surfers might prefer shorter, more maneuverable ones." class="media-main-block__tip media-main-block__tip_1"><span>+</span></button>
                            <button id="tip_2" data-tippy-content="Understanding wave patterns and how they break can greatly improve your surfing. Spend time observing the ocean and learn to recognize the best waves to catch." class="media-main-block__tip media-main-block__tip_2"><span>+</span></button>
                            <button id="tip_3" data-tippy-content="Properly waxing your board ensures better grip and stability. Apply wax in a circular motion, starting from the top of the board down to the tail." class="media-main-block__tip media-main-block__tip_3"><span>+</span></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="advatages">
        <div class="advatages__container container" data-spoilers="991">
            <div class="advantages__title-mobile" data-spoiler>
                <span class="advantages__title-mobile-text">Why us?</span>
                <span class="advantages__title-mobile-arrow _icon-arrow_sh_d"></span>
            </div>
            <div class="advantages__items">
                <div class="advantages__item item-advantage">
                    <div class="item-advantage__icon">
                        <img src="img/home/garantee.svg" alt="garantee-icon">
                    </div>
                    <div class="item-advantage__text">
                        100% Money-Back Guarantee
                    </div>
                </div>
                <div class="advantages__item item-advantage">
                    <div class="item-advantage__icon">
                        <img src="img/home/truck.svg" alt="truck-icon">
                    </div>
                    <div class="item-advantage__text">
                        Delivery across the US
                    </div>
                </div>
                <div class="advantages__item item-advantage">
                    <div class="item-advantage__icon">
                        <img src="img/home/list.svg" alt="list-icon">
                    </div>
                    <div class="item-advantage__text">
                        Option to place an order without registration
                    </div>
                </div>
                <div class="advantages__item item-advantage">
                    <div class="item-advantage__icon">
                        <img src="img/home/percentages.svg" alt="percentages-icon">
                    </div>
                    <div class="item-advantage__text">
                        Discounts for regular customers
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    if ($brands): ?>
    <section class="brands" data-spoilers="575">
        <div class="brands__container container">
            <div class="brands__title section-title" data-spoiler>Brands</div>
            <div class="brands__cards">
                <?php foreach($brands as $brand): ?>
                <a href="/catalog" class="brands__item item-brand">
                    <div class="item-brand__image">
                        <picture>
                            <source srcset="img/brands/<?= htmlspecialchars($brand['image'], ENT_QUOTES, 'UTF-8')?>.webp" type="image/webp">
                            <source srcset="img/brands/<?= htmlspecialchars($brand['image'], ENT_QUOTES, 'UTF-8')?>.jpg" type="image/jpeg">
                            <img src="img/brands/<?= htmlspecialchars($brand['image'], ENT_QUOTES, 'UTF-8')?>.png" alt="brands-image-<?= htmlspecialchars($brand['name'], ENT_QUOTES, 'UTF-8')?>">
                        </picture>
                    </div>
                    <div class="item-brand__text _icon-arrow_sh_r _ipl-after"><?= htmlspecialchars($brand['name'], ENT_QUOTES, 'UTF-8')?></div>
                </a>
                <?php endforeach;?>
                <a href="/catalog" class="brands__more _icon-arrow_sh_r _ipl-after">
                    MORE
                </a>
            </div>
            <div class="brands__mobile-devider">and</div>
        </div>
    </section>
    <?php endif;?>
    <?php if ($categories): ?>
    <section class="categories" data-spoilers="575">
        <div class="categories__container container">
            <div class="categories__title section-title" data-spoiler>Categories</div>
            <div class="categories__row">
                <?php foreach($categories as $category): ?>
                    <a href="/catalog" class="categories__item item-category categories__item">
                        <div class="item-category__information">
                            <h2 class="item-category__title title-category"><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8');?></h2>
                            <ul class="item-category__list">
                                <?php
                                $slicedSubcategories = array_slice($category['sub_categories'], 0, 3);
                                foreach($slicedSubcategories as $sub_category): ?>
                                    <li class="item-category__item"><span><?= htmlspecialchars($sub_category['title'], ENT_QUOTES, 'UTF-8');?></span></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                        <div class="item-category__image item-category__image_<?= htmlspecialchars($category["id"], ENT_QUOTES, 'UTF-8')?>">
                            <picture>
                                <source srcset="img/categories/<?= htmlspecialchars($category['image'], ENT_QUOTES, 'UTF-8');?>.webp" type="image/webp">
                                <source srcset="img/categories/<?= htmlspecialchars($category['image'], ENT_QUOTES, 'UTF-8');?>.jpg" type="image/jpeg">
                                <img src="img/categories/<?= htmlspecialchars($category['image'], ENT_QUOTES, 'UTF-8');?>.png" alt="category-<?= htmlspecialchars($category['alias'], ENT_QUOTES, 'UTF-8');?>-image">
                            </picture>
                        </div>
                    </a>
                <?php endforeach;?>
            </div>
        </div>
    </section>
    <?php endif;?>
    <?php
    use Surfsail\views\helpers\ProductHelper;
    if ($shortboard_products) {
        ProductHelper::renderSliderCatalog($shortboard_products, "Shortboards");
    }
    if ($longboard_products) {
        ProductHelper::renderSliderCatalog($longboard_products, "Longboards", 'big-gap');
    }
    ?>
    <?php if ($new_products): ?>
    <section class="new-products">
        <div class="new-products__container container">
            <div class="new-products__decorbg">
                <picture>
                    <source srcset="img/home/new_bg_small.webp" type="image/webp">
                    <source srcset="img/home/new_bg_small.jpg" type="image/jpeg">
                    <img src="img/home/new_bg_small.png" alt="bg-new-products-small">
                </picture>
            </div>
            <div class="new-products__body">
                <div class="new-products__content">
                    <h2 class="new-products__title section-title">New products</h2>
                    <div class="new-products__text">Discover the latest in surf gear and accessories! 
                        The freshest additions to our collection, carefully selected to keep you at the forefront 
                        of surf culture. Whether you're looking for the latest surfboard designs, innovative safety gear, or stylish apparel, 
                        you'll find it all right here.
                    </div>
                    <a href="/catalog" class="new-products__link _icon-arrow_sh_r">More new products</a>
                </div>
                <div class="new-products__slider-body">
                    <div class="slider-new-products swiper new-products__slider">
                        <div class="swiper-wrapper">
                            <?php foreach($new_products as $product):?>
                                <div class="slider-products__slide slide-product-card swiper-slide">
                                    <?php ProductHelper::renderCard($product);?>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <div class="pagination">
                            <div class="pagination-bullets"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif;?>
    <?php
    if ($discount_products){
        ProductHelper::renderSliderCatalog($discount_products, "Sale");
    }
    ?>
    <?php if ($recommend_products):?>
    <section class="recommend">
        <div class="recommend__container container">
            <div class="recommend__body">
                <div class="reccomend__cards cards-recommend">
                    <?php foreach($recommend_products as $product): ?>
                    <div class="cards-recommend__card card-recommend">
                        <div class="card-recommend__body">
                            <a class="card-recommend__title title-category"><?= htmlspecialchars($product["name"], ENT_QUOTES, 'UTF-8')?></a>
                            <div class="card-recommend__text"><?= htmlspecialchars($product['description'], ENT_QUOTES,'UTF-8')?></div>
                            <a href="" class="card-recommend__button button">More details</a>
                        </div>
                        <div class="card-recommend__image card-recommend__image_first">
                            <picture>
                                <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES,'UTF-8')?>.webp" type="image/webp">
                                <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES,'UTF-8')?>.jpg" type="image/jpeg">
                                <img src="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES,'UTF-8')?>.png" alt="recommend-product-<?= htmlspecialchars($product['alias'], ENT_QUOTES,'UTF-8')?>">
                            </picture>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </section>
    <?php endif;?>
    <?php if ($gear_products){
        ProductHelper::renderSliderCatalog($gear_products, "Gear");
    }
    ?>
    <?php if($articles): ?>
    <section class="articles">
        <div class="articles__container container">
            <div class="articles__block block-articles">
                <div class="block-articles__top-row section-row-top">
                    <h2 class="block-articles__title section-title">Newest articles</h2>
                </div>
                <div class="block-articles__cards cards-articles">
                    <?php foreach($articles as $article): ?> 
                    <article class="cards-articles__card card-article">
                        <a href="" class="card-article__image">
                            <picture>
                                <source srcset="img/articles/<?= htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8')?>.webp" type="image/webp">
                                <source srcset="img/articles/<?= htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8')?>.jpg" type="image/jpeg">
                                <img src="img/articles/<?= htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8')?>.png" alt="article-image">
                            </picture>
                        </a>
                        <div class="card-article__body">
                            <a href="" class="card-article__title"><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8')?></a>
                            <div class="card-article__date _addit-info _addit-info_light"><?php
                                $date = new DateTime($article['upload_date']);
                                echo htmlspecialchars($date->format('m-d-Y'), ENT_QUOTES, 'UTF-8')
                                ?>
                            </div>
                        </div>
                    </article>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </section>
    <?php endif;?>
</main>