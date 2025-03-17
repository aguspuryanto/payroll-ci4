<!DOCTYPE html>
<html>
<head>
	<title>Laporan <?= ucfirst($laporan) ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/cetak.css'); ?>">
</head>
<body>
	<div class='text-center'>
		<p class="d-print-none text-danger">CETAK PADA KERTA DENGAN POSISI LANDSCAPE</p>
	<?php		
		echo "<page size='A4-landscape'>";
		echo "<table class='table table-bordered table-hover table-sm'>
						<thead>
							".$header."
						</thead>
						<tbody class='small'>
              ".$body."
        		</tbody>
        		<tfoot class='small'>
        			".(isset($foot) ? $foot : "")."
        		</tfoot>
				</table>";
		echo "</page>";
	?>
	</div>
</body>
</html>