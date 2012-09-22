<?php

/**
 * ProcessWire 2.x Admin Markup Template / Copyright 2010 by Ryan Cramer
 *
 * ProcessWire Ergo Admin Markup Template 1.0 / Copyright 2012 by Nikola Vidoni  
 *
 */

Debug::timer('ergo');

$searchForm = $user->hasPermission('page-edit') ? $modules->get('ProcessPageSearch')->renderSearchForm() : '';
$bodyClass = $input->get->modal ? 'modal' : '';
if(!isset($content)) $content = '';

$config->styles->prepend($config->urls->adminTemplates . "styles/main.css?v=2");
$config->styles->append($config->urls->adminTemplates . "styles/inputfields.css");
$config->styles->append($config->urls->adminTemplates . "styles/ui.css?v=2");
$config->scripts->append($config->urls->adminTemplates . "scripts/jquery.cookie.js");
$config->scripts->append($config->urls->adminTemplates . "scripts/jquery.tiptip.js");
$config->scripts->append($config->urls->adminTemplates . "scripts/inputfields.js");
$config->scripts->append($config->urls->adminTemplates . "scripts/main.js?v=2");

$browserTitle = wire('processBrowserTitle'); 
if(!$browserTitle) $browserTitle = __(strip_tags($page->get('title|name')), __FILE__) . ' :: ProcessWire';

/*
 * Dynamic phrases that we want to be automatically translated
 *
 * These are in a comment so that they register with the parser, in place of the dynamic __() function calls with page titles. 
 * 
 * __("Pages"); 
 * __("Setup"); 
 * __("Modules"); 
 * __("Access"); 
 * __("Admin"); 
 * 
 */

?>
<!DOCTYPE html>
<html lang="<?php echo __('en', __FILE__); // HTML tag lang attribute
	/* this intentionally on a separate line */ ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $browserTitle; ?></title>

	<script type="text/javascript">
		<?php

		$jsConfig = $config->js();
		$jsConfig['debug'] = $config->debug;
		$jsConfig['urls'] = array(
			'root' => $config->urls->root, 
			'admin' => $config->urls->admin, 
			'modules' => $config->urls->modules, 
			'core' => $config->urls->core, 
			'files' => $config->urls->files, 
			'templates' => $config->urls->templates,
			'adminTemplates' => $config->urls->adminTemplates,
			); 
		?>

		var config = <?php echo json_encode($jsConfig); ?>;
	</script>

	<?php foreach($config->styles->unique() as $file) echo "\n\t<link type='text/css' href='$file' rel='stylesheet' />"; ?>

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="<?php echo $config->urls->adminTemplates; ?>styles/ie.css" />
	<![endif]-->	

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo $config->urls->adminTemplates; ?>styles/ie7.css" />
	<![endif]-->

	<?php foreach($config->scripts->unique() as $file) echo "\n\t<script type='text/javascript' src='$file'></script>"; ?>

	<script>
		// overwrite TinyMCE skin setting globally
		// as defined in /wire/modules/Inputfields/InputfieldTinyMCE/InputfieldTinyMCE.js
		// and loaded before
		if('undefined' != typeof InputfieldTinyMCEConfigDefaults) InputfieldTinyMCEConfigDefaults.skin = "default";
	</script>

</head>

