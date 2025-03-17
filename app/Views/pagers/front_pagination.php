<?php $pager->setSurroundCount(1) ?>

<nav aria-label="Page navigation" class='custom-post-nav text-center'>
    <ul class="nav pagination post-pagination justify-content-center test-pagination">
    <?php if ($pager->hasPrevious()) : ?>
        <li class="nav-item">
            <a class="first-page" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                <span aria-hidden="true"><?= "&#171; Pertama" //lang('Pager.first') ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="prev-page" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
                <span aria-hidden="true"><?= "Sebelumnya" //lang('Pager.previous') ?></span>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link): ?>
        <li <?= $link['active'] ? 'class="nav-item active"' : 'class="nav-item"' ?>>
            <?= $link['active'] ? '<span class="active">' : '<a href="'.$link['uri'].'">' ?>
                <?= $link['title'] ?>
            <?= $link['active'] ? '</span>' : '</a>' ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li class="nav-item">
            <a class="next-page" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
                <span aria-hidden="true"><?= "Berikutnya" //lang('Pager.next') ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="last-page" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                <span aria-hidden="true"><?= "Terakhir &raquo;" //lang('Pager.last') ?></span>
            </a>
        </li>
    <?php endif ?>
    </ul>
</nav>