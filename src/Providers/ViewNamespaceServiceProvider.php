<?php 
namespace DxsRavel\Essentials\Providers;

use Illuminate\Support\ServiceProvider as ServiceProvider;

use View;

class ViewNamespaceServiceProvider extends ServiceProvider {

    //boot 
    public function boot(){
         View::addNamespace('DxsRavel', base_path('vendor/dxsravel/essentials/views') );
    }//end boot 

    public function register(){
        //
    }


}