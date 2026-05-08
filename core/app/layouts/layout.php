<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>PERUVIANDRESS | Panel de Administracion</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="icon" type="image/png" href="core/app/layouts/logo (1).png">
	<!-- Bootstrap 3.3.4 -->
	<link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- Font Awesome Icons -->
	<link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<!-- Theme style -->
	<link href="plugins/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	<link href="plugins/dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

	<script src="plugins/jquery/jquery-2.1.4.min.js"></script>


	<link href="plugins/css/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
	<!--<script type="text/javascript" src="https://tobbias.softluttion.com/js/jquery.hotkeys-0.7.9.min.js"></script>-->
	<script src="plugins/js/jquery-ui-1.10.3.custom.js" type="text/javascript"></script>
	<script src="plugins/js/bootbox.min.js" type="text/javascript"></script>
	<script src="plugins/js/jquery.datetimepicker.js" type="text/javascript"></script>



	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="plugins/morris/raphael-min.js"></script>
	<script src="plugins/morris/morris.js"></script>
	<link rel="stylesheet" href="plugins/morris/morris.css">
	<link rel="stylesheet" href="plugins/morris/example.css">
	<link rel="stylesheet" href="plugins/css/style.css">
	<script src="plugins/jspdf/jspdf.min.js"></script>
	<script src="plugins/jspdf/jspdf.plugin.autotable.js"></script>




	<?php if (isset($_GET["view"]) && $_GET["view"] == "sell") : ?>
		<script type="text/javascript" src="plugins/jsqrcode/llqrcode.js"></script>
		<script type="text/javascript" src="plugins/jsqrcode/webqr.js"></script>



	<?php endif; ?>

	<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
	<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>


	<script src="plugins/Chart.js/Chart.js"></script>
	<link rel="stylesheet" href="https://dbusinessaqp.com/jstree/style.min.css" />
	<script src="https://dbusinessaqp.com/jstree/jstree.min.js"></script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<style>
		.rounded-pill {
			border-radius: 50rem !important;
		}

		thead {
			background-color: #5d69d4;
			color: #fff;
		}

		.clsDatePicker {
			border-top-left-radius: 50rem !important;
			border-bottom-left-radius: 50rem !important;
		}

		td {
			vertical-align: middle !important;
		}

		.btn-delete {
			background-color: #ff4d4d;
			/* Color de fondo */
			color: white;
			/* Color del texto */
			border: none;
			/* Sin borde */
			border-radius: 20px;
			/* Bordes redondeados */
			padding: 10px 20px;
			/* Relleno */
			font-size: 16px;
			/* Tamaño de fuente */
			display: flex;
			/* Flexbox para alinear icono y texto */
			align-items: center;
			/* Alinear verticalmente */
		}

		.btn-delete .icon {
			margin-right: 10px;
			/* Espacio entre icono y texto */
			font-size: 18px;
			/* Tamaño del icono */
		}

		.btn-delete:hover {
			background-color: #ff1a1a;
			/* Color de fondo al pasar el ratón */
		}
	</style>
</head>

