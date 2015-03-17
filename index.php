<?php
	session_start();
?>
<html>
		<head>
		<title>RiazMyLogin</title>
		<link rel="stylesheet" href="assets/css/style.css"/>
		<link rel="stylesheet" href="assets/css/bootstrap.css"/>
		<link rel="stylesheet" href="assets/css/font-awesome.css"/>
		<script src="assets/js/jquery.js" type="text/javascript"></script>    
		<script src="assets/js/bootstrap.js" type="text/javascript"></script>    		    
		<script src="assets/js/lineTextArea.js" type="text/javascript"></script>    		    		
        <script src="assets/js/base.js" type="text/javascript"></script>     

        <!-- // <script type="text/javascript" src="http://mymaplist.com/js/vendor/TweenLite.min.js"></script> -->
        </head>
        <body>

<?php
	//define('MYSQL_USER','root');   #MySQL Engine Username
	//define('MYSQL_PASS','');       #MySQL Engine Password

	define('DB_HOST',   $_SERVER['REMOTE_ADDR']);
	define('SESSION_ID',session_id());

	if(isset($_GET['token']) && htmlspecialchars($_GET['token']) == SESSION_ID) //user-part of the present session
	{
		//for the db mode
		if(isset($_GET['db'])){
			//re-creating db connection, since php resource type cannot be saved in sessions
			$_SESSION['DB'] = mysql_connect(DB_HOST,$_SESSION['username'],$_SESSION['password']); //or die to login view

			//checking that the selected db exists
			//if the selected db doesn't exists in the db schema we display - "No tables found in database " in the left panel
			//the right master pannel is similar to userView
			$query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . htmlspecialchars($_GET['db']) ."'";

			if(!mysql_query($query,$_SESSION['DB'])){
				$_GLOBALS['listError'] = "No tables found in database";
			}else{
				mysql_select_db(htmlspecialchars($_GET['db']));				
			}

			$_SESSION['currentView'] = 'dbView';

         	//removing the background
			echo "<script> $('body').css('background','none'); </script>";

		}
	}
	else{	 //default view and also when the session token expires
		$_SESSION['currentView'] = 'login';
	}

	if(isset($_POST['username']) && isset($_POST['password'])){
		$_SESSION['username'] = htmlspecialchars($_POST['username']);
		$_SESSION['password'] = htmlspecialchars($_POST['password']);

		if($_SESSION['DB'] = mysql_connect(
					DB_HOST,
					$_SESSION['username'],
					$_SESSION['password']
					)){

			//echo $_SESSION['DB'];
		
			//echo "Successfully LoggedIn";			
			$_SESSION['currentView'] = 'userCP';


			//removing the background
			echo "<script> $('body').css('background','none'); </script>";

			//trying setting up a database connection
			//we settup a global variable for this connection
			// if(!($_SESSION['DB'] = mysql_connect(DB_HOST,MYSQL_USER,MYSQL_PASS))){
			// 	$GLOBALS['message_01'] = "Couldn't establish connection to database";
			// 	die();
			// }

		}
		else{
			echo '<div class="alert alert-danger" role="alert">';
			echo '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>';
			echo '<span class="sr-only">Error:</span><span class="brand">Incorrect Login/Password</span></div>';
			die();
			//echo "Incorrect Login/Password";
		}
	}
