<?php
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

$request = $app->getRequest();
if(isset($routeName)){
    $routeName = $routeName;
}else{
    $routeName = $request->get('_route');
}
$username = '';
$fullname = '';
$menuHtml = '';
$pageTitle = '';
$sessionLogin = $app->getSession()->get('profile');
if(is_object($sessionLogin)){
	$username = isset($sessionLogin->username) ? $sessionLogin->username : '';
	$fullname = isset($sessionLogin->fullname) ? $sessionLogin->fullname : '';
	$menuHtml = isset($sessionLogin->menu) ? $sessionLogin->menu : '';
	$pageTitle = isset($sessionLogin->pageTitle) ? $sessionLogin->pageTitle : '';
}
?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 3.7.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Metronic | Admin Dashboard Template</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME STYLES -->
<!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
<link href="/web/theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="/web/theme/assets/admin/layout4/css/themes/<?php echo $themeColor; ?>.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="/web/theme/assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<!-- Css for each page -->
<?php $view['slots']->output('css',false) ?>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- END THEME STYLES -->
<!-- <link rel="shortcut icon" href="favicon.ico"/>  -->
<link rel="icon" type="image/png" href="/web/img/fa-cafe.png" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo">
<!-- BEGIN HEADER -->
	<?php 
  		echo $view['actions']->render(
			new ControllerReference('SmartCafeAdminBundle:PageHeader:view', array())
		); 
  	?>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<?php echo $menuHtml; ?>
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEAD -->
			
			<!-- END PAGE HEAD -->
			<!-- BEGIN PAGE BREADCRUMB -->
			<?php 
		  		echo $view['actions']->render(
					new ControllerReference('SmartCafeAdminBundle:PageBreadCrumb:view', array('routeName' => $routeName))
				); 
		  	?>
			<!-- END PAGE BREADCRUMB -->
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row margin-top-10">
			
			</div>
			<?php $view['slots']->output('body') ?>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2014 &copy; Metronic by keenthemes.
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/web/theme/assets/global/plugins/respond.min.js"></script>
<script src="/web/theme/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="/web/theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/web/theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/web/theme/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/web/theme/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/web/theme/assets/admin/pages/scripts/ui-blockui.js"></script>
<script src="/web/theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="/web/theme/assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="/web/theme/assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>
<script src="/web/theme/assets/admin/pages/scripts/ui-alert-dialog-api.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- Js for each page -->
<?php $view['slots']->output('js',false) ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   //QuickSidebar.init(); // init quick sidebar
   UIBlockUI.init(); // init blockui
   UIAlertDialogApi.init();
	//config selected item of menu
   	var pathname = window.location.pathname;
  	pathname = pathname.replace(new RegExp('/', 'g'), '_');
   	pathname = pathname.replace('.', '_');
   	var level = $('.'+pathname).attr('level');
   	var obj = $('.'+pathname);
   	obj.addClass('active');
	var checkLevel = level;
	while (true) {
		obj = obj.parent();
    	var lvObj = obj.attr('level');
    	if(lvObj == 1){
    		obj.addClass('start active');
    		var objA = obj.find('a');
    		objA.append('<span class="selected"></span>');
			break;
        }
     	var tagName = obj[0].tagName.toLowerCase();
     	if(tagName == 'ul'){
       		obj.css('display', 'block');
     	}
     	if(tagName == 'li'){
       		obj.addClass('open');
     	}
     	obj.find('span').addClass('open');
	}
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>