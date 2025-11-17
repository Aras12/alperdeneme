<?php $page_title='Yorumlar'; include 'includes/header.php'; if(isset($_GET['action'])&&$_GET['action']=='delete')$conn->query("DELETE FROM testimonials WHERE id=".(int)$_GET['id']); if($_SERVER['REQUEST_METHOD']=='POST'){$id=$_POST['id']??0; $n=sanitize($_POST['name']); $l=sanitize($_POST['location']); $r=(int)$_POST['rating']; $c=sanitize($_POST['comment']); $o=(int)$_POST['display_order']; $act=isset($_POST['is_active'])?1:0; $id>0?$conn->query("UPDATE testimonials SET name='$n',location='$l',rating=$r,comment='$c',display_order=$o,is_active=$act WHERE id=$id"):$conn->query("INSERT INTO testimonials (name,location,rating,comment,display_order,is_active) VALUES('$n','$l',$r,'$c',$o,$act)"); echo alert('Kaydedildi!','success');}$ed=isset($_GET['edit'])?$conn->query("SELECT * FROM testimonials WHERE id=".(int)$_GET['edit'])->fetch_assoc():null;?>
<div class="row"><div class="col-md-5"><div class="content-card"><h5><?php echo $ed?'Düzenle':'Yeni Yorum';?></h5>
<form method="POST"><?php if($ed):?><input type="hidden" name="id" value="<?=$ed['id']?>"><?php endif;?>
<div class="mb-3"><label>İsim*</label><input name="name" class="form-control" value="<?=$ed['name']??''?>" required></div>
<div class="mb-3"><label>Konum</label><input name="location" class="form-control" value="<?=$ed['location']??''?>"></div>
<div class="mb-3"><label>Puan</label><select name="rating" class="form-control"><option value="5" <?=($ed['rating']??5)==5?'selected':''?>>5</option><option value="4">4</option><option value="3">3</option></select></div>
<div class="mb-3"><label>Yorum*</label><textarea name="comment" class="form-control" rows="3" required><?=$ed['comment']??''?></textarea></div>
<div class="mb-3"><label>Sıra</label><input type="number" name="display_order" class="form-control" value="<?=$ed['display_order']??0?>"></div>
<div class="form-check mb-3"><input type="checkbox" name="is_active" class="form-check-input" <?=(!$ed||$ed['is_active'])?'checked':''?>>Aktif</div>
<button class="btn btn-primary">Kaydet</button> <?php if($ed):?><a href="testimonials.php" class="btn btn-secondary">İptal</a><?php endif;?></form></div></div>
<div class="col-md-7"><div class="content-card"><h5>Yorumlar</h5><table class="table"><tbody>
<?php $r=$conn->query("SELECT * FROM testimonials ORDER BY display_order"); while($t=$r->fetch_assoc()):?>
<tr><td><?=htmlspecialchars($t['name'])?> - <?=$t['location']?></td><td><a href="?edit=<?=$t['id']?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> <a href="?action=delete&id=<?=$t['id']?>" class="btn btn-sm btn-danger delete-confirm"><i class="fas fa-trash"></i></a></td></tr>
<?php endwhile;?></tbody></table></div></div></div>
<?php include 'includes/footer.php';?>
