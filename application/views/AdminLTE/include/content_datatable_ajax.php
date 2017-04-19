<table id="example" class="display" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Name</th>
			<th>Position</th>
			<th>Office</th>
			<th>Extn.</th>
			<th>Start date</th>
			<th>Salary</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Name</th>
			<th>Position</th>
			<th>Office</th>
			<th>Extn.</th>
			<th>Start date</th>
			<th>Salary</th>
		</tr>
	</tfoot>
</table>
<link type="text/css" rel="stylesheet" href="/assets/jquery_plugind/DataTables-1.10.13/media/css/dataTables.jqueryui.min.css" />
<script src="/assets/jquery_plugind/DataTables-1.10.13/media/js/jquery.dataTables.min.js"></script>
<script src="/assets/jquery_plugind/DataTables-1.10.13/media/js/dataTables.jqueryui.min.js"></script>
<div class="javascript_workspace"></div>
<script type="text/javascript">
$(document).ready(function() {
	$('#example tfoot th').each(function(){
		var title = $(this).text();
		$(this).html('<input type="text" placeholder="Search '+title+'" />');
	});
	var table = $('#example').DataTable({
		"lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
		"scrollX": true,
		"ajax": '/backend/orders/datatable_api',
		"language": {
			"zeroRecords": "塞選無資料"
		}
	});
	table.columns().every(function(){
		var that = this;
		$( 'input', this.footer() ).on( 'keyup change', function(){
			if ( that.search() !== this.value ){
				that.search( this.value ).draw();
			}
		});
	});
});
</script>
