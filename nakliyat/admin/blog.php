<?php
$page_title = 'Blog Yönetimi';
include 'includes/header.php';

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $conn->query("DELETE FROM blog_posts WHERE id = " . (int)$_GET['id']);
    echo alert('Blog yazısı silindi!', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug']);
    $excerpt = sanitize($_POST['excerpt']);
    $content = $_POST['content'];
    $meta_title = sanitize($_POST['meta_title']);
    $meta_description = sanitize($_POST['meta_description']);
    $meta_keywords = sanitize($_POST['meta_keywords']);
    $canonical_url = sanitize($_POST['canonical_url']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle image upload
    $image = sanitize($_POST['image_url']);
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadImage($_FILES['image_file'], 'blog');
        if ($uploaded) {
            $image = $uploaded;
        }
    }

    if ($id > 0) {
        $sql = "UPDATE blog_posts SET title='$title', slug='$slug', excerpt='$excerpt', content='$content', image='$image', meta_title='$meta_title', meta_description='$meta_description', meta_keywords='$meta_keywords', canonical_url='$canonical_url', is_active=$is_active WHERE id=$id";
    } else {
        $sql = "INSERT INTO blog_posts (title, slug, excerpt, content, image, meta_title, meta_description, meta_keywords, canonical_url, is_active) VALUES ('$title', '$slug', '$excerpt', '$content', '$image', '$meta_title', '$meta_description', '$meta_keywords', '$canonical_url', $is_active)";
    }

    if ($conn->query($sql)) {
        echo alert($id > 0 ? 'Blog güncellendi!' : 'Blog eklendi!', 'success');
    }
}

$edit_data = isset($_GET['edit']) ? $conn->query("SELECT * FROM blog_posts WHERE id = " . (int)$_GET['edit'])->fetch_assoc() : null;

if ($edit_data || isset($_GET['new'])):
?>
<div class="content-card">
    <h5 class="mb-4"><i class="fas fa-<?php echo $edit_data ? 'edit' : 'plus'; ?> me-2"></i><?php echo $edit_data ? 'Blog Düzenle' : 'Yeni Blog'; ?></h5>
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($edit_data): ?><input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>"><?php endif; ?>
        <div class="row">
            <div class="col-md-6 mb-3"><label>Başlık *</label><input type="text" name="title" class="form-control" value="<?php echo $edit_data['title'] ?? ''; ?>" required></div>
            <div class="col-md-6 mb-3"><label>Slug (URL) *</label><input type="text" name="slug" class="form-control" value="<?php echo $edit_data['slug'] ?? ''; ?>" required></div>
        </div>
        <div class="mb-3"><label>Özet</label><textarea name="excerpt" class="form-control" rows="2"><?php echo $edit_data['excerpt'] ?? ''; ?></textarea></div>
        <div class="mb-3"><label>İçerik *</label><textarea name="content" class="form-control summernote"><?php echo $edit_data['content'] ?? ''; ?></textarea></div>
        <div class="mb-3">
            <label>Görsel</label>
            <?php if ($edit_data && $edit_data['image']): ?>
            <div class="mb-2">
                <img src="<?php echo $edit_data['image']; ?>" alt="Mevcut Görsel" style="max-width: 200px; max-height: 150px; object-fit: cover;" class="img-thumbnail">
            </div>
            <?php endif; ?>
            <input type="file" name="image_file" class="form-control mb-2" accept="image/*">
            <input type="url" name="image_url" class="form-control" placeholder="veya harici URL girin" value="<?php echo $edit_data['image'] ?? ''; ?>">
        </div>
        <hr><h6>SEO Ayarları</h6>
        <div class="mb-3"><label>Meta Başlık</label><input type="text" name="meta_title" class="form-control" value="<?php echo $edit_data['meta_title'] ?? ''; ?>"></div>
        <div class="mb-3"><label>Meta Açıklama</label><textarea name="meta_description" class="form-control" rows="2"><?php echo $edit_data['meta_description'] ?? ''; ?></textarea></div>
        <div class="mb-3"><label>Meta Keywords</label><input type="text" name="meta_keywords" class="form-control" value="<?php echo $edit_data['meta_keywords'] ?? ''; ?>"></div>
        <div class="mb-3"><label>Canonical URL</label><input type="url" name="canonical_url" class="form-control" value="<?php echo $edit_data['canonical_url'] ?? ''; ?>"></div>
        <div class="form-check mb-3"><input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?php echo (!$edit_data || $edit_data['is_active']) ? 'checked' : ''; ?>><label class="form-check-label" for="is_active">Aktif</label></div>
        <div class="d-flex gap-2"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i><?php echo $edit_data ? 'Güncelle' : 'Ekle'; ?></button><a href="blog.php" class="btn btn-secondary"><i class="fas fa-times me-2"></i>İptal</a></div>
    </form>
</div>
<?php else: ?>
<div class="content-card">
    <div class="d-flex justify-content-between mb-4"><h5><i class="fas fa-list me-2"></i>Blog Listesi</h5><a href="?new=1" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Yeni Blog</a></div>
    <table class="table table-hover"><thead><tr><th>Başlık</th><th>Slug</th><th>Durum</th><th>İşlem</th></tr></thead><tbody>
    <?php $posts = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC"); while ($post = $posts->fetch_assoc()): ?>
        <tr><td><?php echo htmlspecialchars($post['title']); ?></td><td><code><?php echo $post['slug']; ?></code></td><td><span class="badge bg-<?php echo $post['is_active'] ? 'success' : 'secondary'; ?>"><?php echo $post['is_active'] ? 'Aktif' : 'Pasif'; ?></span></td>
        <td><a href="?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="?action=delete&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger delete-confirm"><i class="fas fa-trash"></i></a></td></tr>
    <?php endwhile; ?></tbody></table>
</div>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
