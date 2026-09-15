<?php
$crumbs = ['Events' => 'events'];
if ($filter !== 'all') {
    $crumbs[ucfirst($filter)] = null;
}
pageHero($title, $subtitle, $crumbs);

$tabs = [
    'all'      => ['All Events', 'calendar'],
    'upcoming' => ['Upcoming', 'sparkle'],
    'ongoing'  => ['Ongoing', 'clock'],
    'past'     => ['Past', 'check'],
];
?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <?php foreach ($tabs as $key => [$label, $ic]): ?>
        <a class="filter-chip <?= $filter === $key ? 'is-active' : '' ?>"
           href="<?= url('events' . ($key === 'all' ? '' : '/' . $key)) ?>">
          <?= icon($ic) ?> <?= e($label) ?>
          <span class="count"><?= (int) ($counts[$key] ?? 0) ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (!$events): ?>
      <?php emptyState('No events here yet', 'Try another tab, or check back after the committee publishes the next calendar.', 'calendar'); ?>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($events as $ev) eventCard($ev); ?>
      </div>
      <?php paginate($page, $pages, fn($n) => url('events' . ($filter === 'all' ? '' : '/' . $filter)) . (PRETTY_URLS ? '?' : '&') . 'page=' . $n); ?>
    <?php endif; ?>
  </div>
</section>
