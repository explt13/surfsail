<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->getMeta() ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.min.css?_v=20240719131907"/>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
</head>

<body>
    <div class="wrapper">
        <header class="header">
            <div class="header__row header__row_top top-header">
                <div class="top-header__container container">
                    <nav class="top-header__menu menu-top-header">
                        <ul data-da=".menu__body, 991.98" class="menu-top-header__list">
                            <li class="menu-top-header__item menu-top-header__item_catalog"><a data-catalog href="#" class="menu-top-header__link menu-top-header__link_catalog _icon-arrow_sh_d">Product catalog</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">About us</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">Shipping</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">News</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">Contacts</a></li>
                        </ul>
                    </nav>
                    <a class="top-header__user link-user _icon-user"><span>Account</span></a>
                    <nav class="menu">
                        <button class="menu__icon icon-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                        <div class="menu__body">
                        </div>
                    </nav>
                </div>
            </div>
            <div class="header__row header__row_body body-header">
                <div class="body-header__container container">
                    <a href="./index.html" class="body-header__logo _icon-logo"></a>
                    <div data-da=".catalog-header__container, 479.98" class="body-header__search search-header">
                        <form action="#" class="search-header__form">
                            <button type="submit" class="search-header__button _icon-search"></button>
                            <input type="text" placeholder="Search.." name="form[]" autocomplete="off" data-error="Error" class="search-header__search">
                        </form>
                    </div>
                    <div class="body-header__information information">
                        <div class="information__details">
                            <div class="information__city _icon-geo">Los Angeles</div>
                            <div data-da=".top-header__container, 991.98, first"class="information__number number">
                                <div data-spollers class="number__numbers">
                                    <a class="number__num" href="tel:11234567890">+1 (123) 456-7890</a>
                                    <span class="arrow_num _icon-arrow_sh_d" data-spoller></span>
                                    <ul class="number__list">
                                        <li><a href="tel:11234567891">+1 (123) 456-7891</a></li>
                                        <li><a href="tel:11234567892">+1 (123) 456-7892</a></li>
                                        <li><a href="tel:11234567893">+1 (123) 456-7893</a></li>
                                    </ul>
                                </div>
                                <div class="number__order">Order a call</div>
                            </div>
                        </div>
                        <a data-da=".top-header__container, 991.98, 2" href="#" class="information__fav _icon-fav"></a>
                        <a data-da=".top-header__container, 991.98, 2" href="" class="information__shop shop">
                            <div href="#" class="shop__cart _icon-cart"></div>
                            <div class="shop__details">
                                <div class="shop__sum">0 $</div>
                                <div href="#" class="shop__order">Place an order</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php if ($categories): ?>
                <div class="header__row header__row_catalog catalog-header">
                    <div class="catalog-header__container container">
                        <nav class="catalog-header__menu menu-catalog">
                            <button class="menu-catalog__back back-menu _icon-back">Back</button>
                            <ul class="menu-catalog__list">
                                <?php foreach($categories as $category): ?>
                                    <li class="menu-catalog__item"><button data-parent="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8')?>" class="menu-catalog__link"><?= htmlspecialchars($category["title"], ENT_QUOTES, 'UTF-8')?></button></li>
                                <?php endforeach;?>
                            </ul>
                            <div class="menu-catalog__sub-menu sub-menu-catalog">
                                <button class="sub-menu-catalog__back back-menu _icon-back">Back</button>
                                <div class="sub-menu-catalog__container container">
                                    <?php foreach($categories as $category):?>
                                        <?php if ($category["sub_categories"]): ?>
                                            <div hidden data-submenu="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8')?>" class="sub-menu-catalog__block">
                                                <?php foreach($category["sub_categories"] as $sub_category): ?>
                                                    <div class="sub-menu-catalog__category">
                                                        <a href="./catalog.html" class="sub-menu-catalog__link-category"><?= htmlspecialchars($sub_category["title"], ENT_QUOTES, 'UTF-8')?></a>
                                                    </div>
                                                <?php endforeach;?>
                                                <?php foreach($category["sub_categories"] as $sub_category): ?>
                                                    <?php if ($sub_category['sub_categories']): ?>
                                                        <ul class="sub-menu-catalog__list">
                                                            <?php foreach($sub_category["sub_categories"] as $sub_sub_category): ?>
                                                                <li class="sub-menu-catalog__item"><a href="./catalog.html" class="sub-menu-catalog__link-item"><?= htmlspecialchars($sub_sub_category['title'], ENT_QUOTES, 'UTF-8')?></a></li>
                                                            <?php endforeach;?>
                                                        </ul>
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                                <?php foreach($category["sub_categories"] as $sub_category): ?>
                                                    <?php if ($sub_category["subcategories_count"] > $subcategories_to_show): ?>
                                                        <div class="sub-menu-catalog__footer">
                                                            <a href="./catalog.html" class="sub-menu-catalog__all">More</a>
                                                        </div>
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                            </div>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            <?php endif;?>
        </header>
        <?= $view ?>
        <footer class="footer">
            <div class="footer__top-row top-row-footer">
                <div class="top-row-footer__container container">
                    <div class="top-row-footer__description description-footer">
                        <h2 class="description-footer__title">Surfboards from SurfSail</h2>
                        <div class="description-footer__text">
                            Welcome to SurfSail, your ultimate online destination for top-quality surfboards. Our store offers a wide selection of surfboards designed for surfers of all levels, from beginners to professionals. 
                            At SurfSail, we take pride in providing surfboards that are known for their exceptional performance and durability. Whether you are looking for longboards, shortboards, fishboards, or funboards, we have the perfect board to match your style and needs. 
                            Our surfboards are crafted with the finest materials, ensuring a superior surfing experience in various wave conditions. Many of our products come with warranties of up to 5 years, highlighting our commitment to quality and customer satisfaction.
                            Explore our extensive range of surfboards on our website, SurfSail. Our knowledgeable staff is ready to assist you in selecting the ideal board, tailored to your preferences and surfing goals.
                            Dive in and ride the waves with SurfSail, where quality and performance meet the thrill of surfing.
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer__body body-footer">
                <div data-spollers="600" class="body-footer__container container">
                    <div  class="body-footer__row footer-body-row footer-body-row_top">
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoller spoller-item-footer">
                                <button class="column-item__title" data-spoller>INFORMATION</button>
                                <nav class="column-item__body">
                                    <ul class="column-item__list">
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Surfboards in Los Angeles and Surrounding Areas</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Surfboard Materials</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">About us</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Payment and Delivery Terms</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Privacy Policy</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>    
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoller spoller-item-footer">
                                <button class="column-item__title" data-spoller>SUPPORT</button>
                                <nav class="column-item__body">
                                    <ul class="column-item__list">
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Contact Information</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Returns</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Site Map</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>  
                        </div>
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoller spoller-item-footer">
                                <button class="column-item__title" data-spoller>ADDITIONAL</button>
                                <nav class="column-item__body">
                                    <ul class="column-item__list">
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Gift Certificates</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Partners</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Discounted Items</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoller spoller-item-footer">
                                <button class="column-item__title" data-spoller>MY ACCOUNT</button>
                                <nav class="column-item__body">
                                    <ul class="column-item__list">
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">My Account</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Order History</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">My Wishlist</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Newsletter</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="body-footer__row footer-body-row">
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoller spoller-item-footer">
                                <button class="column-item__title" data-spoller>CONTACTS</button>
                                <nav class="column-item__body">
                                    <ul class="column-item__list">
                                        <li class="column-item__list-item">
                                            <a href="tel:11234567890" class="column-item__link _icon-call">+1 (123) 456-7890</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link _icon-clocks">Mon-Fri 8:00 AM - 5:00 PM (PST)</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link _icon-geo">Los Angeles, 123 Ocean Drive, Office 5A</a>
                                        </li>
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link _icon-mail">info@surfsail.com</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoller spoller-item-footer">
                                <button class="column-item__title" data-spoller>USEFUL LINKS</button>
                                <nav class="column-item__body">
                                    <ul class="column-item__list">
                                        <li class="column-item__list-item">
                                            <a href="" class="column-item__link">Payment and Delivery Methods</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoller spoller-item-footer">
                                <button class="column-item__title item-guarantee" data-spoller>OUR GUARANTEE</button>
                                <nav class="column-item__body">
                                    <ul class="column-item__list">
                                        <li class="column-item__list-item">
                                            <span href="" class="item-guarantee__text">
                                                Not satisfied with your purchase? 
                                                You can return it within 30 days of receipt,
                                                according to our <a href="" class="item-guarantee__link">return policy</a>
                                            </span>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="footer-body-row__column column-item column-item_mailing">
                            <button class="column-item__title _not-spoller">NEWSLETTER</button>
                            <form action="#" class="column-item__form form-mailing">
                                <label for="form-mailing__input" class="form-mailing__label">Subscribe</label>
                                <div class="form-mailing__input-container">
                                    <input autocomplete="off" name="form[]" type="text" data-error="Error" data-required="email" placeholder="example@gmail.com" class="form-mailing__input" id="form-mailing__input">
                                    <button type="submit" class="form-mailing__button _icon-arrow_sh_r"></button>
                                </div>
                                <div class="form-mailing__agreement">
                                    <input type="checkbox" class="form-mailing__agreement-button">
                                    <a href="" class="form-mailing__policy">I have read and agree to the Terms and Conditions.</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="body-footer__socials">
                        <a href="#"><img src="/img/home/footer/facebook.svg" alt="facebook"></a>
                        <a href="#"><img src="/img/home/footer/viber.svg" alt="viber"></a>
                        <a href="#"><img src="/img/home/footer/whatsapp.svg" alt="whatsapp"></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="http://surfsail/js/app.min.js?_v=20240719131907"></script>
    <script src="/js/script.js"></script>
</body>
</html>
