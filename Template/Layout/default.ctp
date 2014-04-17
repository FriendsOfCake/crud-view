<!DOCTYPE html>
<html lang="en">
<head>
	<?= $this->Html->charset(); ?>
	<title><?= $this->get('title');?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Base CSS -->
	<?= $this->Html->css('CrudView.bootstrap.min');?>
	<?= $this->Html->css('/bootflat/css/bootflat.min');?>
	<?= $this->Html->css('CrudView.local');?>

	<!-- Base JS -->
	<?= $this->Html->script('CrudView.jquery.min');?>
	<?= $this->Html->script('CrudView.bootstrap.min');?>

	<!-- Moment.js -->
	<?= $this->Html->script('CrudView./contrib/momentjs/js/moment-with-langs.min');?>

	<!-- DateTime picker -->
	<?= $this->Html->css('CrudView./contrib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min');?>
	<?= $this->Html->script('CrudView./contrib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min');?>

	<?= $this->Html->meta('icon'); ?>
	<?= $this->fetch('meta'); ?>
	<?= $this->fetch('css'); ?>
</head>
<body>
	<nav class="navbar navbar-default navbar-custom" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">Crud View v0.1.0</a>
			</div>

			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<?= $this->element('sidebar'); ?>
			</div>
		</div>
	</nav>

	<div class="bs-docs-header" id="content">
		<div class="container">
			<?= $this->Session->flash(); ?>
			<?= $this->fetch('content'); ?>
		</div>
	</div>

	<?= $this->fetch('script'); ?>
</body>
</html>
