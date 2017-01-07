<div class="List-Footer-Controls clearfix">

	<div class="Page-Indicator">
		Pagina <span class="b"><?php echo $intPage; ?></span> din <?php echo $intPages; ?>
	</div>
	
	<div class="paginari">
	
		<div class="List-Pagination">
			<?php if ($intPage != $intFirstPage) { ?>
				<a href="<?php echo $strPrevUrl; ?>" class="paged prev-pg">Anterioara</a>
			<?php } ?>
			
			<?php if (isset($arrPageGroups)) { 
			
					$intLastDisplayedPage = 1;
					foreach ($arrPageGroups as $intGroupId => $arrPageGroup){
						if ($intGroupId - 1 != $intLastDisplayedPage && $intGroupId != 1){
							?><span class="page_group_action">...</span><?php 
						}
						foreach ($arrPageGroup['pages'] as $intPageNr => $strPageUrl){
							$intLastDisplayedPage = $intPageNr;  
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
						 		
					}
					
				  } else { 
					?>
					<span class="page_group_holder">
					<?php 
					for ($i = 1; $i <= $intPages; $i++){
						if ($i == $intPage){
							?>
							<a href="<?php echo $strUrlBase . $i; ?>" class="on-page">
								<?php echo $i; ?>
							</a>
							<?php 
						} else {
							?>
							<a href="<?php echo $strUrlBase . $i; ?>">
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
			
			
			
			<div class="Page-Jump-Small" id="jump-small" >
	              <form action="<?php echo $strUrlBase; ?>" method="post" class="paginare3" onsubmit="if($j('<?php echo '#'.$strPageParam; ?>').val() < 1 || $j('#<?php echo $strPageParam; ?>').val() > <?php echo $intPages; ?>) return false;">
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