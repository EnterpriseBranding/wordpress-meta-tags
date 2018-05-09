<?php


//namespace DivPusher\MetaTags;


class AdminNotice{

    private $type;

    private $message;

    private $dismissible;

    private $transient;



    public function __construct(){
        $this->type = 'notice-info';
        $this->dismissible = true;
    }



    public function setType($type) {
        $allowedTypes = array('notice-error', 'notice-warning', 'notice-success', 'notice-info');
        if(!in_array($type, $allowedTypes)) {
            $type = 'notice-info';
        }

        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    
    
    public function setMessage($message) {
        $allowedHtml = array(
            'a' => array(
                'href' => array(),
                'target' => array(),
                'title' => array(),
                'class' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            'i' => array(),
            'span' => array(
                'class' => array()
            )
        );

        $this->message = wp_kses($message, $allowedHtml); 
    }

    public function getMessage() {
        return $this->message;
    }



    public function setDismissible($bool) {
        if(is_bool($bool) === true) {
            $this->dismissible = $bool;
        }         
    }

    public function getDismissible() {
        return $this->dismissible;
    }




    public function setTransient($transient) {
        if(strlen($transient) > 172){
            trigger_error( 
                'Transient must be less than 172 characters. Only lowercase english letters, numbers and underscore character are allowed!', 
                E_USER_ERROR
            );

            return;
        }

        $transient = preg_replace( '/[^a-z0-9_]+/', '', strtolower($transient) );

        $this->transient = $transient;             
    }

    public function getTransient() {
        return $this->transient;
    }



    //display notices on certain pages
    public function onPage($pages) {
        
        if(empty($this->message)) {
            trigger_error( 
                'A message must be set before calling this method!', 
                E_USER_ERROR
            );

            return;
        }

        $pages = func_get_args();

        add_action( 'admin_notices', function() use( $pages ) {

            $screen = get_current_screen();
            foreach((array)$pages as $page) {
                if($page == $screen->parent_file) {
                    $class = $this->type;

                    if($this->dismissible){
                        $class .= ' is-dismissible';
                    }

                    echo '<div class="notice '. $class .'">
                    <p>'. $this->message .'</p>            
                    </div>';   
                }
            }

        });

    }



    //display notice on plugin activation
    public function onPluginActivation($pluginFile) {

        if(empty($this->message)) {
            trigger_error( 'A message must be set before calling this method!', E_USER_ERROR );

            return;
        }

        if(empty($this->transient)) {
            trigger_error( 'A transient must be set before calling this method!', E_USER_ERROR );

            return;
        }


        register_activation_hook( $pluginFile, function() {
            set_transient( $this->transient, true, 5 );
        });         
         

        add_action( 'admin_notices', function() { 

            if( get_transient( $this->transient ) ){
                $class = $this->type;

                if($this->dismissible){
                    $class .= ' is-dismissible';
                }

                echo '<div class="notice '. $class .'">
                    <p>'. $this->message .'</p>            
                </div>';
                
                delete_transient( $this->transient );
            }

        });
    }



    //display notice on theme activation
    public function onThemeActivation($themeName = null) {
        
        if(empty($this->message)) {
            trigger_error( 'A message must be set before calling this method!', E_USER_ERROR );

            return;
        }


        add_action( 'after_switch_theme', function() use($themeName){

            //check theme if set
            if(!empty($themeName)){
                $activeTheme = wp_get_theme();
                if($activeTheme->get('Name') != $themeName){
                    return;
                }
            }

            add_action( 'admin_notices', function() { 

                $class = $this->type;

                if($this->dismissible){
                    $class .= ' is-dismissible';
                }

                echo '<div class="notice '. $class .'">
                    <p>'. $this->message .'</p>            
                </div>';
                    
            });


        });

    }

}