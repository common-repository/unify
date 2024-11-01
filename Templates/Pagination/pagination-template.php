<div class="container-fluid unify-table-pagination p-0">
	<div class="row">
		<div class="col">
			<ul class="pagination">
				<li  class="<?php echo ($prev_dis) ? 'disabled' : ''; ?>">
					<a href="<?php echo (($prev_dis) ? 'javascript:void(0)' : (!empty($_GET['paged']) ? esc_html(str_replace('paged=' . esc_html($paged), "paged=" . (esc_html($paged) - 1), esc_html(add_query_arg( NULL, NULL )))) :  esc_html(add_query_arg( NULL, NULL )). "&paged=" . (esc_html($paged) - 1))); ?>" >
						Prev
					</a>
				</li>
				<?php
				for ($i = 1; $i <= $total; $i++)
				{
					if($i < 3 || ($total - 2) < $i || $total < 7 || ($paged + 1) == $i || ($paged - 1) == $i || $paged == $i)
					{
					?>				
					<li class="<?php echo (($paged == $i) ? 'active' : '') ?>" >
						<a href="<?php echo (!empty($_GET['paged']) ? esc_html(str_replace('paged=' . esc_html($paged), "paged=" . esc_html($i), esc_html(add_query_arg( NULL, NULL )))) :  esc_html(add_query_arg( NULL, NULL )). "&paged=" .esc_html($i)); ?>" >
							<?php echo esc_html($i); ?>
						</a>
					</li>
				<?php 				
					}
				}
					?>
				<li  class="<?php echo ($next_dis) ? 'disabled' : ''; ?>">
					<a href="<?php echo (($next_dis) ? 'javascript:void(0)' : (!empty($_GET['paged']) ? esc_html(str_replace('paged=' . esc_html($paged), "paged=" . (esc_html($paged) + 1), esc_html(add_query_arg( NULL, NULL )))) :  esc_html(add_query_arg( NULL, NULL )). "&paged=" . (esc_html($paged) + 1))); ?>" >
						Next
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>