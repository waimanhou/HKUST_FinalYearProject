<?php require_once($_SERVER['DOCUMENT_ROOT'].'/fyp/requires.php');?>
	<div data-role="footer" style="position:absolute;bottom:0;font-size:8;">
		<?php echo COPY_RIGHT ?>
	</div>
</div>
</div>
</body>
<script type="text/javascript">
	function convertDateTime(eid){
		var info = $('[id^="'+eid+'"]').map(
			function () {
				var myDate = new Date($(this).html());
				myDate.setTime(myDate.getTime() - myDate.getTimezoneOffset() * 60 * 1000);
				$(this).html(myDate.toUTCString());
			}
		);
	}
	convertDateTime('postDate');
</script>
</html>