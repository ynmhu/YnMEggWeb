<?php
if (!isset($_SESSION['bot_id'])) {
    $nobot = true;
} else {
    $prep = $conn->prepare("SELECT * FROM actions WHERE bot_id=:botid ORDER BY id DESC");
    $prep->bindValue(":botid", $_SESSION['bot_id'], PDO::PARAM_INT);
    $prep->execute();
    $results = array_slice($prep->fetchAll(PDO::FETCH_ASSOC), 0, 30);
}
?>
<div class="page-header">
  <h1 class="text-center">Bot Logs</h1>
</div>

<?php if (isset($nobot)) { ?>
<div class="alert alert-info" role="alert">
  No bots have been added yet. 
  <a class="alert-link" href="/adatbazis/addbot">Add a new bot.</a>
</div>
<?php } else { ?>
<table class="table table-hover table-bordered">
  <thead class="table-dark text-center">
    <tr>
      <th>Action</th>
      <th>Arguments</th>
      <th>Timestamp</th>
      <th>Picked up?</th>
      <th>Time of pick up</th>
      <th>Success?</th>
      <th>Message</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results as $res) { ?>
    <tr>
      <td><?= htmlspecialchars($res['command'] ?? '') ?></td>
      <td><?= is_null($res['arguments']) ? '---' : htmlspecialchars($res['arguments'] ?? '') ?></td>
      <td><?= date("D M j G:i:s T Y", $res['timestamp']) ?></td>
      <td>
        <?php if ((int)$res['executed'] === 1) { ?>
          <span class="badge bg-success"><i class="bi bi-check-circle"></i></span>
        <?php } else { ?>
          <span class="badge bg-danger"><i class="bi bi-x-circle"></i></span>
        <?php } ?>
      </td>
      <td><?= (int)$res['executed'] === 1 ? date("D M j G:i:s T Y", $res['pickup']) : '' ?></td>
      <td>
        <?php if ((int)$res['success'] === 1) { ?>
          <span class="badge bg-success"><i class="bi bi-check-circle"></i></span>
        <?php } elseif ((int)$res['success'] === 0) { ?>
          <span class="badge bg-danger"><i class="bi bi-x-circle"></i></span>
        <?php } else { ?>
          <span class="badge bg-secondary">N/A</span>
        <?php } ?>
      </td>
      <td><?= isset($res['message']) ? htmlspecialchars($res['message']) : '' ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php } ?>
