<?php $page_title='SSS Yönetimi'; include 'includes/header.php'; if(isset($_GET['action'])&&$_GET['action']=='delete')$conn->query("DELETE FROM faqs WHERE id=".(int)$_GET['id']); if($_SERVER['REQUEST_METHOD']=='POST'){$id=$_POST['id']??0; $q=sanitize($_POST['question']); $a=sanitize($_POST['answer']); $o=(int)$_POST['display_order']; $act=isset($_POST['is_active'])?1:0; $id>0?$conn->query("UPDATE faqs SET question='$q',answer='$a',display_order=$o,is_active=$act WHERE id=$id"):$conn->query("INSERT INTO faqs (question,answer,display_order,is_active) VALUES('$q','$a',$o,$act)"); echo alert('Kaydedildi!','success');}$ed=isset($_GET['edit'])?$conn->query("SELECT * FROM faqs WHERE id=".(int)$_GET['edit'])->fetch_assoc():null;?>
<div class="row"><div class="col-md-5"><div class="content-card"><h5><?php echo $ed?'Düzenle':'Yeni Ekle';?></h5>
<form method="POST"><?php if($ed):?><input type="hidden" name="id" value="<?=$ed['id']?>"><?php endif;?>
<div class="mb-3"><label>Soru*</label><input name="question" class="form-control" value="<?=$ed['question']??''?>" required></div>
<div class="mb-3"><label>Cevap*</label><textarea name="answer" class="form-control" rows="3" required><?=$ed['answer']??''?></textarea></div>
<div class="mb-3"><label>Sıra</label><input type="number" name="display_order" class="form-control" value="<?=$ed['display_order']??0?>"></div>
<div class="form-check mb-3"><input type="checkbox" name="is_active" class="form-check-input" <?=(!$ed||$ed['is_active'])?'checked':''?>>Aktif</div>
<button class="btn btn-primary">Kaydet</button> <?php if($ed):?><a href="faq.php" class="btn btn-secondary">İptal</a><?php endif;?></form></div></div>
<div class="col-md-7"><div class="content-card"><h5>SSS Listesi</h5><table class="table"><thead><tr><th>Soru</th><th>Sıra</th><th>İşlem</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT * FROM faqs ORDER BY display_order"); while($f=$r->fetch_assoc()):?>
<tr><td><?=htmlspecialchars($f['question'])?></td><td><?=$f['display_order']?></td><td><a href="?edit=<?=$f['id']?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="?action=delete&id=<?=$f['id']?>" class="btn btn-sm btn-danger delete-confirm"><i class="fas fa-trash"></i></a></td></tr>
<?php endwhile;?></tbody></table></div></div></div>
<?php include 'includes/footer.php';?>
