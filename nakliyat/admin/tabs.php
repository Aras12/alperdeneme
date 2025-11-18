<?php
$page_title = 'Tab İçerikleri';
include 'includes/header.php';

// Display session messages
if (isset($_SESSION['success_message'])) {
    echo alert($_SESSION['success_message'], 'success');
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo alert($_SESSION['error_message'], 'danger');
    unset($_SESSION['error_message']);
}

// Handle delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $conn->query("DELETE FROM tabs WHERE id=" . (int)$_GET['id']);
    $_SESSION['success_message'] = 'Tab silindi!';
    redirect('tabs.php');
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug']);
    $content = $conn->real_escape_string($_POST['content']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0) {
        $conn->query("UPDATE tabs SET title='$title', slug='$slug', content='$content', display_order=$display_order, is_active=$is_active WHERE id=$id");
        $_SESSION['success_message'] = 'Tab güncellendi!';
    } else {
        $conn->query("INSERT INTO tabs (title, slug, content, display_order, is_active) VALUES('$title', '$slug', '$content', $display_order, $is_active)");
        $_SESSION['success_message'] = 'Tab eklendi!';
    }
    redirect('tabs.php');
}

// Get edit data
$edit_data = isset($_GET['edit']) ? $conn->query("SELECT * FROM tabs WHERE id=" . (int)$_GET['edit'])->fetch_assoc() : null;

if ($edit_data || isset($_GET['new'])):
?>
<div class="content-card">
    <h5 class="mb-4">
        <i class="fas fa-<?php echo $edit_data ? 'edit' : 'plus'; ?> me-2"></i>
        <?php echo $edit_data ? 'Tab Düzenle' : 'Yeni Tab'; ?>
    </h5>
    <form method="POST">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Başlık *</label>
                <input name="title" class="form-control" value="<?php echo $edit_data['title'] ?? ''; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Slug *</label>
                <input name="slug" class="form-control" value="<?php echo $edit_data['slug'] ?? ''; ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">İçerik *</label>
            <textarea name="content" class="form-control summernote"><?php echo $edit_data['content'] ?? ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Sıra</label>
            <input type="number" name="display_order" class="form-control" value="<?php echo $edit_data['display_order'] ?? 0; ?>">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                   <?php echo (!$edit_data || $edit_data['is_active']) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">
                <i class="fas fa-save me-2"></i><?php echo $edit_data ? 'Güncelle' : 'Ekle'; ?>
            </button>
            <a href="tabs.php" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>İptal
            </a>
        </div>
    </form>
</div>
<?php else: ?>
<div class="content-card">
    <div class="d-flex justify-content-between mb-4">
        <h5><i class="fas fa-list me-2"></i>Tab Listesi</h5>
        <a href="?new=1" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Yeni Tab
        </a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Başlık</th>
                <th>Slug</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM tabs ORDER BY display_order");
            while ($tab = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($tab['title']); ?></td>
                <td><code><?php echo $tab['slug']; ?></code></td>
                <td>
                    <span class="badge bg-<?php echo $tab['is_active'] ? 'success' : 'secondary'; ?>">
                        <?php echo $tab['is_active'] ? 'Aktif' : 'Pasif'; ?>
                    </span>
                </td>
                <td>
                    <a href="?edit=<?php echo $tab['id']; ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="?action=delete&id=<?php echo $tab['id']; ?>" class="btn btn-sm btn-danger delete-confirm">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
