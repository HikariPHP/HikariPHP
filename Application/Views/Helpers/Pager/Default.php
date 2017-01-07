<div class="List-Footer-Controls clearfix">

	<div class="Page-Indicator">
		Pagina <strong><?php echo $intPage; ?></strong> din <?php echo $intPages; ?>
	</div>
	
	<div class="paginari">
	
		<div class="List-Pagination">
			<?php if ($intPage != $intFirstPage) { ?>
				<a href="<?php echo $strPrevUrl; ?>" class="paged prev-pg">Anterioara</a>
			<?php } ?>
			
			<?php if (isset($arrPageGroups)) { 
			
					foreach ($arrPageGroups as $intGroupId => $arrPageGroup){
						?>
						<span 
							class="page_group_action" 
							id="pager_group_<?php echo $intGroupId; ?>_toggle"
							style="display: <?php echo $arrPageGroup['visible'] ? 'none' : 'inline'; ?>"
						>
							<a 
								href="#"
								onclick="$('.page_group_holder').hide();
										 $('.page_group_action').show();
										 $('#pager_group_<?php echo $intGroupId; ?>').show();
										 $('#pager_group_<?php echo $intGroupId; ?>_toggle').hide();
										 return false;
										"
							>
							<?php echo $intGroupId; ?>..
							</a>
						</span>
						
						<span
							class="page_group_holder"
							id="pager_group_<?php echo $intGroupId; ?>"
							style="display:<?php echo $arrPageGroup['visible']? 'inline' : 'none'; ?>"
						>
							<?php 
							foreach ($arrPageGroup['pages'] as $intPageNr => $strPageUrl){  
								if ($intPage == $intPageNr){
									?>
									<a href="<?php echo $strPageUrl; ?>" class="on-page">
										<?php echo $intPageNr; ?>
									</a>
									<?php 
								} else {
									?>
									<a href="<?php echo $strPageUrl; ?>">
										<?php echo $intPageNr; ?>
									</a>
									<?php 
								}
							} 
							?>
						</span>
						<?php 		
					}
					
				  } else { 
					?>
					<span class="page_group_holder">
					<?php 
					for ($i = 1; $i <= $intPages; $i++){
						if ($i == $intPage){
							?>
							<a href="<?php echo $strBaseUrl . $i; ?>" class="on-page">
								<?php echo $i; ?>
							</a>
							<?php 
						} else {
							?>
							<a href="<?php echo $strBaseUrl . $i; ?>">
								<?php echo $i; ?>
							</a>
							<?php 
						}
					}
					?>
					</span>
					<?php 
				 } 
			?>
			
			<?php if ($intPages != $intPage) { ?>
				<a href="<?php echo $strNextUrl; ?>" class="paged next-pg">Urmatoare</a>
			<?php } ?>
			
			<a href="javascript:;" title="Mergi la pagina" class="jump-to" onclick="$('#jump').toggle();$('.jump-to').toggleClass('visible')">Mergi la pagina</a>
			
			<div class="Page-Jump" id="jump" style="display:none;">
	              <form action="" method="post" class="paginare3">
	                <label>Mergi la pagina</label>
	                <input type="text" class="text" name="<?php echo $strPageParam; ?>" id='<?php echo $strPageParam; ?>' />
	                <input type="submit" value="" class="Go" />
	              </form>
	        </div> <!-- .Page-Jump -->
		</div><!-- .List-Pagination -->
	
	</div>
	
	<div class="View-Counter">
		Pe pagina:
		
		<?php 
		foreach ($arrPerPage as $intPerPage => $arrPerPageItem) {
			if ($intPerPageSelected == $intPerPage){
				?>
				<span class="accentuat"><?php echo $arrPerPageItem['label']; ?></span>
				<?php 
			} else {
				?>
				<a
					href="#"
					onclick="window.location='<?php echo $arrPerPageItem['url']; ?>'; return false;"
					rel="nofollow"
				>
					<?php echo $arrPerPageItem['label']; ?>
				</a>
				<?php 
			}
		}
		?>
		
	</div>

</div> <!-- .List-Footer-Controls clearfix -->