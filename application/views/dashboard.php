<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nutikodu</title>

	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/js/lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="assets/css/devices/MultilevelSwitch.css" />
	<link rel="stylesheet" href="assets/css/devices/MiLight.css" />
	<link rel="stylesheet" href="assets/css/devices/Battery.css" />
	<link rel="stylesheet" href="assets/css/devices/AccuWeather.css" />
	<link rel="stylesheet" href="assets/css/devices/BinarySensor.css" />
	<link rel="stylesheet" href="assets/css/devices/PioneerVSX.css" />
	<script src="assets/js/lib/jquery.min.js"></script>
	<script src="assets/js/lib/jquery.mobile.min.js"></script>
	<script src="assets/js/lib/tether.min.js"></script>
	<script src="assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/lib/isotope.pkgd.min.js"></script>
	<script src="assets/js/lib/jquery.debouncedresize.js"></script>
	<script src="assets/js/dashboard.js"></script>
	<script src="assets/js/dialog.js"></script>
	<script src="assets/js/command.js"></script>
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="grid"></div>
	</div>
</div>

<!-- Settings -->
<div id="item_settings" class="modal fade">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<img src="assets/img/hourglass.gif" class="img-fluid mx-auto d-block" alt="Laadin ...">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tühista</button>
				<button type="button" class="btn btn-primary">Salvesta</button>
			</div>
		</div>
	</div>
</div>

<!-- Toggle -->
<div id="item_toggle" class="modal fade">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<img src="assets/img/hourglass.gif" class="img-fluid mx-auto d-block" alt="Laadin ...">
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
$(function(){
	Dashboard.init( '.grid' );
});
</script>
</body>
</html>