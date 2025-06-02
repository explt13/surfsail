<main class="page page_catalog">
    <div class="page__header">
        <div class="page__container container">
            <div class="page__title">Surfboards</div>
            <nav class="page__breadcrumbs breadcrumbs">
                <ul class="breadcrumbs__list">
                    <li class="breadcrumbs__item">
                        <a href="./index.html" class="breadcrumbs__link">Main Page</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <span href="" class="breadcrumbs__current">Surfboards</span>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <section class="catalog">
        <div class="catalog__container container">
            <div class="catalog__sort">
                <select name="form[]" class="sort">
                    <option selected value="1">Best Sellers</option>
                    <option value="2">Avg. Customer Review</option>
                    <option value="3">Newest Arrivals</option>
                    <option value="4">Price: Low to High</option>
                    <option value="4">Price: High to Low</option>
                </select>
            </div>
            <div class="catalog__body">
                <div class="catalog__filter filter-catalog" data-spoiler-opened data-spoilers="1044,max">
                    <!-- <button class="filter-catalog__apply-button">Apply filters</button> -->
                    <button data-spoiler data-spoiler-time="1000" type="button" data-spoiler class="filter-catalog__title">Filter</button>
                    <div data-spoiler-opened="1045,min" data-spoilers class="filter-catalog__items">
                        <?php foreach ($this->data['filters'] as $filter):?>
                            <?php $style_modifier = $filter['style_modifier'] ? htmlspecialchars('item-filter_' . $filter['style_modifier']) : null; ?>
                            <div class="item-filter <?= $style_modifier ?>" data-filtertype=<?= htmlspecialchars($filter['type']); ?>>
                                <button type="button" class="item-filter__title _icon-arrow_sh_d _active" data-alias=<?= htmlspecialchars($filter['alias'])?> data-spoiler><?= htmlspecialchars($filter['name'])?></button>
                                <?php switch ($filter['type']):
                                    case "checkbox": ?>
                                        <div class="item-filter__body">
                                            <?php foreach ($filter['options'] as $option): ?> 
                                                <div class="checkbox checkbox_filter">
                                                    <?php
                                                    $checked = in_array($option['alias'], $this->data['selected_filters'][$filter['alias']] ?? []); 
                                                    $id = bin2hex(random_bytes(8));
                                                    ?>
                                                    <input id="<?= htmlspecialchars($id);?>"type="checkbox" class="checkbox__input" <?= $checked ? 'checked=true' : ''?> value="<?= htmlspecialchars($option['alias']);?>">
                                                    <label for="<?= htmlspecialchars($id);?>" class="checkbox__label checkbox__label_filter">
                                                        <span class="checkbox__box checkbox__box_filter"></span>
                                                        <span class="checkbox__text checkbox__text_filter"><?= htmlspecialchars($option['name'])?></span><small><?= htmlspecialchars($option['product_qty'])?></small>
                                                    </label>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    <?php break;
                                    case "range": ?>
                                    <?php
                                    list($min, $max) = $this->data['selected_filters'][$filter['alias']] ?? [null, null];
                                    ?>
                                        <div data-range class="item-filter__body range-item">
                                            <div class="range-item__inputs">
                                                <input data-range-from="0" type="text" value=<?=$min ?? 0 ?> autocomplete="off" name="priceMin" class="range-item__input">
                                                <input data-range-to="3000" type="text" value=<?=$max ?? 3000 ?> autocomplete="off" name="priceMax" class="range-item__input">
                                            </div>
                                            <div data-range-item class="range-item__slider"></div>
                                        </div>
                                    <?php break;
                                endswitch;?>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="catalog__content">
                    <div class="catalog__products">
                        <?php
                        use Surfsail\render\helpers\ProductHelper;
                        if (empty($this->data['products'])): ?>
                            <div class="catalog__no-products">No products found</div>
                        <?php endif; ?>
                        <?php
                        foreach($this->data['products'] as $product){
                            ProductHelper::renderCard($this->data['currency'], $product);
                        }
                        ?>
                    </div>
                    <?php if ($this->data['pagination']): ?>
                    <div class="catalog__footer">
                        <!-- <a href="" class="catalog__more button">Show more</a> -->
                        <?= $this->data['pagination']->render(); ?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </section>
</main>