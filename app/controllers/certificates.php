<?php
/** Public certificate verification. */
$code   = trim((string) (get('code', '') ?: post('code', '')));
$result = null;
$searched = false;

if ($code !== '') {
    $searched = true;
    $clean = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $code));
    $result = fetchOne(
        'SELECT * FROM certificates WHERE UPPER(verify_code) = :code OR UPPER(cert_no) = :cert LIMIT 1',
        ['code' => $clean, 'cert' => $clean]
    );
}

page('certificates', [
    'title'       => 'Verify a Certificate',
    'description' => 'Check that a DUETCS certificate is genuine using its verification code.',
    'subtitle'    => 'Every certificate DUETCS issues carries a code. Enter it below to confirm it is genuine.',
    'code'        => $code,
    'result'      => $result,
    'searched'    => $searched,
    'recent'      => fetchAll('SELECT cert_no, holder_name, title, type, issued_on FROM certificates WHERE is_revoked = 0 ORDER BY issued_on DESC LIMIT 5'),
    'total'       => (int) fetchValue('SELECT COUNT(*) FROM certificates', [], 0),
]);
