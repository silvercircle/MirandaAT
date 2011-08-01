<?php $options = get_option('blocks2_options'); 
    global $sidebar_style, $display_sidebar;
?>
</div>
</td>
<td style="padding-right:5px;vertical-align:top;<?php echo $sidebar_style;?>">
<!-- sidebar START -->
<?php if($display_sidebar != '0') : ?>
<aside>
<div id="sidebar">
	<div class="widget">
	</div>
	<?php dynamic_sidebar(); ?>
</div>
</aside>
<?php endif; ?>
<!-- sidebar END -->
</td></tr></table>
<!-- content table wrapper end -->
</div>