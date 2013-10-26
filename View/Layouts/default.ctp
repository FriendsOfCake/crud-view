<!DOCTYPE html>
<html>
<head>
	<?= $this->Html->charset(); ?>
	<title><?= $this->get('title');?></title>

	<?= $this->Html->css('CrudView.bootstrap');?>
	<?= $this->Html->css('CrudView.sb-admin');?>

	<?= $this->Html->meta('icon'); ?>
	<?= $this->fetch('meta'); ?>
	<?= $this->fetch('css'); ?>
</head>
<body>
	<div id="wrapper">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.html">Crud View v0.1.0</a>
			</div>

			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<?= $this->element('sidebar'); ?>
				<?= $this->element('topbar'); ?>
			</div>
		</nav>

		<div id="page-wrapper">
			<?= $this->Session->flash(); ?>
			<?= $this->fetch('content'); ?>
		</div>
	</div>

	<?= $this->Html->script('CrudView.jquery');?>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<?= $this->fetch('script'); ?>
</body>
</html>