?>
        
        <!--##############################################################################-->
        <!--                            LOGIN SCREEN                                      -->
        <!--##############################################################################-->

            <div id="loginScreen" style="display: <?php echo $_SESSION['currentView'] != 'login'? 'none':'block'; ?>" class="container">
                <div class="row vertical-offset-100">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">                                
                                <div class="row-fluid user-row">
                                    <img src="http://s11.postimg.org/7kzgji28v/logo_sm_2_mr_1.png" class="img-responsive" alt="Conxole Admin"/>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" accept-charset="UTF-8" role="form" class="form-signin">
                                    <fieldset>
                                        <label class="panel-login">
                                            <div class="login_result"></div>
                                        </label>
                                        <input class="form-control" name="username" placeholder="Username" id="username" type="text">
                                  		<input class="form-control" name="password" placeholder="Password" id="password" type="password">
                                        <br></br>
                                        <input class="btn btn-lg btn-success btn-block" type="submit" id="login" value="Login »">
                                        <input type="hidden" value="1" name="loggedIn"/>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!--##############################################################################-->
        <!--                            USER DASHBOARD                                    -->
        <!--##############################################################################-->

        <div id="wrapper" style="display: <?php echo $_SESSION['currentView'] != 'userCP'? 'none':'block'; ?>">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        <strong><i style="color:pink" class="fa fa-cubes"></i><span class="brand"><span style="color:orange">Riaz</span><span style="color:white">My</span><span style="color:green">Admin</span></span></strong>
                    </a>
                </li>
                <li style='margin-bottom:10px'>
                	<span class="nav-control"><i class="fa fa-home"></i></span>
                	<span class="nav-control"><i class="fa fa-desktop"></i></span>
                	<span class="nav-control"><i class="fa fa-question-circle"></i></span>
                	<span class="nav-control"><i class="fa fa-file-text-o"></i></span>
               	   	<span class="nav-control"><i class="fa fa-refresh"></i></span>             
                </li>
                <?php
		            $query = "SHOW DATABASES";
		            $all_db = array();
		            if($result = mysql_query($query,$_SESSION['DB'])){
						//sort(mysql_fetch_assoc($result));
						while($row = mysql_fetch_assoc($result)){
							$all_db[] = $row;
							//echo '<li><a href="?db='. $row['Database'].'">' . $row['Database'] . '</a></li>';                						
						}
						sort($all_db);
						foreach ($all_db as &$db) {
							echo '<li><a href="?db='. $db['Database'].'&token='. SESSION_ID .'"><i style="margin-right:5px" class="fa fa-database"></i>' . $db['Database'] . '</a></li>';                									
						}
					
						
					}else{ // failed show database query
						echo '<li>Unable to retrive Databases</li>';	
					}
                ?>
                
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
			<ol class="breadcrumb">
			  <?php $query = $_SERVER['QUERY_STRING']; ?>
			  <li><a href="#">localhost</a></li>
			  <!-- <li><a href="#">Library</a></li>
			  <li class="active">Data</li> -->
			</ol>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tabX1" data-toggle="tab">Databases</a></li>
                            <li><a href="#tabX2" data-toggle="tab">SQL</a></li>
                            <li><a href="#tabX3" data-toggle="tab">Status</a></li> 
                            <li><a href="#tabX4" data-toggle="tab">Users</a></li> 
                            <li><a href="#tabX5" data-toggle="tab">Export</a></li> 
                            <li><a href="#tabX6" data-toggle="tab">Import</a></li> 
                            <li><a href="#tabX7" data-toggle="tab">Settings</a></li> 
                            <li><a href="#tabX8" data-toggle="tab">Synchronize</a></li> 
                            <li><a href="#tabX9" data-toggle="tab">Replication</a></li> 
                            <li><a href="#tabX10" data-toggle="tab">Variables</a></li> 
                            <li><a href="#tabX11" data-toggle="tab">Charsets</a></li> 
                            <li><a href="#tabX12" data-toggle="tab">Engines</a></li>                                                        
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tabX1">
                        <!-- ############ TAB DATABASES ##########################################-->  
                        <h4>Databases</h4>  
                        <div class="panel panel-primary">
								<div class="panel-heading">
								<h3 class="panel-title"><i style="margin-right:5px" class="fa fa-database"></i>Create Database</h3>
								<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
								</div>
								<div class="panel-body">
								<div class="controls form-inline">
							   		<input type="text" id="database" class="form-control" name="database" placeholder="database" class="input-sm"/>
							   		<div class="dropdown" style="display:inline">
									  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
									  Collation									    
									    <span class="caret"></span>
									  </button>
									  <?php
									  	dropdownPrepare("SHOW CHARACTER SET","Default_collation");
									  ?>
									</div>
									<button type="button" class="btn btn-success">Create</button>
								</div>
								</div>	
							</div>	

							<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Databases</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<?php
				                        	$query = "SHOW DATABASES";
				                        	$results = mysql_query($query,$_SESSION['DB']);                        
				                        	simpleTableRenderer($results,"");
				                        ?>
									</div>
							</div>				                                                      
                        <!-- ############ TAB DATABASES ##########################################-->                                       
                        
                        </div>
                        <div class="tab-pane fade" id="tabX2">
                        <!-- ############ TAB SQL ##########################################-->                                       
                        	
                        	<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Run SQL query/queries on server "127.0.0.1"</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<textarea id="sqlQuery" style="resize: none;height:400px" class="form-control"></textarea>
									</div>
									<div class="panel-footer">
													<button type="button" style="padding:1px 2px !important; "class="btn btn-success pull-right">Go</button>						
									</div>
							</div>	
						<!-- ############ TAB SQL ##########################################-->                                                               
                        </div>
                        <div class="tab-pane fade" id="tabX3">
                        <!-- ############ TAB Status ##########################################-->  
                        <h4>Runtime Information</h4>   
                        <div class="panel with-nav-tabs panel-primary">
                		<div class="panel-heading">
	                        <ul class="nav nav-tabs">
	                            <li class="active"><a href="#tab31" data-toggle="tab">Server</a></li>
	                            <li><a href="#tab32" data-toggle="tab">Query Statistics</a></li>
	                            <li><a href="#tab33" data-toggle="tab">All Status Variables</a></li> 
	                            <li><a href="#tab34" data-toggle="tab">Monitor</a></li> 
	                            <li><a href="#tab35" data-toggle="tab">Advisor</a></li> 	                                                                           
	                        </ul>
               			</div>
                		<div class="panel-body">
                    	<div class="tab-content">
                        	<div class="tab-pane fade in active" id="tab31">
                        		<h5>Network Traffic Analysis Since Startup : </h5>
                        	</div>
                        	<div class="tab-pane fade" id="tab32">
                        		<h5>Questions since Startup : </h5>
                        	</div>
                        	<div class="tab-pane fade" id="tab33"></div>
                        	<div class="tab-pane fade" id="tab34"></div>
                        	<div class="tab-pane fade" id="tab35"></div>
                        </div>
                        </div>
                        </div>                                              
                        <!-- ############ TAB Status ##########################################-->                                                                                      	
                        </div>
                        <div class="tab-pane fade" id="tabX4">
                        <!-- ############ TAB USERS ##########################################-->               
                        
                        	<?php
                        	$query = "select User,Host,Password,Super_priv as Global_Privileges, Grant_priv  from mysql.user";
                        	$results = mysql_query($query,$_SESSION['DB']);                        
                        	simpleTableRenderer($results,"USERS");
                        ?>
                        <!-- ############ TAB USERS ##########################################-->                            	
                        </div>
                        <div class="tab-pane fade" id="tabX5">
                        <!-- ############ TAB EXPORT ##########################################-->                            	
                        
                        	<h4>Exporting databases from the current server</h4>
                        	<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Export Method</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<div>
											<input type="radio" aria-label="...">									        
										    <label>Quick - display only the minimal options</label>
									    </div>
									    <div>
										    <input type="radio" aria-label="...">									        
										    <label>Custom - display all possible options</label>
										</div>
									</div>
								</div>
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Format</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
									<div class="dropdown" style="display:inline">
									  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
									  Formats									    
									    <span class="caret"></span>
									  </button>
									  <?php
									  	dropdownPrepare("SHOW CHARACTER SET","Default_collation");
									  ?>
									</div>		
									</div>
									<div class="panel-footer">
										<button type="button" style="padding:1px 5px !important;" class="btn btn-success">Go</button>						
									</div>
				
								</div>
                        <!-- ############ TAB EXPORT ##########################################-->                            	                        
                        </div>
                		<!-- ############ TAB IMPORT ##########################################-->               						
                        <div class="tab-pane fade" id="tabX6">
                        	<h4>Importing into the current Server</h4>
                        	<!-- ..................File to Import ......................... -->							
                        	<div class="panel panel-primary">
                        			<div class="panel-heading">
										<h3 class="panel-title">File To Import</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										File may be compressed (gzip, bzip2, zip) or uncompressed.<br/>
										A compressed file's name must end in <b>.[format].[compression].</b> Example: <b>.sql.zip</b><br/>
										<p>Browse your computer : <input style="display: inline" type="file"/>(Max: 2046 KiB)</p>
										Character set of the file : 
											  <div class="dropdown" style="display:inline">
											  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
											  Formats									    
											    <span class="caret"></span>
											  </button>
											  <?php
											  	dropdownPrepare("SHOW CHARACTER SET","Charset");
											  ?>
											</div>
									</div>							
							</div>
							<!-- .................. File to Import ......................... -->
									
							<!-- .................. Partial Import ......................... -->							
							<div class="panel panel-primary">                        		
									<div class="panel-heading">
										<h3 class="panel-title">Partial Import</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<input type="checkbox"/><span style="margin-left:5px">Allow the interruption of an import in case the script detects it is close to the PHP timeout limit.</span><br/>
									    (This might be good way to import large files, however it can break transactions.)<br/>
										Number of rows to skip, starting from the first row:
										<input type="text" id="rowSkip" class="form-control" style="width: inherit;display:inline" name="rowSkip" value="0" class="input-sm"/>							   		
									</div>
							</div>
							<!-- .................. Partial Import ......................... -->					

							<!-- .................. Format ......................... -->												
							<div class="panel panel-primary">                        		
									<div class="panel-heading">
										<h3 class="panel-title">Format</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<div class="dropdown">
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
										    Formats
										    <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSV</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Open Document Spreadsheet</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ESRI Shape File</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">SQL</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">XML</a></li>										  
										  </ul>
										</div>
									</div>
							</div>	
							<!-- .................. Format ......................... -->							
							<!--.......................... Format Specific Options ..............................-->
							<div class="panel panel-primary">                        		
									<div class="panel-heading">
										<h3 class="panel-title">Format-Specific Options</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<div class="dropdown">
										SQL Compatibility Mode : 										
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
										    Mode
										    <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">NONE</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ANSI</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">DB2</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MAXDB</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MYSQL323</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MYSQL40</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MYSQL323</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ORACLE</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">TRADITIONAL</a></li>										  
										  </ul>
										</div>
										<span><input type="checkbox" style="margin-left:5px"/></span>Do not use AUTO_INCREMENT for zero values
									</div>
							</div>	
							<!--.......................... Format Specific Options ..............................-->	
							<!-- ############ TAB IMPORT ##########################################-->               						
                        </div>
                        <div class="tab-pane fade" id="tabX7">Settings</div>

                        <!-- ############ TAB IMPORT ##########################################-->               						                        
                        <div class="tab-pane fade" id="tabX8">
                        <h4>Synchronize</h4>
                        	<div class="panel panel-primary">                        		
								<div class="panel-heading">
										<h3 class="panel-title">Synchronize</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
								</div>
								<div class="panel-body">
								</div>
								<div class="panel-footer">
										<button type="button" style="padding:1px 5px !important;" class="btn btn-success">Go</button>						
								</div>				
							</div>
                        </div>
                        <!-- ############ TAB IMPORT ##########################################-->               						
                        
                        <div class="tab-pane fade" id="tabX9">
                        <!-- ############ TAB REPLICATION ##########################################-->               
                        
                        		<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Master Replication</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">This server is not configured as master in a replication process. Would you like to configure it?</div>
								</div>
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Slave Replication</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">This server is not configured as slave in a replication process. Would you like to configure it?</div>
								</div>
						<!-- ############ TAB REPLICATION ##########################################-->                                       
                        </div>
                        <div class="tab-pane fade" id="tabX10">
                        <!-- ############ TAB VARIABLES ##########################################-->               
                        
                        	<?php
                        	$query = "SHOW VARIABLES";
                        	$results = mysql_query($query,$_SESSION['DB']);                        
                        	simpleTableRenderer($results,"VARIABLES");
                        ?>
                        <!-- ############ TAB VARIABLES ##########################################-->               
                        
                        </div>

                        <div class="tab-pane fade" id="tabX11">
                        <!-- ############ TAB CHARSETS ##########################################-->
                        	
						<?php
                        	$query = "SHOW CHARACTER SET";
                        	$results = mysql_query($query,$_SESSION['DB']);
                        	//echo $results;
                        	simpleTableRenderer($results,"CHARACTER SETS");
                        ?>
						<!-- ############ TAB CHARSETS ##########################################-->                                                </div>
                        <div class="tab-pane fade" id="Xtab12">
                        <!-- ############ TAB ENGINES ##########################################-->               
                        <?php
                        	$query = "SHOW ENGINES";
                        	$results = mysql_query($query,$_SESSION['DB']);
                        	simpleTableRenderer($results,"ENGINES");
                        ?>
                        </div>
                        <!-- ############ TAB ENGINES ##########################################-->                                       
                        
                        <!-- ############ TAB DESIGNER ##########################################-->               
                        <div class="tab-pane fade" id="Xtab13">
                        <?php
                        	$query = "SHOW ENGINES";
                        	$results = mysql_query($query,$_SESSION['DB']);
                        	simpleTableRenderer($results,"ENGINES");
                        ?>
                        </div> 
                        <!-- ############ TAB DESIGNER ##########################################-->                                       
                                         
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!--##############################################################################-->
        <!--                            Database DASHBOARD                                    -->
        <!--##############################################################################-->

        <div id="wrapper" style="display: <?php echo $_SESSION['currentView'] != 'dbView'? 'none':'block'; ?>">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        <strong><i style="color:pink" class="fa fa-cubes"></i><span class="brand"><span style="color:orange">Riaz</span><span style="color:white">My</span><span style="color:green">Admin</span></span></strong>
                    </a>
                </li>
                <li style='margin-bottom:10px'>
                	<span class="nav-control"><i class="fa fa-home"></i></span>
                	<span class="nav-control"><i class="fa fa-desktop"></i></span>
                	<span class="nav-control"><i class="fa fa-question-circle"></i></span>
                	<span class="nav-control"><i class="fa fa-file-text-o"></i></span>
               	   	<span class="nav-control"><i class="fa fa-refresh"></i></span>             
                </li>
                <?php
		            $query = "SHOW TABLES";
		            $all_db = array();
		            if($result = mysql_query($query,$_SESSION['DB'])){
						//sort(mysql_fetch_assoc($result));
						while($row = mysql_fetch_assoc($result)){
							$all_db[] = $row;
							//echo '<li><a href="?db='. $row['Database'].'">' . $row['Database'] . '</a></li>';                						
						}
						sort($all_db);
						$colName = 'Tables_in_' . $_GET['db'];
						foreach ($all_db as &$db) {
							echo '<li><a href="#"><i style="margin-right:5px" class="fa fa-database"></i>' . $db["$colName"] . '</a></li>';                									
						}					
						
					}else{ // failed show database query
						echo '<li>failed show database query</li>';	
					}
                ?>
                
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
			<ol class="breadcrumb">
			  <?php $query = $_SERVER['QUERY_STRING']; ?>
			  <li><a href="#">localhost</a></li>
			  <li class="active"><a href="#"><?=htmlspecialchars($_GET['db'])?></a></li>
			</ol>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1" data-toggle="tab">Structure</a></li>
                            <li><a href="#tab2" data-toggle="tab">SQL</a></li>
                            <li><a href="#tab3" data-toggle="tab">Search</a></li> 
                            <li><a href="#tab4" data-toggle="tab">Query</a></li> 
                            <li><a href="#tab5" data-toggle="tab">Export</a></li> 
                            <li><a href="#tab6" data-toggle="tab">Import</a></li> 
                            <li><a href="#tab7" data-toggle="tab">Operations</a></li> 
                            <li><a href="#tab8" data-toggle="tab">Privileges</a></li> 
                            <li><a href="#tab9" data-toggle="tab">Routines</a></li> 
                            <li><a href="#tab10" data-toggle="tab">Events</a></li> 
                            <li><a href="#tab11" data-toggle="tab">Triggers</a></li> 
                            <li><a href="#tab12" data-toggle="tab">Tracking</a></li>                                                        
                            <li><a href="#tab12" data-toggle="tab">Designer</a></li>                                                        
                      
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1">
                        <!-- ############ TAB DATABASES ##########################################-->  
                        <h4>Databases</h4>  
                        <div class="panel panel-primary">
								<div class="panel-heading">
								<h3 class="panel-title"><i style="margin-right:5px" class="fa fa-database"></i>Create Database</h3>
								<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
								</div>
								<div class="panel-body">
								<div class="controls form-inline">
							   		<input type="text" id="database" class="form-control" name="database" placeholder="database" class="input-sm"/>
							   		<div class="dropdown" style="display:inline">
									  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
									  Collation									    
									    <span class="caret"></span>
									  </button>
									  <?php
									  	dropdownPrepare("SHOW CHARACTER SET","Default_collation");
									  ?>
									</div>
									<button type="button" class="btn btn-success">Create</button>
								</div>
								</div>	
							</div>	

							<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Databases</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<?php
				                        	$query = "SHOW DATABASES";
				                        	$results = mysql_query($query,$_SESSION['DB']);                        
				                        	simpleTableRenderer($results,"");
				                        ?>
									</div>
							</div>				                                                      
                        <!-- ############ TAB DATABASES ##########################################-->                                       
                        
                        </div>
                        <div class="tab-pane fade" id="tab2">
                        <!-- ############ TAB SQL ##########################################-->                                       
                        	
                        	<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Run SQL query/queries on server "127.0.0.1"</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<textarea id="sqlQuery" style="resize: none;height:400px" class="form-control"></textarea>
									</div>
									<div class="panel-footer">
													<button type="button" style="padding:1px 2px !important; "class="btn btn-success pull-right">Go</button>						
									</div>
							</div>	
						<!-- ############ TAB SQL ##########################################-->                                                               
                        </div>
                        <div class="tab-pane fade" id="tab3">
                        <!-- ############ TAB Status ##########################################-->  
                        <h4>Runtime Information</h4>   
                        <div class="panel with-nav-tabs panel-primary">
                		<div class="panel-heading">
	                        <ul class="nav nav-tabs">
	                            <li class="active"><a href="#tab31" data-toggle="tab">Server</a></li>
	                            <li><a href="#tab32" data-toggle="tab">Query Statistics</a></li>
	                            <li><a href="#tab33" data-toggle="tab">All Status Variables</a></li> 
	                            <li><a href="#tab34" data-toggle="tab">Monitor</a></li> 
	                            <li><a href="#tab35" data-toggle="tab">Advisor</a></li> 	                                                                           
	                        </ul>
               			</div>
                		<div class="panel-body">
                    	<div class="tab-content">
                        	<div class="tab-pane fade in active" id="tab31">
                        		<h5>Network Traffic Analysis Since Startup : </h5>
                        	</div>
                        	<div class="tab-pane fade" id="tab32">
                        		<h5>Questions since Startup : </h5>
                        	</div>
                        	<div class="tab-pane fade" id="tab33"></div>
                        	<div class="tab-pane fade" id="tab34"></div>
                        	<div class="tab-pane fade" id="tab35"></div>
                        </div>
                        </div>
                        </div>                                              
                        <!-- ############ TAB Status ##########################################-->                                                                                      	
                        </div>
                        <div class="tab-pane fade" id="tab4">
                        <!-- ############ TAB USERS ##########################################-->               
                        
                        	<?php
                        	$query = "select User,Host,Password,Super_priv as Global_Privileges, Grant_priv  from mysql.user";
                        	$results = mysql_query($query,$_SESSION['DB']);                        
                        	simpleTableRenderer($results,"USERS");
                        ?>
                        <!-- ############ TAB USERS ##########################################-->                            	
                        </div>
                        <div class="tab-pane fade" id="tab5">
                        <!-- ############ TAB EXPORT ##########################################-->                            	
                        
                        	<h4>Exporting databases from the current server</h4>
                        	<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Export Method</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<div>
											<input type="radio" aria-label="...">									        
										    <label>Quick - display only the minimal options</label>
									    </div>
									    <div>
										    <input type="radio" aria-label="...">									        
										    <label>Custom - display all possible options</label>
										</div>
									</div>
								</div>
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Format</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
									<div class="dropdown" style="display:inline">
									  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
									  Formats									    
									    <span class="caret"></span>
									  </button>
									  <?php
									  	dropdownPrepare("SHOW CHARACTER SET","Default_collation");
									  ?>
									</div>		
									</div>
									<div class="panel-footer">
										<button type="button" style="padding:1px 5px !important;" class="btn btn-success">Go</button>						
									</div>
				
								</div>
                        <!-- ############ TAB EXPORT ##########################################-->                            	                        
                        </div>
                		<!-- ############ TAB IMPORT ##########################################-->               						
                        <div class="tab-pane fade" id="tab6">
                        	<h4>Importing into the current Server</h4>
                        	<!-- ..................File to Import ......................... -->							
                        	<div class="panel panel-primary">
                        			<div class="panel-heading">
										<h3 class="panel-title">File To Import</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										File may be compressed (gzip, bzip2, zip) or uncompressed.<br/>
										A compressed file's name must end in <b>.[format].[compression].</b> Example: <b>.sql.zip</b><br/>
										<p>Browse your computer : <input style="display: inline" type="file"/>(Max: 2046 KiB)</p>
										Character set of the file : 
											  <div class="dropdown" style="display:inline">
											  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
											  Formats									    
											    <span class="caret"></span>
											  </button>
											  <?php
											  	dropdownPrepare("SHOW CHARACTER SET","Charset");
											  ?>
											</div>
									</div>							
							</div>
							<!-- .................. File to Import ......................... -->
									
							<!-- .................. Partial Import ......................... -->							
							<div class="panel panel-primary">                        		
									<div class="panel-heading">
										<h3 class="panel-title">Partial Import</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<input type="checkbox"/><span style="margin-left:5px">Allow the interruption of an import in case the script detects it is close to the PHP timeout limit.</span><br/>
									    (This might be good way to import large files, however it can break transactions.)<br/>
										Number of rows to skip, starting from the first row:
										<input type="text" id="rowSkip" class="form-control" style="width: inherit;display:inline" name="rowSkip" value="0" class="input-sm"/>							   		
									</div>
							</div>
							<!-- .................. Partial Import ......................... -->					

							<!-- .................. Format ......................... -->												
							<div class="panel panel-primary">                        		
									<div class="panel-heading">
										<h3 class="panel-title">Format</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<div class="dropdown">
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
										    Formats
										    <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSV</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Open Document Spreadsheet</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ESRI Shape File</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">SQL</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">XML</a></li>										  
										  </ul>
										</div>
									</div>
							</div>	
							<!-- .................. Format ......................... -->							
							<!--.......................... Format Specific Options ..............................-->
							<div class="panel panel-primary">                        		
									<div class="panel-heading">
										<h3 class="panel-title">Format-Specific Options</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">
										<div class="dropdown">
										SQL Compatibility Mode : 										
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
										    Mode
										    <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">NONE</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ANSI</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">DB2</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MAXDB</a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MYSQL323</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MYSQL40</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">MYSQL323</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ORACLE</a></li>										  
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">TRADITIONAL</a></li>										  
										  </ul>
										</div>
										<span><input type="checkbox" style="margin-left:5px"/></span>Do not use AUTO_INCREMENT for zero values
									</div>
							</div>	
							<!--.......................... Format Specific Options ..............................-->	
							<!-- ############ TAB IMPORT ##########################################-->               						
                        </div>
                        <div class="tab-pane fade" id="tab7">Settings</div>

                        <!-- ############ TAB IMPORT ##########################################-->               						                        
                        <div class="tab-pane fade" id="tab8">
                        <h4>Synchronize</h4>
                        	<div class="panel panel-primary">                        		
								<div class="panel-heading">
										<h3 class="panel-title">Synchronize</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
								</div>
								<div class="panel-body">
								</div>
								<div class="panel-footer">
										<button type="button" style="padding:1px 5px !important;" class="btn btn-success">Go</button>						
								</div>				
							</div>
                        </div>
                        <!-- ############ TAB IMPORT ##########################################-->               						
                        
                        <div class="tab-pane fade" id="tab9">
                        <!-- ############ TAB REPLICATION ##########################################-->               
                        
                        		<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Master Replication</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">This server is not configured as master in a replication process. Would you like to configure it?</div>
								</div>
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Slave Replication</h3>
										<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
									</div>
									<div class="panel-body">This server is not configured as slave in a replication process. Would you like to configure it?</div>
								</div>
						<!-- ############ TAB REPLICATION ##########################################-->                                       
                        </div>
                        <div class="tab-pane fade" id="tab10">
                        <!-- ############ TAB VARIABLES ##########################################-->               
                        
                        	<?php
                        	$query = "SHOW VARIABLES";
                        	$results = mysql_query($query,$_SESSION['DB']);                        
                        	simpleTableRenderer($results,"VARIABLES");
                        ?>
                        <!-- ############ TAB VARIABLES ##########################################-->               
                        
                        </div>
                        <div class="tab-pane fade" id="tab11">
                        <!-- ############ TAB CHARSETS ##########################################-->
                        	
						<?php
                        	$query = "SHOW CHARACTER SET";
                        	$results = mysql_query($query,$_SESSION['DB']);
                        	//echo $results;
                        	simpleTableRenderer($results,"CHARACTER SETS");
                        ?>
						<!-- ############ TAB CHARSETS ##########################################-->                                                </div>
                        <div class="tab-pane fade" id="tab12">
                        <!-- ############ TAB ENGINES ##########################################-->               
                        <?php
                        	$query = "SHOW ENGINES";
                        	$results = mysql_query($query,$_SESSION['DB']);
                        	simpleTableRenderer($results,"ENGINES");
                        ?>
                        <!-- ############ TAB ENGINES ##########################################-->                                       
                        </div>                  
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#DB  View -->
        </body>
