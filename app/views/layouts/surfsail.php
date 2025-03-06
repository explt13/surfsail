<!DOCTYPE html>
<html lang="en">
<head>
    <?php if (isset($this->meta['description'])): ?>
    <meta name="description" content="<?=  htmlspecialchars($this->meta['description'], ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif;?>
    <?php if (isset($this->meta['keywords'])): ?>
    <meta name="keywords" content="<?= htmlspecialchars($this->meta['keywords'], ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif;?>
    <?php if (isset($this->meta['title'])): ?>
    <title><?=  htmlspecialchars($this->meta['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <?php endif;?>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.min.css"/>
    <link rel="stylesheet" href="css/extra.css"/>
    <script src="js/app.min.js" defer></script>
    <script type="module" src="js/script.js" defer></script>
    <meta charset="UTF-8">
</head>

<body data-page=<?= lcfirst($this->route->controller)?>>
    <div class="wrapper">
        <header class="header">
            <div class="header__row header__row_top top-header">
                <div class="top-header__container container">
                    <nav class="top-header__menu menu-top-header">
                        <ul data-relocate=".menu__body, 991.98" class="menu-top-header__list">
                            <li class="menu-top-header__item menu-top-header__item_account"></li>
                            <li class="menu-top-header__item menu-top-header__item_catalog"><a data-catalog href="#" class="menu-top-header__link menu-top-header__link_catalog _icon-arrow_sh_d _ipl-after">Product catalog</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">About us</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">Shipping</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">News</a></li>
                            <li class="menu-top-header__item"><a href="#" class="menu-top-header__link">Contacts</a></li>
                        
                        </ul>
                    </nav>
                    <div class="top-header__user-panel" data-relocate=".menu-top-header__item_account,991.98">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href='user' class="top-header__user link-user">Welcome, <?= htmlspecialchars($_SESSION['user']['first_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></a>
                        <a href='user/logout' class="top-header__logout"><img src="img/home/logout.svg" /></a>
                    <?php else:?>
                        <a href="auth?form=login" class="top-header__user-login">Log In</a>
                        <a href="auth?form=register" class="top-header__user-signup">Sign Up</a>
                    <?php endif;?>
                    </div>
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
                    <a href="/" class="body-header__logo _icon-logo"></a>
                    <div data-relocate=".catalog-header__container, 479.98" class="body-header__search search-header">
                        <div class="search-header__form">
                            <button class="search-header__button _icon-search"></button>
                            <input type="text" placeholder="Search.." autocomplete="off" data-error="Error" class="search-header__search">
                        </div>
                        <div class="search-header__result"></div>
                    </div>
                    <div class="body-header__information information">
                        <div class="information__details">
                            <div class="information__city _icon-geo">Los Angeles</div>
                            <div data-relocate=".top-header__container, 991.98, first"class="information__number number">
                                <div data-spoilers class="number__numbers">
                                    <a class="number__num" href="tel:11234567890">+1 (123) 456-7890</a>
                                    <span class="arrow_num _icon-arrow_sh_d" data-spoiler></span>
                                    <ul class="number__list">
                                        <li><a href="tel:11234567891">+1 (123) 456-7891</a></li>
                                        <li><a href="tel:11234567892">+1 (123) 456-7892</a></li>
                                        <li><a href="tel:11234567893">+1 (123) 456-7893</a></li>
                                    </ul>
                                </div>
                                <div class="number__order">Order a call</div>
                            </div>
                        </div>
                        <a data-relocate=".top-header__container, 991.98, 2" href="favorite" class="information__fav _icon-fav"></a>
                        <?php (new app\widgets\cart\Cart())->render();?>
                    </div>
                </div>
            </div>
            <?php (new app\widgets\menu\Menu())->render() ;?>
        </header>
        <?= $content ?>
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
                <div data-spoilers="767" data-spoiler-single class="body-footer__container container">
                    <div  class="body-footer__row footer-body-row footer-body-row_top">
                        <div class="footer-body-row__column column-item">
                            <div class="column-item__spoiler spoiler-item-footer">
                                <button class="column-item__title" data-spoiler>INFORMATION</button>
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
                            <div class="column-item__spoiler spoiler-item-footer">
                                <button class="column-item__title" data-spoiler>SUPPORT</button>
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
                            <div class="column-item__spoiler spoiler-item-footer">
                                <button class="column-item__title" data-spoiler>ADDITIONAL</button>
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
                            <div class="column-item__spoiler spoiler-item-footer">
                                <button class="column-item__title" data-spoiler>MY ACCOUNT</button>
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
                            <div class="column-item__spoiler spoiler-item-footer">
                                <button class="column-item__title" data-spoiler>CONTACTS</button>
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
                            <div class="column-item__spoiler spoiler-item-footer">
                                <button class="column-item__title" data-spoiler>USEFUL LINKS</button>
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
                            <div class="column-item__spoiler spoiler-item-footer">
                                <button class="column-item__title item-guarantee" data-spoiler>OUR GUARANTEE</button>
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
                            <button class="column-item__title _not-spoiler">NEWSLETTER</button>
                            <form action="#" class="column-item__form form-newsletter">
                                <label for="form-newsletter__input" class="form-newsletter__label">Subscribe</label>
                                <div class="form-newsletter__input-container">
                                    <input autocomplete="off" name="form[]" type="text" data-error="Error" data-required="email" placeholder="example@gmail.com" class="form-newsletter__input" id="form-newsletter__input">
                                    <button type="submit" class="form-newsletter__button _icon-arrow_sh_r"></button>
                                </div>
                                <div class="form-newsletter__agreement checkbox checkbox_terms">
                                    <input id="newsletter-aggreement" name="newsletter" type="checkbox" class="checkbox__input form-newsletter__agreement-button">
                                    <label for="newsletter-aggreement" tabindex="0" class="checkbox__label checkbox__label_terms form-newsletter__label-aggrement">
                                        <div class="checkbox__box checkbox__box_terms"></div>
                                        <div class="checkbox__text checkbox__text_terms">I have read and agree to the </div>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="body-footer__socials">
                        <a href="#"><img src="img/footer/facebook.svg" alt="facebook"></a>
                        <a href="#"><img src="img/footer/viber.svg" alt="viber"></a>
                        <a href="#"><img src="img/footer/whatsapp.svg" alt="whatsapp"></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div class="notification">
        <div class="notification__container">
        </div>
    </div>
    <template id="notification-item-template">
        <div class="notification__item">
            <div class="notification__message"></div>
            <button class="notification__close"></button>
            <div class="notification__time"></div>
        </div>
    </template>
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>
</body>
</html>
