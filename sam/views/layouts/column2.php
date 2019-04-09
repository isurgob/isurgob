<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-24">
	<div id="mainmenu">
	<?php
		//$this->beginWidget('zii.widgets.CPortlet', array(	'title'=>'Operations',));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			//'htmlOptions'=>array('class'=>'operations'),
		));
		//$this->endWidget();
	?>
	</div><!-- sidebar -->
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
</div>
<?php $this->endContent(); ?>