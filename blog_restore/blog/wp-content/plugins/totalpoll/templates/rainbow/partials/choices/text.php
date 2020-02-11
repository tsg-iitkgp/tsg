<?php
if (defined('ABSPATH') === false) :
	exit;
endif; // Shhh
?>

<div class="totalpoll-choice-label">
	<?php
	if ($this->current === 'results'):
		include dirname(__FILE__) . '/../results/bar.php';
	endif;
	?>
	<span><?php echo esc_attr($choice['content']['label']); ?></span>
	<?php
	if ($this->current === 'results'):
		include dirname(__FILE__) . '/../results/text.php';
	endif;
	?>
</div>