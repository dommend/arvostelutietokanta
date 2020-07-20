<link rel="stylesheet" href="../style/normalize.css">
<link rel="stylesheet" href="style/main-admin.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" src="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="http://dev.zurial.fi/arvostelu/admin/js/dataTables.conditionalPaging.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdn.tiny.cloud/1/v9uh0oidr4oqwy1lx71vkxtxjb2qfyl0c905ewdsad4sykcy/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> 


<script>
	$(document).ready(function() {
		$('table').DataTable({
			conditionalPaging: true,
			"pageLength": 10,
			"order": [
				[1, 'desc']
			],
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Finnish.json"
			}
		});
	});

	tinymce.init({
		selector: "textarea",
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table paste imagetools wordcount"
		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
	}); 
</script>