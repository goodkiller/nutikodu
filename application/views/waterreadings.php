<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nutikodu</title>
	<meta http-equiv="refresh" content="3600">

	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/js/lib/bootstrap/css/bootstrap.min.css">
	<script src="assets/js/lib/jquery.min.js"></script>
	<script src="assets/js/lib/tether.min.js"></script>
	<script src="assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
	<div class="row">
	
		<div class="col-sm-12">

			<h2>Salu tee 2-10 vesi</h2>
			<table class="table table-condensed table-bordered">
			<thead>
			<tr>
				<th rowspan="2">Kuupäev</th>
				<th colspan="3">Üldine vesi</th>
				<th colspan="3">Korter 1</th>
				<th colspan="3">Korter 2</th>
				<th rowspan="2">Vahe</th>
			</tr>
			<tr>
				<th>Näit</th>
				<th>Kulu</th>
				<th>Summa</th>
				<th>Näit</th>
				<th>Kulu</th>
				<th>Summa</th>
				<th>Näit</th>
				<th>Kulu</th>
				<th>Summa</th>
			</tr>
			</thead>
			<tbody>
			<?php


			foreach( $readings as $r )
			{
				if( $r->total_diff != 0 ){
					$total_diff_class = 'text-danger';
				}else{
					$total_diff_class = '';
				}

				?>
				<tr>
					<td><?php echo $r->eventdate; ?></td>
					<td align="right"><?php echo $r->wm_0; ?> m³</td>
					<td align="right"><?php echo $r->wm_0_diff; ?> m³</td>
					<td align="right"><?php echo $r->wm_0_diff_price; ?>€</td>
					<td align="right"><?php echo $r->wm_1; ?> m³</td>
					<td align="right"><?php echo $r->wm_1_diff; ?> m³</td>
					<td align="right"><?php echo $r->wm_1_diff_price; ?>€</td>
					<td align="right"><?php echo $r->wm_2; ?> m³</td>
					<td align="right"><?php echo $r->wm_2_diff; ?> m³</td>
					<td align="right"><?php echo $r->wm_2_diff_price; ?>€</td>
					<td align="right" class="<?php echo $total_diff_class; ?>"><?php echo $r->total_diff; ?> m³</td>
				</tr>
				<?php
			}

			?>
			</tbody>
			</table>

		</div>

	</div>
</div>
</body>
</html>