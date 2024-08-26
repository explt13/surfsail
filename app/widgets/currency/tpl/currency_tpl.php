<option value="<?= htmlspecialchars($this->currency['code'], ENT_QUOTES, 'UTF-8')?>"><?= htmlspecialchars($this->currency['code'], ENT_QUOTES, 'UTF-8')?></option>
<?php foreach ($this->currencies as $k => $v):?>
    <?php if ($k != $this->currency['code']):?>
        <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8')?>"><?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8')?></option>
    <?php endif;?>
<?php endforeach?>