<?
/**
 * Description of class-binnash-wpbookmark
 *
 * @author nur
 */
require_once ('class-binnash-fbalbum-config.php');
require_once ('facebook.php');
if (!class_exists('FbAlbum4Wordpress')) {
class FbAlbum4Wordpress extends Facebook{
    function FbAlbum4Wordpress(){
        $this->__construct();
    }
    function __construct() {
        $conf = FbAlbum4WordpressConfig::getInstance();
        register_activation_hook( FBALBUM4WP_DIR . '/' . 'fbalbum4wordpress.php', array(&$this,'activate') );        	
        register_deactivation_hook( FBALBUM4WP_DIR . '/' . 'fbalbum4wordpress.php', array(&$this,'deactivate') );
        add_action('admin_menu', array(&$this, 'adminMenu'));
        add_action('init', array(&$this, 'loadLibrary'));
        add_action('wp_ajax_fetch_fb_album_list', array(&$this, 'fetchFBAlbumList'));
        add_action('wp_ajax_binnash_get_album_links',array(&$this, 'fetchFBPhotos'));
        add_action('wp_ajax_nopriv_binnash_get_album_links',array(&$this, 'fetchFBPhotos'));
        if (is_admin()){
            if($conf->fb_app_id &&$conf->fb_secret_id)
                add_action('admin_notices', array(&$this,'check_fb_config'));
            add_filter('tiny_mce_version', array(&$this, 'refresh_mce'));            
            add_filter('mce_external_plugins', array(&$this, 'addlink_tinymce_plugin'));
            add_filter('mce_buttons', array(&$this, 'register_addlink_button'));            
        }
        $config['appId']  = $conf->fb_app_id;
        $config['secret'] = $conf->fb_secret_id;
        $config['fileUpload'] = false;
        $config['cookie'] = true;
        parent::__construct($config);
        add_shortcode('binnashfbalbum4wp', array(&$this, 'processShortcode'));
    }
    function fetchFBPhotos(){
        $conf = FbAlbum4WordpressConfig::getInstance();
        $this->setAccessToken($conf->access_token);
        $userId = $this->getUser();
        $return = array();
        if(isset($_REQUEST['album_id'])){
            try{
                $album = $this->api($_REQUEST['album_id'].'/photos');                   
                $count = 0;
                
                foreach ($album['data'] as $photo){
                    //var_dump($photo);
                    $return[$count]['n'] = $photo['images'][0]['source'];
                    $return[$count]['t'] = $photo['images'][3]['source'];
                    $return[$count]['name'] = $photo['name'];
                    $count++;
                }
            }catch (FacebookApiException $e){
                //no op
            }                               
        }            
        echo json_encode($return);
        exit(0);
    }
    function processShortcode($attrs, $contents, $codes){
        $conf = FbAlbum4WordpressConfig::getInstance();
        if(isset($attrs['album_id'])){        
            $albumId = $attrs['album_id'];
            ob_start();
            include ('album_loader.php');
            $content = ob_get_contents();
            ob_end_clean();			
            return $content;			                            
        }
        return $contents;
    }
    function fetchFBAlbumList(){
        $conf = FbAlbum4WordpressConfig::getInstance();
        $this->setAccessToken($conf->access_token);
        $userId = $this->getUser();
        $notLoggedIn = false;
        $loginUrl = 'admin.php?page=wp_fbalbum_manage&menu_id=settings';
        if($userId){
            try{
                $data =  $this->api('me/albums','GET');
                $albums = array();
                foreach($data['data'] as $album)
                    $albums[$album['id']] = $album['name'];
            }catch (FacebookApiException $e){
                $notLoggedIn = true;
            }
        }else{
            $notLoggedIn  = true;
        }                    
        ob_start();
        require_once('shortcode_form.php');
        $output = ob_get_contents();
        ob_end_clean();	            
        die($output);        
    }
    function register_addlink_button($buttons){
        array_push($buttons, "|", "binnashfbalbum");
        return $buttons;
    }
    function addlink_tinymce_plugin($plugin_array){
        $plugin_array['binnashfbalbum'] = FBALBUM4WP_URL.'/js/binnashfbalbum.tinymceplugin.js';
        return $plugin_array;
    }
    function refresh_mce($ver) {
        $ver += 4;
        return $ver;
    }            
    function check_fb_config(){
        $conf = FbAlbum4WordpressConfig::getInstance();
        if(empty($conf->fb_app_id) || empty($conf->fb_secret_id)){
            $msg = "<br/><div class = 'error'>Please Set Facebook APP ID and SECRET ID to Use FBAlbum for Wordpress .<br/>";
            $msg .= "Click <a href='admin.php?page=wp_fbalbum_manage&menu_id=settings'>here</a> to Set Them Now.</div>";
            echo $msg;
        }
    }
    function loadLibrary(){
        wp_enqueue_script('jquery');
        if(!is_admin()){
            wp_enqueue_style('bfbalbum', FBALBUM4WP_URL . '/css/bfbalbum.css'); 
            wp_enqueue_style('jquery.ad-gallery',  FBALBUM4WP_URL . '/css/jquery.ad-gallery.css'); 
            wp_enqueue_script('jquery.ad-gallery', FBALBUM4WP_URL . '/js/jquery.ad-gallery.js'); 
            wp_enqueue_script('jquery.easing.1.3', FBALBUM4WP_URL . '/js/jquery.easing.1.3.js'); 
        }
        if(is_admin()&&isset($_GET['page'])&&($_GET['page']=='wp_fbalbum_manage')){                
            wp_enqueue_style('bfbalbum-admin', FBALBUM4WP_URL . '/css/bfbalbum-admin.css'); 
        }
    }    
    function adminMenu(){
        add_options_page('FBAlbum4WP', 'FBAlbum4WP', 'manage_options', "wp_fbalbum_manage",array(&$this,'FbAlbum4WordpressHook'));
        add_submenu_page(__FILE__, 'FBAlbum4WP', 'FBAlbum4WP', 'add_users', 'wp_fbalbum_manage', array(&$this,'FbAlbum4WordpressHook'));        
    }
    function FbAlbum4WordpressHook(){
        $menuInfo = $this->drawMenu();
        $page_content = 'Content Not Found.';
        $menu = $menuInfo['menu'];
        switch ($menuInfo['current_menu_id']){
            case 'settings':
                $page_content = $this->settingsPage();
                break;
            default:
                $page_content = $this->managePage();
                break;
        }
        include_once ('fbalbum_manage.php');        
    }
    function managePage(){
        ob_start();
        include_once('manage_page.php');
        $content = ob_get_contents();
        ob_end_clean();			
        return $content;			                
    }
    
    function settingsPage(){        
        global $wpdb;
        $redirect_url = site_url(). '/wp-admin/admin.php?page=wp_fbalbum_manage&menu_id=settings';
        $conf = FbAlbum4WordpressConfig::getInstance();
        if(isset($_POST['op_edit_settngs'])){
            unset($_POST['op_edit_settngs']);				
            $fields = $_POST;
            $conf->updateConfig($_POST);
        }
        if(isset($_GET['binnash_fbalbum_logout'])){
            $this->destroySession();
            $conf->updateConfig(array('access_token'=>''));
        }
        if(isset($_GET['binnash_fbalbum_login'])){
            $conf->updateConfig(array('access_token'=>$this->getAccessToken()));
        }
        $this->setAccessToken($conf->access_token);
        $params = array(
            scope => 'user_photos, friends_photos,offline_access',
            redirect_uri => $redirect_url . '&binnash_fbalbum_login=1'
        );            
        $userId = $this->getUser();
        if($userId){
            try{
                $user = $this->api('/me','GET');

                $logoutUrl = $this->getLogoutUrl(
                        array('next'=>$redirect_url . '&binnash_fbalbum_logout=1'));
            }
            catch(FacebookApiException $e){
                $loginUrl = $this->getLoginUrl($params);
            }        
        }else{
           $loginUrl = $this->getLoginUrl($params);
        }        
        $fields['fb_app_id'] = $conf->fb_app_id;
        $fields['fb_secret_id'] =$conf->fb_secret_id; 
        ob_start();
        include_once('settings_page.php');
        $content = ob_get_contents();
        ob_end_clean();			
        return $content;			        
    }
    function drawMenu(){        
        $menuInfo = FbAlbum4WordpressConfig::menu();        
        $menuIds = array_keys($menuInfo);
        $requestedMenu = isset($_GET['menu_id'])? $_GET['menu_id']: 'settings';        
        $currentMenu = in_array($requestedMenu, $menuIds)? $requestedMenu: 'settings';
        $menu = '<ul class="binnash-fbalbum-submenu">';
        foreach($menuInfo as $key=>$value){
            $menu .= "<li ";			     
            if($currentMenu ==$key) $menu .= 'class="current"';
            $menu .= '><a href="'.$value['link'].'">'.$value['title'].'</a></li>';
        }   				
        $menu .='</ul>';
        return array('menu'=>$menu,'current_menu_id'=>$currentMenu);
    }
    
    function activate(){
        $conf = FbAlbum4WordpressConfig::getInstance();
        if(!isset($conf->fb_app_id))
            $conf->updateConfig(array('fb_app_id'=>'','fb_app_secret'=>''));        
        
    }
    function deactivate(){
        
    }
}
}