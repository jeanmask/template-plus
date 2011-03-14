<?php 
    abstract class Kohana_Controller_Template extends Controller {
         public $template = 'template';
         public $auto_render = TRUE;
         
         public $style = array(); 	// array('src' => '', 'media' => 'all')
         public $script = array(); // array('src' => '', 'position' => 'after')
                  
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
                    $before[] = html::style($style['src'], array('media' => $style['media']) );
                }
                
                foreach(array_merge($this->default_script, $this->script) as $script) {
                    if( $script['position'] == 'before' )
                        $before[] = html::script($script['src']);
                    else
                        $after[] = html::script($script['src']);
                }
                
                $this->template->set('template_before', implode(PHP_EOL, $before) );
                $this->template->set('template_after', implode(PHP_EOL, $after) );
                
                $this->response->body($this->template->render());
            }
            
            return parent::after();
         }
    } 
?>
