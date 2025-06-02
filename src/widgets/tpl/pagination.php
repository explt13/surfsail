<div class="catalog__pagging pagging">
    <a href=<?= htmlspecialchars($this->page.'?page='. ($this->disabled_prev ? 1 :$this->current_page - 1) );?> class="pagging__arrow pagging__arrow_left _icon-arrow_sh_r <?= $this->disabled_prev ? 'pagging__arrow_disabled': ''?>"></a>
    <ul class="pagging__list">
        <?php if ($this->current_page - 1 > 1): ?>
            <li class="pagging__item"><a href=<?= htmlspecialchars($this->page.'?page=1');?> class="pagging__link"><?= htmlspecialchars(1)?></a></li>
        <?php endif;?>
        <?php if ($this->current_page - 2 > 1): ?>
            <li class="pagging__item"><span class="pagging__dots">..</span></li>
        <?php endif;?>
        <?php if ($this->sec_prev_page): ?>
            <li class="pagging__item"><a href=<?= htmlspecialchars($this->page.'?page='.$this->sec_prev_page);?> class="pagging__link"><?= htmlspecialchars($this->sec_prev_page)?></a></li>
        <?php endif;?>
        <?php if ($this->prev_page): ?>
            <li class="pagging__item"><a href=<?= htmlspecialchars($this->page.'?page='.$this->prev_page);?> class="pagging__link"><?= htmlspecialchars($this->prev_page)?></a></li>
        <?php endif;?>
        <?php if ($this->current_page): ?>
            <li class="pagging__item"><a href=<?= htmlspecialchars($this->page.'?page='.$this->current_page);?> class="pagging__link _active"><?= htmlspecialchars($this->current_page)?></a></li>
        <?php endif;?>
        <?php if ($this->next_page): ?>
            <li class="pagging__item"><a href=<?= htmlspecialchars($this->page.'?page='.$this->next_page);?> class="pagging__link"><?= htmlspecialchars($this->next_page)?></a></li>
        <?php endif;?>
        <?php if ($this->sec_next_page): ?>
            <li class="pagging__item"><a href=<?= htmlspecialchars($this->page.'?page='.$this->sec_next_page);?> class="pagging__link"><?= htmlspecialchars($this->sec_next_page)?></a></li>
        <?php endif;?>
        <?php if ($this->total_pages - $this->current_page > 3):?>
            <li class="pagging__item"><span class="pagging__dots">..</span></li>
        <?php endif;?>
        <?php if ($this->total_pages && $this->total_pages - $this->current_page > 2): ?>
            <li class="pagging__item"><a href=<?= htmlspecialchars($this->page.'?page='.$this->total_pages);?> class="pagging__link"><?= htmlspecialchars($this->total_pages)?></a></li>
        <?php endif;?>
    </ul>
    <a href=<?= htmlspecialchars($this->page.'?page='. ($this->disabled_next ? $this->total_pages :$this->current_page + 1) );?> class="pagging__arrow pagging__arrow_right _icon-arrow_sh_r <?= $this->disabled_next ? 'pagging__arrow_disabled': ''?>"></a>
</div>