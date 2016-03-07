<?php
foreach ($data['sidebar'] as $key => $value) {
    if (!is_array($value)) {
        ?>
        <ul class="nav nav-sidebar">
            <li class="<?=($data['active']==$key?"active":"")?>"><a href="<?= $value ?>"><?= $key ?></a></li>
        </ul>
    <?php
    } else {
        ?>
        <h3><?= $key ?></h3>
        <ul class="nav nav-sidebar">
            <?php
            foreach ($value as $key2 => $value2) {
                ?>
                <li class="<?=($data['active']==$key2?"active":"")?>"><a href="<?= $value2 ?>"><?= $key2 ?></a></li>
            <?php
            }
            ?>
        </ul>
    <?php
    }
}
?>