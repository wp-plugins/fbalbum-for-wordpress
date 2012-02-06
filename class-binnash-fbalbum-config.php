<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-binnash-fbalbum-config
 *
 * @author nur
 */
class FbAlbum4WordpressConfig {
    static $_this;
    function FbAlbum4WordpressConfig(){
        $this->__construct();
    }
    function __construct(){
        $this->options = get_option('wpbinnashfbalbum_options');
        if(!empty($this->options))
        foreach($this->options as $key=>$value)$this->$key = $value;        
    }
    static function getInstance(){
        if(null === self::$_this){
            self::$_this = new FbAlbum4WordpressConfig();        
        }        
        return self::$_this;
    }
    function updateConfig($options){
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
            $this->$key = $value;
        }
        update_option('wpbinnashfbalbum_options', $this->options);
    }    
    static function menu(){
        $urlPrefix = 'admin.php?page=wp_fbalbum_manage&menu_id=';
        return array(
            /*'manage'=>array('title'=>'Manage',
                            'link'=>$urlPrefix.'manage'
                           ),*/
            'settings'=>array('title'=>'Settings',
                              'link'=>$urlPrefix.'settings')
        );
    }    
}
?>