<?php if($user->isGuest()) { ?>

<body class="login">

    <div id="loginBox">
    
    	<div class="header">
        	<div class="logo">ProcessWire</div>
        </div>
    
        <?php echo $content?>

    </div>
    
    <?php if(count($notices)) include($config->paths->adminTemplates . "notices.inc"); ?>

</body>

<?php } else { ?>

<body<?php if($bodyClass) echo " class='$bodyClass'"; ?>>

<!-- Wrapper
-------------------------------------------------------------------->

<div id="wrapper">

    <!-- Toggle Sidebar On/Off
    -------------------------------------------------------------------->
    
    <a id="toggle">Collapse Sidebar</a>
    
    <!-- Sidebar
    -------------------------------------------------------------------->

    <div id="sidebar">
    
        <h2>Latest updates</h2>
    
        <?php $updates = $pages->find('limit=3, sort=-modified');
        
        echo "<ul>";
        
		foreach($updates as $update) {
			if ($update->editable()) {
				echo "<li><a href='".$config->urls->admin."page/edit/?id={$update->id}'><span class='date'>". date('d.m.', $update->modified) ."</span> " . $update->title . "</a></li>\n";
			}	
		}
		
		echo "</ul>";
		
		?>  
        
        <h2>Newest added</h2>
        
        <?php $newest = $pages->find('limit=3, sort=-created');
        
        echo "<ul>";
        
		foreach($newest as $new) {
			if ($new->editable()) {
				echo "<li><a href='".$config->urls->admin."page/edit/?id={$new->id}'><span class='date'>". date('d.m.', $new->created) ."</span> " . $new->title . "</a></li>\n";
			}
		}
		
		echo "</ul>";
		
		?>

	</div><!-- Sidebar END-->

	<!-- Content
    -------------------------------------------------------------------->
    
    <div id="content" class="content">
	
        <!-- Container
        -------------------------------------------------------------------->
            
        <div class="container">
    
            <!-- Left
            -------------------------------------------------------------------->
                
            <div class="left">
    
                <!-- Logo
                -------------------------------------------------------------------->
                
                <p id="logo">ProcessWire</p>
                
                <!-- Menu
                -------------------------------------------------------------------->
                
                <ul id="nav" class="menu accordion">
                    <?php include($config->paths->templatesAdmin . "topnav.inc"); ?>
                </ul>
    
            </div><!-- Left END-->
            
            <!-- Right
            -------------------------------------------------------------------->
                
            <div class="right">
            
                <!-- Header
                -------------------------------------------------------------------->
                
                <div id="header" class="header">
                    
                    <!-- Logged User
                    -------------------------------------------------------------------->
                    
                    <div id="user">You are logged in as <span><?php echo $user->name?></span></div>
                    
                    <!-- Search
                    -------------------------------------------------------------------->
                    
                    <div id="search"><?php echo tabIndent($searchForm, 3); ?></div>
        
                    <!-- User Info
                    -------------------------------------------------------------------->
                    
                    <?php if(!$user->isGuest()): ?>
            
                    <ul id="controls">
        
                        <?php if($user->hasPermission('profile-edit')): ?>
                        <li><a class="tip profile" href="<?php echo $config->urls->admin; ?>profile/" title="<?php echo ucfirst (__('profile', __FILE__)); ?>"><?php echo ucfirst (__('profile', __FILE__)); ?></a></li>
                        <?php endif; ?>
            
                        <li><a class="tip view" href="<?php echo $config->urls->root; ?>" title="<?php echo ucfirst (__('Site', __FILE__)); ?>"><?php echo ucfirst (__('Site', __FILE__)); ?></a></li>
            
                        <li><a class="tip logout" href="<?php echo $config->urls->admin; ?>login/logout/" title="<?php echo ucfirst (__('logout', __FILE__)); ?>"><?php echo ucfirst (__('logout', __FILE__)); ?></a></li>
              
                    </ul>
                
                    <?php endif; ?>

                    <!-- Breadcrumbs
                    -------------------------------------------------------------------->
        
                    <?php if(!$user->isGuest()) {
                    
                    echo "<ul id='breadcrumbs'>";
                    foreach($this->fuel('breadcrumbs') as $breadcrumb) {
                            $title = __($breadcrumb->title, __FILE__); 
                            echo "\n\t\t\t\t<li><a href='{$breadcrumb->url}'>{$title}</a></li>";
                        }
                    echo "</ul>";
    
                    } ?>
                    
                    <!-- Title
                    -------------------------------------------------------------------->
                
                </div><!-- Header END-->
                
                <h1 class="title"><?php echo __(strip_tags($this->fuel->processHeadline ? $this->fuel->processHeadline : $page->get("title|name")), __FILE__); ?></h1>
    
                <!-- Notices
                -------------------------------------------------------------------->
    
                <?php if(count($notices)) include($config->paths->adminTemplates . "notices.inc"); ?>
        
                <?php if(trim($page->summary)) echo "<h2>{$page->summary}</h2>"; ?>
                
                <?php if($page->body) echo $page->body; ?>
    
                <?php echo $content ?>
    
                <?php if($config->debug && $this->user->isSuperuser()) include($config->paths->adminTemplates . "debug.inc"); ?>
    
                <!-- Footer
                -------------------------------------------------------------------->
    
                <div id="footer" class="footer">
                    
                    <div class="container">
                        
                        <div id="left">
                            
                            <a href="http://www.processwire.com">ProcessWire <?php echo $config->version . ' <!--v' . $config->systemVersion; ?>--></a> <span>/</span> Copyright &copy; <?php echo date("Y"); ?> by Ryan Cramer
                        
                        </div>
                        
                        <div id="right">
                        
                            <div id="code">
                            
                                <?php echo "This page was created in <span>". Debug::timer('ergo') ."</span> seconds"; ?>
                            
                            </div>
                        
                            <div id="top"><a href="#">Top</a></div>
                        
                        </div>
                
                    </div>
                
                </div><!-- Footer END-->
                
			</div><!-- Right END-->
            
            <div class="clear"></div>
    
		</div><!-- Container END-->

	</div><!-- Content END-->

</div><!-- Wrapper END-->

</body>

<?php } ?>

</html>