<body class="<?php if (isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])) : ?>  skin-blue sidebar-mini <?php else : ?>login-page<?php endif; ?>" style="background-image: url('img/fondo_peruvian.png'); background-repeat: no-repeat; background-size: cover;">
	<div class="wrapper">
		<!-- Main Header -->
		<?php if (isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])) : ?>
			<header class="main-header">
				<!-- Logo -->
				<a href="./" class="logo" style="background-color: #fff;">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini"><img src="img/logo-3.png"></span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"><img src="img/logo-3.png" style="width: 100%;"></span>
				</a>

				<!-- Header Navbar -->
				<nav class="navbar navbar-static-top" role="navigation">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<!-- Navbar Right Menu -->
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">


							<?php
							//print_r($_SESSION);
							if (isset($_SESSION["user_id"])) :
								$msgs = MessageData::getUnreadedByUserId($_SESSION["user_id"]);
								$cnt_tot = 0;
								$found = false;
								$products = ProductData::OnlyProducts();
								//print_r($products);
								foreach ($products as $product) {
									$q = OperationData::getQByStock($product->id, StockData::getPrincipal()->id);
									if ($q == 0 ||  $q <= $product->inventary_min) {
										$cnt_tot++;
									}
								}
							?>

								<?php
								$k = Core::$user->kind;
								//echo $k;
								if ($k == 1) {
								?>
									<li class="dmt">
										<a href="./?view=alerts">
											<i class="fa fa-bell"></i>
											<span class="label label-danger"><?php echo $cnt_tot; ?></span>
										</a>

									</li>
									<!--<li class="dropdown messages-menu dmt">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-envelope-o"></i>
											<span class="label label-success"><?php echo count($msgs); ?></span>
										</a>
										<ul class="dropdown-menu">
											<li class="header">Tienes <?php echo count($msgs); ?> mensajes nuevos</li>
											<li>
												<ul class="menu">
													<?php echo Core::$user->kind; ?>
													<?php foreach ($msgs as $i) : ?>
														<li>
															<a href="./?view=messages&opt=open&code=<?php echo $i->code; ?>">
																<h4>
																	<?php if ($i->user_from != $_SESSION["user_id"]) : ?>
																		<?php $u = $i->getFrom();
																		echo $u->name . " " . $u->lastname; ?>
																	<?php elseif ($i->user_to != $_SESSION["user_id"]) : ?>
																		<?php $u = $i->getTo();
																		echo $u->name . " " . $u->lastname; ?>
																	<?php endif; ?>
																	<small><i class="fa fa-clock-o"></i> 5 mins</small>

																</h4>
																<p><?php echo $i->message; ?></p>
															</a>
														</li>
													<?php endforeach; ?>

												</ul>
											</li>
											<li class="footer"><a href="./?view=messages&opt=all">Todos los mensajes</a></li>
										</ul>
									</li>-->
									<!---->
								<?php
								} else {
								}
								?>

							<?php endif; ?>

							<!-- User Account Menu -->
							<li class="dropdown user user-menu">
								<!-- Menu Toggle Button -->
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<!-- The user image in the navbar-->
									<!-- hidden-xs hides the username on small devices so only the image appears. -->

									<span class=""><?php if (isset($_SESSION["user_id"])) {
														echo UserData::getById($_SESSION["user_id"])->name;
														if (Core::$user->kind == 1) {
															echo " (Administrador)";
														} else if (Core::$user->kind == 2) {
															echo " (Almacenista)";
														}
														//else if(Core::$user->kind==3){ echo " (Vendedor)"; }

													} else if (isset($_SESSION["client_id"])) {
														echo PersonData::getById($_SESSION["client_id"])->name . " (Cliente)";
													} ?> <b class="caret"></b> </span>

								</a>
								<ul class="dropdown-menu">
									<?php if (isset($_SESSION["user_id"])) : ?>
										<li class="user-header">
											<?php
											if (Core::$user->image != "") {
												$url = "storage/profiles/" . Core::$user->image;
												if (file_exists($url)) {
													echo "<img src='$url' class='img-circle'>";
												}
											}
											?>

											<p>
												<?php echo Core::$user->name . " " . Core::$user->lastname; ?>
											</p>
										</li> <!-- The user image in the menu -->
										<li><a href="">Cambiar de usuario</a></li>
									<?php endif; ?>
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-right">
											<?php if (isset($_SESSION["user_id"])) : ?>
												<a href="./?view=profile" class="btn btn-default btn-flat">Mi Perfil</a>
											<?php endif; ?>
											<a href="./logout.php" class="btn btn-default btn-flat">Salir</a>
										</div>
									</li>
								</ul>
							</li>
							<!-- Control Sidebar Toggle Button -->
						</ul>
					</div>
				</nav>
			</header>
			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">

				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- Sidebar Menu -->
					<!--<?php echo Core::$user->kind; ?>-->
					<?php

					//print_r(Core::$user);

					$k = Core::$user->kind;

					include_once("core/app/view/env.php");
					$query = $mbd->prepare("SELECT distinct m.id, m.text, m.nivel, m.parent_id, m.link, m.orden, m.icon FROM menus as m, menus_entidades as me, user as e WHERE me.idMenu = m.id AND m.parent_id = 0 AND me.idUsuario = e.id and e.id = :dni_entidad ORDER BY m.orden ASC");
					$dni = $_GET['dni_usuario'];
					$query->bindParam(':dni_entidad', Core::$user->id);
					$query->execute();

					$array = array();
					$i = 0;

					//print_r($query->fetch(PDO::FETCH_ASSOC));

					while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
						//print_r($res);
						//$r = ($res['padre'] == '0') ? '#' : $res['padre'];
						$query2 = $mbd->prepare("SELECT m.id, m.text, m.nivel, m.parent_id, m.link, m.orden, m.icon FROM menus as m, menus_entidades as me, user as e WHERE me.idMenu = m.id AND m.parent_id = :id_padre AND me.idUsuario = e.id and e.id = :dni_entidad ORDER BY id");
						$dni = $_GET['dni_usuario'];
						$query2->bindParam(':dni_entidad', Core::$user->id);
						$query2->bindParam(':id_padre', $res['id']);
						$query2->execute();
						$hijos = array();
						while ($res2 = $query2->fetch(PDO::FETCH_ASSOC)) {
							$hijos[] = $res2;
						}
						$values[] = array(
							'id' => $res['id'],
							'parent' => $r,
							'text' => $res['text'],
							'icon' => $res['icon'],
							'link' => $res['link'],
							'hijos' => $hijos //array('value' => $res['link'])
						);
					}
					/*$JSON = json_encode($values);
                  echo $JSON;*/
					//print_r($values);

					?>
					<ul class="sidebar-menu">
						<?php
						$cc = 0;
						foreach ($values as $value) {
							if (count($value['hijos']) > 0) {
								$margin = '';
								/*if ($cc == 0) {
									$margin = 'margin-top: 60px;';
								} else {
									$margin = '';
								}*/
								$li = '<li class="treeview" style="' . $margin . '">
        								<a href="#"><i style="color: #5664cd;" class="' . $value['icon'] . '"></i> <span>' . $value['text'] . '</span> <i class="fa fa-angle-left pull-right"></i></a>
        								<ul class="treeview-menu">';
								foreach ($value['hijos'] as $hijo) {
									$li .= '<li><a href="' . $hijo['link'] . '"><i class="' . $hijo['icon'] . '"></i> <span>' . $hijo['text'] . '</span></a></a></li>';
								}
								$li .= '</ul>
        							</li>';
								echo $li;
								$cc++;
							} else {
								echo '<li><a href="' . $value['link'] . '"><i style="color: #5664cd;" class="' . $value['icon'] . '"></i> <span>' . $value['text'] . '</span></a></li>';
							}
						}
						?>
					</ul>

				</section>
				<!-- /.sidebar -->
			</aside>
		<?php endif; ?>

		<!-- Content Wrapper. Contains page content -->
		<?php if (isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])) : ?>
			<div class="content-wrapper">
				<?php View::load("index"); ?>
			</div><!-- /.content-wrapper -->

			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 2020
				</div>
				<!--<strong>Copyright &copy; 2018 <a href="http://www.solinsoft.com/" target="_blank">Solinsoft</a></strong>-->
			</footer>
		<?php else : ?>
			<?php if (isset($_GET["view"]) && $_GET["view"] == "clientaccess") : ?>
				<div class="login-box">
					<div class="login-logo">
						<a href="./"></a>
					</div><!-- /.login-logo -->
					<div class="login-box-body">
						<center>
							<h4><img src="img/logomedium.jpg"></h4>
						</center>
						<form action="./?action=processloginclient" method="post">
							<div class="form-group has-feedback">
								<input type="text" name="username" required class="form-control" placeholder="Usuario" />
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="password" name="password" required class="form-control" placeholder="Password" />
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>
							<div class="row">

								<div class="col-xs-12">
									<button type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button>
									<a href="./" class="btn btn-default btn-block btn-flat"><i class="fa fa-arrow-left"></i> Regresar</a>
								</div><!-- /.col -->
							</div>
						</form>
					</div><!-- /.login-box-body -->
				</div><!-- /.login-box -->
			<?php else : ?>
				<div class="row">
					<div class="col-md-4">
						<div class="login-box">
							<div class="login-box-body" style="min-height: 365px; background: transparent; color: white; font-weight: bold; font-size:18px; text-align: center;">
								<h2 style="text-align: center;">Misión</h2>
								<p style="text-align: justify; line-height: 30px; font-weight: bold;">
									Nos dedicamos al diseño, confección y comercialización de prendas de vestir satisfaciendo las necesidades de nuestros clientes, brindamos oportunidades a mujeres y personas que buscan la mejora continua.
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="login-box">
							<div class="login-logo">
								<a href="./"></a>
							</div><!-- /.login-logo -->
							<div class="login-box-body">
								<center>
									<h4><img src="img/logo-3.png" style="width: 100%;"></h4>
								</center>
								<form action="./?action=processlogin" method="post">
									<div class="form-group has-feedback">
										<input type="text" name="username" autocomplete="off" required class="form-control rounded-pill" placeholder="Usuario" />
										<span class="glyphicon glyphicon-user form-control-feedback"></span>
									</div>
									<div class="form-group has-feedback">
										<input type="password" name="password" autocomplete="off" required class="form-control rounded-pill" placeholder="Password" />
										<span class="glyphicon glyphicon-lock form-control-feedback"></span>
									</div>
									<div class="row">

										<div class="col-xs-12">
											<button type="submit" class="btn btn-primary rounded-pill btn-block btn-flat">Acceder</button>
											<!--
              <a href="./?view=clientaccess" class="btn btn-default btn-block btn-flat">Acceso al cliente <i class="fa fa-arrow-right"></i> </a>-->
										</div><!-- /.col -->
									</div>
								</form>
							</div><!-- /.login-box-body -->
						</div><!-- /.login-box -->
					</div>
					<div class="col-md-4">
						<div class="login-box">
							<div class="login-box-body" style="min-height: 365px; background: transparent; color: white; font-weight: bold; font-size:18px; text-align: center;">
								<h2 style="text-align: center;">Visión</h2>
								<p style="text-align: justify; line-height: 30px; font-weight: bold;">
									Ser reconocidos por las principales empresas como proveedores de prendas de vestir, con calidad en la confección y diseños al gusto del cliente.
								</p>
							</div>
						</div>
					</div>
				</div>

			<?php endif; ?>
		<?php endif; ?>


	</div><!-- ./wrapper -->

	<!-- REQUIRED JS SCRIPTS -->

	<!-- jQuery 2.1.4 -->
	<!-- Bootstrap 3.3.2 JS -->
	<script src="plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- AdminLTE App -->
	<script src="plugins/dist/js/app.min.js" type="text/javascript"></script>

	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
	<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

	<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>








	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".datatable").DataTable({
				dom: 'Bfrtip',
				buttons: [{
						extend: 'excelHtml5',
						exportOptions: {
							columns: [1, 2, 3, 5, 6, 7, 8, 9, 10]
						}
					},
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						exportOptions: {
							columns: [1, 2, 3, 5, 6, 7, 8, 9, 10]
						}
					},
					/*'colvis'*/
				],
				"language": {
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sSearch": "Buscar:",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "Primero",
						"sLast": "Último",
						"sNext": "Siguiente",
						"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				}
			});
		});
	</script>
	<!-- Optionally, you can add Slimscroll and FastClick plugins.
          Both of these plugins are recommended to enhance the
          user experience. Slimscroll is required when using the
          fixed layout. -->
</body>

</html>