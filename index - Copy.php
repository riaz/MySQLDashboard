<?php
/***
This application will matrure similar to the php my admin application.

***/
$loggedIn = false;	
?>
<!-- 
 * parallax_login.html
 * @Author original @msurguy (tw) -> http://bootsnipp.com/snippets/featured/parallax-login-form
 * @Tested on FF && CH
 * @Reworked by @kaptenn_com (tw)
 * @package PARALLAX LOGIN.
-->
        <script src="http://mymaplist.com/js/vendor/TweenLite.min.js"></script>
        <body>
            <div class="container">
                <div class="row vertical-offset-100">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">                                
                                <div class="row-fluid user-row">
                                    <img src="http://s11.postimg.org/7kzgji28v/logo_sm_2_mr_1.png" class="img-responsive" alt="Conxole Admin"/>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form accept-charset="UTF-8" role="form" class="form-signin">
                                    <fieldset>
                                        <label class="panel-login">
                                            <div class="login_result"></div>
                                        </label>
                                        <input class="form-control" placeholder="Username" id="username" type="text">
                                        <input class="form-control" placeholder="Password" id="password" type="password">
                                        <br></br>
                                        <input class="btn btn-lg btn-success btn-block" type="submit" id="login" value="Login »">
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
            </div>
<!-- <html>
	<head>
		<title> Riaz my admin</title>
		<style type="text/css"></style>
	</head>
	<body>
		<content>		
		<form  style="display: <?php echo $loggedIn? 'none': 'block'; ?>" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table>
				<tr colspan="2"><p> RiazMyAdmin Login Scren: </p></tr>
				<tr>
					<td>Username</td>
					<td><input type="text" name="text"/></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="text"/></td>					
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Log In"/><input type="hidden" value="1" name="loggedIn"/></td>
				</tr>				
			</table>
		</form>
		</content>
		<footer><center>Copyright&copy;<?php echo date('Y'); ?></center></footer>
	</body>
</html> -->