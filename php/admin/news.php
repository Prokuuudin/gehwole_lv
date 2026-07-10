<?php

require_once __DIR__ . '/../includes/storage.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/upload.php';
require_once __DIR__ . '/includes/layout.php';

require_login();

$action = $_GET['action'] ?? 'list';
$errors = [];

$items = load_collection('news');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['add', 'edit'], true)) {
    require_csrf();
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'date' => trim($_POST['date'] ?? ''),
        'text' => trim($_POST['text'] ?? ''),
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
    ];
    $errors = required_field_errors($data, ['title']);
    $errors = array_merge($errors, max_length_errors($data, ['title' => 255]));

    $imageName = ($_POST['existing_image'] ?? '') !== '' ? $_POST['existing_image'] : null;
    if (!empty($_FILES['image']['name'])) {
        $saved = save_uploaded_image($_FILES['image'], __DIR__ . '/../../uploads/news');
        if ($saved === null) {
            $errors[] = 'Neizdevās augšupielādēt attēlu (pārbaudi formātu un izmēru, maks. 5 MB).';
        } else {
            $imageName = $saved;
        }
    }

    if (!$errors) {
        $dateValue = $data['date'] !== '' ? $data['date'] : null;
        if ($action === 'add') {
            $items[] = [
                'id' => next_id($items),
                'title' => $data['title'],
                'date' => $dateValue,
                'text' => $data['text'],
                'image' => $imageName,
                'sort_order' => $data['sort_order'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        } else {
            $id = (int)$_POST['id'];
            foreach ($items as &$i) {
                if ((int)$i['id'] === $id) {
                    $i['title'] = $data['title'];
                    $i['date'] = $dateValue;
                    $i['text'] = $data['text'];
                    $i['image'] = $imageName;
                    $i['sort_order'] = $data['sort_order'];
                    break;
                }
            }
            unset($i);
        }
        save_collection('news', $items);
        header('Location: news.php');
        exit;
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    require_csrf();
    $id = (int)$_GET['id'];
    $items = array_values(array_filter($items, fn($i) => (int)$i['id'] !== $id));
    save_collection('news', $items);
    header('Location: news.php');
    exit;
}

$items = sort_rows($items);

$editing = null;
if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($items as $i) {
        if ($i['id'] == $_GET['id']) {
            $editing = $i;
            break;
        }
    }
}

admin_header('Jaunumi');
foreach ($errors as $e) {
    echo '<p class="error">' . htmlspecialchars($e) . '</p>';
}
?>
<table>
<tr><th>ID</th><th>Nosaukums</th><th>Datums</th><th>Attēls</th><th></th></tr>
<?php foreach ($items as $i): ?>
<tr>
  <td><?= (int)$i['id'] ?></td>
  <td><?= htmlspecialchars($i['title']) ?></td>
  <td><?= htmlspecialchars($i['date'] ?? '—') ?></td>
  <td><?= $i['image'] ? htmlspecialchars($i['image']) : '—' ?></td>
  <td>
    <a href="news.php?action=edit&id=<?= (int)$i['id'] ?>">Rediģēt</a>
    <a href="news.php?action=delete&id=<?= (int)$i['id'] ?>&csrf=<?= urlencode(csrf_token()) ?>" onclick="return confirm('Dzēst?')">Dzēst</a>
  </td>
</tr>
<?php endforeach; ?>
</table>

<h2><?= $editing ? 'Rediģēt jaunumu' : 'Pievienot jaunumu' ?></h2>
<form method="post" action="news.php?action=<?= $editing ? 'edit' : 'add' ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <?php if ($editing): ?>
    <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editing['image'] ?? '') ?>">
  <?php endif; ?>
  <label>Nosaukums: <input type="text" name="title" value="<?= htmlspecialchars($editing['title'] ?? '') ?>" required></label><br>
  <label>Datums: <input type="date" name="date" value="<?= htmlspecialchars($editing['date'] ?? '') ?>"></label><br>
  <label>Teksts: <textarea name="text" rows="4" cols="50"><?= htmlspecialchars($editing['text'] ?? '') ?></textarea></label><br>
  <label>Attēls: <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></label><br>
  <label>Kārtība: <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>"></label><br>
  <button type="submit"><?= $editing ? 'Saglabāt' : 'Pievienot' ?></button>
</form>
<?php admin_footer(); ?>
