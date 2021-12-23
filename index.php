<?php
	include_once 'index-navbar.php';
?>

<div class="custom-container">
	<div class="row justify-content-center"">
		<div class="col-8">
			<h2> Welcome </h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
			</br>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-8" id="test">
		</div>
	</div>
</div>
	
<script type="text/javascript">
	$(document).ready(function() {
		$("#test").load("login-form.txt");
		$("#btn").click(function(){
			$("#test").load("login-form.txt");
		});
		$("#btn2").click(function(){
			$("#test").load("signup-form.txt");
		});
	});
</script>

</body>
</html>