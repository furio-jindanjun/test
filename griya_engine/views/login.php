<div id="contentframe">
	<?php
	if(isset($isLoginPage)){

		echo '<form action="'.base_url().'login/validasi" method="post" class="aw" id="sainfrm">
				<label for="key1">Username</label><input type="text" id="key1" name="key1"/>
				<label for="key2">Password</label><input type="password" id="key2" name="key2"/>
				<span class="divider-horizontal"></span>
				<button type="submit">Login</button>
			<input type="hidden" id="key3" name="key3" value=""/></form>';
		
	}else{

		echo '<form action="" method="post" id="sainfrm">
				<b>'.$userName.'</b><span class="divider-vertical"></span><a title="logout" class="aw" href="'.base_url().'login/logout">X</a></form>';

	}
	?>
</div>