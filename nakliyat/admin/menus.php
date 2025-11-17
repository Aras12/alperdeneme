<?php
$page_title = 'Menü Yönetimi';
include 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM menus WHERE id=$id");
    echo alert('Menü silindi!', 'success');
}

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $url = sanitize($_POST['url']);
    $target = sanitize($_POST['target']);
    $icon = sanitize($_POST['icon']);
    $parent_id = (int)$_POST['parent_id'];
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = (int)$_POST['id'];
        $sql = "UPDATE menus SET
                title='$title',
                url='$url',
                target='$target',
                icon='$icon',
                parent_id=$parent_id,
                display_order=$display_order,
                is_active=$is_active
                WHERE id=$id";
        $conn->query($sql);
        echo alert('Menü güncellendi!', 'success');
    } else {
        // Insert
        $sql = "INSERT INTO menus (title, url, target, icon, parent_id, display_order, is_active)
                VALUES ('$title', '$url', '$target', '$icon', $parent_id, $display_order, $is_active)";
        $conn->query($sql);
        echo alert('Menü eklendi!', 'success');
    }
}

// Get menu for editing
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM menus WHERE id=$id");
    $edit_data = $result->fetch_assoc();
}

// Fetch all menus
$menus = $conn->query("SELECT * FROM menus ORDER BY display_order ASC");
?>

<div class="row">
    <div class="col-md-4">
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-<?= $edit_data ? 'edit' : 'plus' ?> me-2"></i>
                <?= $edit_data ? 'Menü Düzenle' : 'Yeni Menü Ekle' ?>
            </h5>
            <form method="POST" action="">
                <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" class="form-control" required
                           value="<?= $edit_data['title'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">URL *</label>
                    <input type="text" name="url" class="form-control" required
                           value="<?= $edit_data['url'] ?? '' ?>"
                           placeholder="Örn: /hakkimizda veya https://example.com">
                    <small class="text-muted">İç link için "/" ile başlayın (örn: /hakkimizda)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hedef</label>
                    <select name="target" class="form-select">
                        <option value="_self" <?= ($edit_data && $edit_data['target'] == '_self') ? 'selected' : '' ?>>Aynı Pencere (_self)</option>
                        <option value="_blank" <?= ($edit_data && $edit_data['target'] == '_blank') ? 'selected' : '' ?>>Yeni Pencere (_blank)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">İkon (Font Awesome)</label>
                    <input type="text" name="icon" class="form-control"
                           value="<?= $edit_data['icon'] ?? '' ?>"
                           placeholder="Örn: fas fa-home">
                    <small class="text-muted">Font Awesome sınıfı (isteğe bağlı)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Üst Menü</label>
                    <select name="parent_id" class="form-select">
                        <option value="0">Ana Menü</option>
                        <?php
                        $parent_menus = $conn->query("SELECT * FROM menus WHERE parent_id=0 ORDER BY display_order ASC");
                        while($pm = $parent_menus->fetch_assoc()):
                            if ($edit_data && $pm['id'] == $edit_data['id']) continue; // Kendisini parent yapmasın
                        ?>
                        <option value="<?= $pm['id'] ?>" <?= ($edit_data && $edit_data['parent_id'] == $pm['id']) ? 'selected' : '' ?>>
                            <?= $pm['title'] ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sıra</label>
                    <input type="number" name="display_order" class="form-control"
                           value="<?= $edit_data['display_order'] ?? 0 ?>">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                           <?= (!$edit_data || $edit_data['is_active']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i><?= $edit_data ? 'Güncelle' : 'Ekle' ?>
                </button>
                <?php if ($edit_data): ?>
                <a href="menus" class="btn btn-secondary">İptal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-list me-2"></i>Tüm Menüler</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sıra</th>
                            <th>Başlık</th>
                            <th>URL</th>
                            <th>İkon</th>
                            <th>Hedef</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($menu = $menus->fetch_assoc()): ?>
                        <tr>
                            <td><?= $menu['display_order'] ?></td>
                            <td>
                                <?php if ($menu['parent_id'] > 0): ?>
                                <i class="fas fa-level-up-alt fa-rotate-90 text-muted me-1"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($menu['title']) ?>
                            </td>
                            <td><small><?= htmlspecialchars($menu['url']) ?></small></td>
                            <td>
                                <?php if ($menu['icon']): ?>
                                <i class="<?= $menu['icon'] ?>"></i>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary"><?= $menu['target'] ?></span></td>
                            <td>
                                <?php if ($menu['is_active']): ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Pasif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?edit=<?= $menu['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?= $menu['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bu menüyü silmek istediğinizden emin misiniz?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
