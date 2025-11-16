<?php
$page_title = 'Slider Yönetimi';
include 'includes/header.php';

// Handle actions
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM sliders WHERE id = $id");
    echo alert('Slider başarıyla silindi!', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $button_text = sanitize($_POST['button_text']);
    $button_link = sanitize($_POST['button_link']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle image upload
    $image = sanitize($_POST['image_url']); // Existing or URL
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadImage($_FILES['image_file'], 'slider');
        if ($uploaded) {
            $image = $uploaded;
        }
    }

    if ($id > 0) {
        // Update
        $sql = "UPDATE sliders SET title='$title', description='$description', image='$image',
                button_text='$button_text', button_link='$button_link', display_order=$display_order,
                is_active=$is_active WHERE id=$id";
    } else {
        // Insert
        $sql = "INSERT INTO sliders (title, description, image, button_text, button_link, display_order, is_active)
                VALUES ('$title', '$description', '$image', '$button_text', '$button_link', $display_order, $is_active)";
    }

    if ($conn->query($sql)) {
        echo alert($id > 0 ? 'Slider güncellendi!' : 'Slider eklendi!', 'success');
    }
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM sliders WHERE id = $edit_id")->fetch_assoc();
}

// Get all sliders
$sliders = $conn->query("SELECT * FROM sliders ORDER BY display_order ASC");
?>

<div class="row">
    <div class="col-md-5">
        <div class="content-card">
            <h5 class="mb-4">
                <?php echo $edit_data ? '<i class="fas fa-edit me-2"></i>Slider Düzenle' : '<i class="fas fa-plus me-2"></i>Yeni Slider Ekle'; ?>
            </h5>
            <form method="POST" action="" enctype="multipart/form-data">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" class="form-control"
                           value="<?php echo $edit_data['title'] ?? ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" rows="2"><?php echo $edit_data['description'] ?? ''; ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Görsel</label>
                    <?php if ($edit_data && $edit_data['image']): ?>
                    <div class="mb-2">
                        <img src="<?php echo $edit_data['image']; ?>" alt="Mevcut Görsel" style="max-width: 200px; max-height: 100px; object-fit: cover;" class="img-thumbnail">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image_file" class="form-control mb-2" accept="image/*">
                    <small class="text-muted d-block mb-2">Bilgisayarınızdan resim yükleyin (JPG, PNG, GIF, WEBP)</small>
                    <input type="url" name="image_url" class="form-control" placeholder="veya harici URL girin"
                           value="<?php echo $edit_data['image'] ?? ''; ?>">
                    <small class="text-muted">Ya dosya yükleyin ya da URL girin</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Buton Metni</label>
                    <input type="text" name="button_text" class="form-control"
                           value="<?php echo $edit_data['button_text'] ?? ''; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Buton Linki</label>
                    <input type="text" name="button_link" class="form-control"
                           value="<?php echo $edit_data['button_link'] ?? ''; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Sıra</label>
                    <input type="number" name="display_order" class="form-control"
                           value="<?php echo $edit_data['display_order'] ?? 0; ?>">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                           <?php echo (!$edit_data || $edit_data['is_active']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i><?php echo $edit_data ? 'Güncelle' : 'Ekle'; ?>
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="slider.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>İptal
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-list me-2"></i>Slider Listesi</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sıra</th>
                            <th>Başlık</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($slider = $sliders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $slider['display_order']; ?></td>
                                <td><?php echo htmlspecialchars($slider['title']); ?></td>
                                <td>
                                    <?php if ($slider['is_active']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-actions">
                                    <a href="?edit=<?php echo $slider['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $slider['id']; ?>"
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
    </div>
</div>

<?php include 'includes/footer.php'; ?>
