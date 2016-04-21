<?php 

namespace DxsRavel\Essentials\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\ServiceProvider as ServiceProvider;

use Storage;
use Form;

class ExtendFormBuilderServiceProvider extends  ServiceProvider {

    //boot 
    public function boot()
    {
        Form::macro('selectGrouped',function($name, $list = array(), $selected = null, $options = array()){
            
            $selected = $this->getValueAttribute($name, $selected);
            $options['id'] = $this->getIdAttribute($name, $options);
            if ( ! isset($options['name'])) $options['name'] = $name;
            $groups = array();            
            foreach($list as $idg => $Group){
                $group_tag = array();
                $group_tag[] = '<optgroup value="'.$idg.'" label="'. $Group['label'] .'">';
                foreach($Group['values'] as $v => $o){
                    $group_tag[] = $this->getSelectOption($o, $v, $selected);
                }
                $group_tag[] = 'optgroup';

                $groups[] = implode('',$group_tag);
            }
            $options = $this->html->attributes($options);
            $html = implode('', $groups);
            return "<select{$options}>{$html}</select>";            
        });    

    }//end boot 

     public function register()
    {
        //
    }


}