<?php
/** Executive committee, previous committees and faculty advisors. */
$view = $params[0] ?? 'current';
if (!in_array($view, ['current', 'previous', 'advisors'], true)) {
    notFound();
}

if ($view === 'advisors') {
    $committee = fetchOne("SELECT * FROM committees WHERE type = 'advisory' ORDER BY is_current DESC, id DESC LIMIT 1");
    $groups = $committee
        ? [[ 'committee' => $committee,
             'members'   => fetchAll('SELECT * FROM committee_members WHERE committee_id = ? ORDER BY sort_order', [$committee['id']]) ]]
        : [];
    $title = 'Faculty Advisors';
    $sub   = 'The faculty members from the Department of CSE who guide the society.';
} elseif ($view === 'previous') {
    $groups = [];
    foreach (fetchAll("SELECT * FROM committees WHERE type='executive' AND is_current = 0 ORDER BY term DESC") as $c) {
        $groups[] = ['committee' => $c,
                     'members'   => fetchAll('SELECT * FROM committee_members WHERE committee_id = ? ORDER BY sort_order', [$c['id']])];
    }
    $title = 'Previous Committees';
    $sub   = 'Every executive committee that has served DUETCS, term by term.';
} else {
    $committee = fetchOne("SELECT * FROM committees WHERE type='executive' AND is_current = 1 ORDER BY id DESC LIMIT 1");
    $groups = $committee
        ? [[ 'committee' => $committee,
             'members'   => fetchAll('SELECT * FROM committee_members WHERE committee_id = ? ORDER BY sort_order', [$committee['id']]) ]]
        : [];
    $title = 'Executive Committee';
    $sub   = 'The students elected to run DUETCS this academic year.';
}

page('committee', [
    'title'       => $title,
    'description' => $sub,
    'subtitle'    => $sub,
    'groups'      => $groups,
    'view'        => $view,
]);
