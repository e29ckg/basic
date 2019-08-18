<table class="table table-striped table-bordered" width="100%">
				<thead>
					<tr>
						<!-- <th data-class="expand" width="10%" align="center"> # </th> -->
						<th width="10%">img</th>
						<th data-hide="phone" width="80%">Link</th>		
					</tr>
				</thead>
				<tbody>                             
					<?php foreach ($models as $model): ?>
					<tr >
						<!-- <td align="center"><?= $i++?></td> -->
						<td class="img-weblink" >
							<a href="#"><img src="<?= Url::to('@web'.WebLink::getImg($model->id)) ?>" alt="Smiley face" data-id= "<?=$model->id?>" class = "act-show img"></a>
							<br>
							<?php // echo '<a href="'.$model->link.'" target="_blank">'.$model->name.'</a>'?>
						</td>
						
						<td>
						<?php  echo '<a href="'.$model->link.'" target="_blank">'.$model->name.'</a>'?>
						
						<?php 
								// $modelFiles = $model->getWebLinkFile()->orderBy(['sort'=>SORT_ASC,'id' => SORT_ASC])->all();
								// $modelFiles = WebLinkFile::find()->where(['web_link_id'=>$model->id])->orderBy(['sort'=>SORT_ASC,'id' => SORT_ASC])->all(); 
								echo '<ul>';
								// echo var_dump($model->webLinkFile);
								foreach ($model->webLinkFile as $modelFile):
									// echo $modelFile->name;
									echo '<li>';
									if($modelFile->type =='url'){
										echo '<a href="'.Url::to($modelFile->url).'"  target="_blank">'.$modelFile->name.'</a> ';														
									}else{
									echo '<a href="'.Url::to('@web/uploads/weblink/'.$model->id.'/'.$modelFile->file).'"  target="_blank">'.$modelFile->name.'.'.$modelFile->type.'</a> ';
									}		
									echo '</li>';
								endforeach;
								echo '</ul>';
								// echo var_dump($modelFiles);
							?>
							</td>			        
					</tr>
					<?php  endforeach; ?>
				</tbody>	
			</table>
<?= var_dump($models)?>