</html>

<?php

/**** Utility functions block *********/

/******************** 1. SimpleTableRenderer ********************************/

function simpleTableRenderer($results,$title) 
  { 
  $row=mysql_fetch_array($results,MYSQL_ASSOC); 
  fixArrayKey($row);
  
  $array_of_keys=array_keys($row); 

  echo '<div class="panel panel-default">';
  if($title)
  	echo '<div class="panel-heading">' . $title .'</div>';
  echo '<table class="table">';  
  echo "<thead>"; 
  foreach($array_of_keys as $array_key) 
    { 
    echo "<th>". $array_key."</th>"; 
    } 
  echo "</thead>"; 
  if($row) 
    { 
    fixArrayKey($row);
    extract($row); 
    echo "<tr>"; 
    foreach($array_of_keys as $array_key) echo "<td>". $$array_key . "</td>"; 
    echo "</tr>\n"; 
    } //end if($row) 

    while($row=mysql_fetch_array($results,MYSQL_ASSOC)){
    	  fixArrayKey($row);
	      extract($row); 
	      echo "<tr>"; 
	      foreach($array_of_keys as $array_key) echo "<td>".$$array_key."</td>"; 
	      echo "</tr>\n"; 	       
    } // end of for 
	
  echo "</table></div>"; 
  } // end function  

/******************** 2. Fix array keys  ********************************/
function fixArrayKey(&$arr)
{
    $arr=array_combine(array_map(function($str){return str_replace(" ","_",$str);},array_keys($arr)),array_values($arr));
    
}

function dropdownPrepare($query,$field){
	$results = mysql_query($query);

	echo '<ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="dropdownMenu1">';
	while($row=mysql_fetch_array($results,MYSQL_ASSOC)){
    	  fixArrayKey($row);
	      extract($row); 
	      echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="#">'; 
	      echo  "". $row[$field] .""; 
	      echo "</a></li>"; 	       
    } 
    echo '</ul>';
}
?>