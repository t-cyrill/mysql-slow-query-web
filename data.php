<?php
function getSlowData($hostgroup, $order) {
  $sqls = [
    'time'     => 'SELECT * FROM `mysql`.`slow_log` ORDER BY `start_time` DESC LIMIT 10',
    'qtime'    => 'SELECT * FROM `mysql`.`slow_log` ORDER BY `query_time` DESC LIMIT 10',
    'sent'     => 'SELECT * FROM `mysql`.`slow_log` ORDER BY `rows_sent` DESC LIMIT 10',
    'examined' => 'SELECT * FROM `mysql`.`slow_log` ORDER BY `rows_examined` DESC LIMIT 10',
  ];
  if (!isset($sqls[$order])) {
    $order = 'time';
  }
  $data = [];
  foreach ($hostgroup['hosts'] as $host) {
    $pdo = new PDO("mysql:host={$host['addr']};port={$host['port']}", $host['user'], $host['password']);
    $ps = $pdo->prepare($sqls[$order]);
    $ps->execute();
    $rs = $ps->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rs as &$tmp) {
      foreach (['query_time', 'lock_time'] as $column) {
        list($his, $ms) = explode('.', $tmp[$column]);
        $t = 0;
        foreach (array_reverse(array_map('intval', explode(':', $his))) as $k => $v) {
          $t += $v * 60**$k;
        }
        $tmp[$column] = "{$t}.{$ms}";
      }
      $tmp['start_time'] = explode('.', $tmp['start_time'])[0];
    }
    unset($tmp);
    $name = $host['name'];
    $data[] = [
      'name' => $name,
      'rs' => $rs,
    ];
  }
  return $data;
}
