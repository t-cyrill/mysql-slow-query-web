<?php
require __DIR__ . '/data.php';
$hostgroups = require __DIR__ . '/dbconf.php';
$params = $_GET + ['order' => 'time', 'hostgroup' => ''];
$order = $params['order'];
$hostgroup = $params['hostgroup'];
if (!isset($hostgroups[$hostgroup])) {
  echo 'No hostgroup definitions'; exit;
}
$data = getSlowData($hostgroups[$hostgroup], $order);
?>
<!DOCTYPE html>
<title>MySQL Slow Query Viewer</title>
<style type="text/css">
table {
  width: 100%;
}
td, th {
  border: solid 1px;
}
td.sql {
  width: 100%;
}
.nowrap {
  white-space: nowrap;
}
a {
  text-decoration: none;
}
</style>

<h1>MySQL Slow Query Viewer</h1>

<ul>
  <li><a href="?order=time">最新10件</a></li>
  <li><a href="?order=qtime">遅い順10件</a></li>
  <li><a href="?order=sent">転送量が多い順10件</a></li>
  <li><a href="?order=examined">探索が多い順10件</a></li>
</ul>

<?php foreach ($data as $groupname => $hostgroup): ?>

<h1><?= $groupname; ?></h1>

<?php foreach ($hostgroup as $d): ?>

<h2><?= $d['name']; ?></h2>

<table>
  <tr>
    <th>time</th>
    <?php // <th>user_host</th> ?>
    <th>query_time</th>
    <th>lock_time</th>
    <th>rows_sent</th>
    <th>rows_examined</th>
    <th>db</th>
    <th>sql_text</th>
  </tr>

  <?php foreach ($d['rs'] as $r): ?>
  <tr>
    <td class="nowrap"><?= $r['start_time'] ?></td>
    <?php // <td><?= $r['user_host'] ></td> ?>
    <td><?= $r['query_time'] ?></td>
    <td><?= $r['lock_time'] ?></td>
    <td><?= $r['rows_sent'] ?></td>
    <td><?= $r['rows_examined'] ?></td>
    <td><?= $r['db'] ?></td>
    <td class="sql"><?= $r['sql_text'] ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php endforeach; ?>
<?php endforeach; ?>

