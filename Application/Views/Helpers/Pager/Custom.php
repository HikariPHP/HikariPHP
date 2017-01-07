<!-- pagination -->
<div class="tf_pagination">
	<div class="inner">

		<?php if ($intPage != $intFirstPage) { ?>
			<a href="<?php echo $strPrevUrl; ?>" class="page_prev"><span></span>Back</a>
		<?php } ?>

		<?php

		for ($i = 1; $i <= $intPages; $i++){
			if ($i == $intPage){
				?>
				<span class="page-numbers page_current"><?php echo $i; ?></span>

			<?php
			} else {
				?>
				<a class="page-numbers" href="<?php echo $strUrlBase . $i; ?>">
					<?php echo $i; ?>
				</a>
			<?php
			}
		}
		?>

		<?php if ($intPages != $intPage) { ?>
			<a class="page_next" href="<?php echo $strNextUrl; ?>"><span></span>Forward</a>
		<?php } ?>



	</div>
</div>
<!--/ pagination -->