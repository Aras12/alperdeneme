<?php $page_title='Tab İçerikleri'; include 'includes/header.php'; if(isset($_GET['action'])&&$_GET['action']=='delete')$conn->query("DELETE FROM tabs WHERE id=".(int)$_GET['id']); if($_SERVER['REQUEST_METHOD']=='POST'){$id=$_POST['id']??0; $t=sanitize($_POST['title']); $s=sanitize($_POST['slug']); $c=$_POST['content']; $o=(int)$_POST['display_order']; $act=isset($_POST['is_active'])?1:0; $id>0?$conn->query("UPDATE tabs SET title='$t',slug='$s',content='$c',display_order=$o,is_active=$act WHERE id=$id"):$conn->query("INSERT INTO tabs (title,slug,content,display_order,is_active) VALUES('$t','$s','$c',$o,$act)"); echo alert('Kaydedildi!','success');}$ed=isset($_GET['edit'])?$conn->query("SELECT * FROM tabs WHERE id=".(int)$_GET['edit'])->fetch_assoc():null; if($ed||isset($_GET['new'])):?>
<div class="content-card"><h5><?php echo $ed?'Düzenle':'Yeni Tab';?></h5>
<form method="POST"><?php if($ed):?><input type="hidden" name="id" value="<?=$ed['id']?>"><?php endif;?>
<div class="row"><div class="col-md-6 mb-3"><label>Başlık*</label><input name="title" class="form-control" value="<?=$ed['title']??''?>" required></div>
<div class="col-md-6 mb-3"><label>Slug*</label><input name="slug" class="form-control" value="<?=$ed['slug']??''?>" required></div></div>
<div class="mb-3"><label>İçerik*</label><textarea name="content" class="form-control summernote"><?=$ed['content']??''?></textarea></div>
<div class="mb-3"><label>Sıra</label><input type="number" name="display_order" class="form-control" value="<?=$ed['display_order']??0?>"></div>
<div class="form-check mb-3"><input type="checkbox" name="is_active" class="form-check-input" <?=(!$ed||$ed['is_active'])?'checked':''?>>Aktif</div>
<button class="btn btn-primary">Kaydet</button> <a href="tabs.php" class="btn btn-secondary">İptal</a></form></div>
<?php else:?>
<div class="content-card"><div class="d-flex justify-content-between mb-4"><h5>Tab Listesi</h5><a href="?new=1" class="btn btn-primary">Yeni Tab</a></div><table class="table"><tbody>
<?php $r=$conn->query("SELECT * FROM tabs ORDER BY display_order"); while($t=$r->fetch_assoc()):?>
<tr><td><?=htmlspecialchars($t['title'])?></td><td><a href="?edit=<?=$t['id']?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="?action=delete&id=<?=$t['id']?>" class="btn btn-sm btn-danger delete-confirm"><i class="fas fa-trash"></i></a></td></tr>
<?php endwhile;?></tbody></table></div>
<?php endif; include 'includes/footer.php';?>
