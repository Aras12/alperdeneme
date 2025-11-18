<?php
$page_title = 'Hizmetler';
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
    $conn->query("DELETE FROM services WHERE id = " . (int)$_GET['id']);
    echo alert('Hizmet silindi!', 'success');
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug']);
    $short_description = $conn->real_escape_string(trim($_POST['short_description']));
    $content = $conn->real_escape_string($_POST['content']);
    $icon = sanitize($_POST['icon']);
    $meta_title = $conn->real_escape_string(trim($_POST['meta_title']));
    $meta_description = $conn->real_escape_string(trim($_POST['meta_description']));
    $meta_keywords = sanitize($_POST['meta_keywords']);
    $canonical_url = sanitize($_POST['canonical_url']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle image upload
    $image = sanitize($_POST['image_url']);
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadImage($_FILES['image_file'], 'service');
        if ($uploaded) {
            $image = $uploaded;
        }
    }

    if ($id > 0) {
        $sql = "UPDATE services SET title='$title', slug='$slug', short_description='$short_description',
                content='$content', icon='$icon', image='$image', meta_title='$meta_title',
                meta_description='$meta_description', meta_keywords='$meta_keywords',
                canonical_url='$canonical_url', display_order=$display_order, is_active=$is_active WHERE id=$id";
    } else {
        $sql = "INSERT INTO services (title, slug, short_description, content, icon, image, meta_title,
                meta_description, meta_keywords, canonical_url, display_order, is_active)
                VALUES ('$title', '$slug', '$short_description', '$content', '$icon', '$image', '$meta_title',
                '$meta_description', '$meta_keywords', '$canonical_url', $display_order, $is_active)";
    }

    if ($conn->query($sql)) {
        $_SESSION['success_message'] = $id > 0 ? 'Hizmet güncellendi!' : 'Hizmet eklendi!';
        redirect('services.php');
    } else {
        $_SESSION['error_message'] = 'Hata: ' . $conn->error;
    }
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_data = $conn->query("SELECT * FROM services WHERE id = " . (int)$_GET['edit'])->fetch_assoc();
}

// List or edit form
if ($edit_data || isset($_GET['new'])):
?>

<div class="content-card">
    <h5 class="mb-4">
        <i class="fas fa-<?php echo $edit_data ? 'edit' : 'plus'; ?> me-2"></i>
        <?php echo $edit_data ? 'Hizmet Düzenle' : 'Yeni Hizmet Ekle'; ?>
    </h5>
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" class="form-control"
                           value="<?php echo $edit_data['title'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Slug (URL) *</label>
                    <input type="text" name="slug" class="form-control"
                           value="<?php echo $edit_data['slug'] ?? ''; ?>" required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Kısa Açıklama</label>
            <textarea name="short_description" class="form-control" rows="2"><?php echo $edit_data['short_description'] ?? ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">İçerik *</label>
            <textarea name="content" class="form-control summernote"><?php echo $edit_data['content'] ?? ''; ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">İkon (FontAwesome)</label>
                    <input type="text" name="icon" class="form-control"
                           value="<?php echo $edit_data['icon'] ?? 'fas fa-wrench'; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Görsel</label>
                    <?php if ($edit_data && $edit_data['image']): ?>
                    <div class="mb-2">
                        <img src="<?php echo $edit_data['image']; ?>" alt="Mevcut Görsel" style="max-width: 150px; max-height: 100px; object-fit: cover;" class="img-thumbnail">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image_file" class="form-control mb-2" accept="image/*">
                    <input type="url" name="image_url" class="form-control" placeholder="veya harici URL"
                           value="<?php echo $edit_data['image'] ?? ''; ?>">
                </div>
            </div>
        </div>

        <hr>
        <h6>SEO Ayarları</h6>

        <div class="mb-3">
            <label class="form-label">Meta Başlık</label>
            <input type="text" name="meta_title" class="form-control"
                   value="<?php echo $edit_data['meta_title'] ?? ''; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Meta Açıklama</label>
            <textarea name="meta_description" class="form-control" rows="2"><?php echo $edit_data['meta_description'] ?? ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-control"
                   value="<?php echo $edit_data['meta_keywords'] ?? ''; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Canonical URL</label>
            <input type="url" name="canonical_url" class="form-control"
                   value="<?php echo $edit_data['canonical_url'] ?? ''; ?>">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Sıra</label>
                    <input type="number" name="display_order" class="form-control"
                           value="<?php echo $edit_data['display_order'] ?? 0; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 form-check mt-4">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                           <?php echo (!$edit_data || $edit_data['is_active']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i><?php echo $edit_data ? 'Güncelle' : 'Ekle'; ?>
            </button>
            <a href="services.php" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>İptal
            </a>
        </div>
    </form>
</div>

<?php else:
$services = $conn->query("SELECT * FROM services ORDER BY display_order ASC");
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Hizmet Listesi</h5>
        <a href="?new=1" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Yeni Hizmet
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>Başlık</th>
                    <th>Slug</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $services->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $service['display_order']; ?></td>
                        <td><?php echo htmlspecialchars($service['title']); ?></td>
                        <td><code><?php echo $service['slug']; ?></code></td>
                        <td>
                            <span class="badge bg-<?php echo $service['is_active'] ? 'success' : 'secondary'; ?>">
                                <?php echo $service['is_active'] ? 'Aktif' : 'Pasif'; ?>
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="?edit=<?php echo $service['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $service['id']; ?>"
                               class="btn btn-sm btn-danger delete-confirm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
