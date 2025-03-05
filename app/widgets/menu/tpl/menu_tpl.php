<?php if ($this->categories): ?>
    <div class="header__row header__row_catalog catalog-header">
        <div class="catalog-header__container container">
            <nav class="catalog-header__menu menu-catalog">
                <button class="menu-catalog__back back-menu _icon-back">Back</button>
                <ul class="menu-catalog__list">
                    <?php foreach($this->categories as $category): ?>
                        <li class="menu-catalog__item"><button data-parent="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8')?>" class="menu-catalog__link _icon-arrow_sh_d _ipl-after"><?= htmlspecialchars($category["title"], ENT_QUOTES, 'UTF-8')?></button></li>
                    <?php endforeach;?>
                </ul>
                <div class="menu-catalog__sub-menu sub-menu-catalog">
                    <button class="sub-menu-catalog__back back-menu _icon-back">Back</button>
                    <div class="sub-menu-catalog__container container">
                        <?php foreach($this->categories as $category):?>
                            <?php if ($category["sub_categories"]): ?>
                                <div hidden data-submenu="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8')?>" class="sub-menu-catalog__block">
                                    <?php foreach($category["sub_categories"] as $sub_category): ?>
                                        <div class="sub-menu-catalog__category">
                                            <a href="<?= htmlspecialchars($category['alias'], ENT_QUOTES, 'UTF-8')?>.php" class="sub-menu-catalog__link-category"><?= htmlspecialchars($sub_category["title"], ENT_QUOTES, 'UTF-8')?></a>
                                        </div>
                                    <?php endforeach;?>
                                    <?php foreach($category["sub_categories"] as $sub_category): ?>
                                        <?php if ($sub_category['sub_categories']): ?>
                                            <ul class="sub-menu-catalog__list">
                                                <?php foreach($sub_category["sub_categories"] as $sub_sub_category): ?>
                                                    <li class="sub-menu-catalog__item"><a href="<?= htmlspecialchars($sub_sub_category['alias'], ENT_QUOTES, 'UTF-8')?>.php" class="sub-menu-catalog__link-item"><?= htmlspecialchars($sub_sub_category['title'], ENT_QUOTES, 'UTF-8')?></a></li>
                                                <?php endforeach;?>
                                            </ul>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    <?php foreach($category["sub_categories"] as $sub_category): ?>
                                        <?php if ($sub_category["sub_categories_count"] > $this->sub_categories_to_show): ?>
                                            <div class="sub-menu-catalog__footer">
                                                <a href="<?= htmlspecialchars($category['alias'], ENT_QUOTES, 'UTF-8')?>.php" class="sub-menu-catalog__all">More</a>
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