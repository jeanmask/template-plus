<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Plus_Controller_Template extends Controller {
     public static $default_style_media = 'screen';
     public static $default_script_position = 'before';

     public $style = array(); 	// array('src' => '', 'media' => 'all')
     public $script = array(); // array('src' => '', 'position' => 'after')
      
     public $template = 'template';
     public $auto_render = TRUE;
              
     public $default_style = array();
     public $default_script = array();
     
     public function before() {
        if( $this->auto_render === TRUE ) {
            $this->template = View::factory($this->template);
        }
        
        return parent::before();
     }
   
     public function after() {
     
        if( $this->auto_render === TRUE ) {
            
            $before = array();
            $after = array();
            
            foreach(array_merge($this->default_style, $this->style) as $style) {
                if(!array_key_exists('media', $style)) {
                  $style['media'] = self::$default_style_media;
                }

                $before[] = html::style($style['src'], array('media' => $style['media']) );
            }
            
            foreach(array_merge($this->default_script, $this->script) as $script) {
                if(!array_key_exists('position',$script)) {
                  $script['position'] = self::$default_script_position;
                }

                if( $script['position'] == 'before' ) {
                    $before[] = html::script($script['src']);
                }
                else {
                    $after[] = html::script($script['src']);
                }
            }
            
            $this->template->set('template_before', implode(PHP_EOL, $before) );
            $this->template->set('template_after', implode(PHP_EOL, $after) );
            
            $this->response->body($this->template->render());
        }
        
        return parent::after();
     }
}
