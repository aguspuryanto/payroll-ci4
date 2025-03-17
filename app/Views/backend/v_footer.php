</div>
<p></p>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="<?= base_url('assets/js/priceFormat/jquery.priceformat.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/tinymce/tinymce.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url('assets/js/jquery.toast.min.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript" src="<?= base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css"> 
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.js"></script>

<script type="text/javascript">
<?php  
if(isset($jquery)) echo $jquery;

if(isset($toast)) {
	switch($toast['head']) {
		case "Berhasil": $icon = "success"; break;
		case "Error": $icon = "error"; break;
	}
	echo "
		  $.toast({
		    heading: '".$toast['head']."',
		    text: '".$toast['text']."',
		    showHideTransition: 'slide',
		    icon: '$icon'
			});
	";
}
?>
</script>
</body>
</html>