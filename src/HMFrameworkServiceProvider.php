<?php

namespace SiranixSociety\HMFramework;

use Illuminate\Support\ServiceProvider;

class HMFrameworkServiceProvider extends ServiceProvider{
    protected $defer = false;

    public function boot(){
        $TimeStamp = date('Y_m_d_His', time());
    }

    public function register(){
        $this->commands([
            'SiranixSociety\HMFramework\Commands\TestCommand',
            'SiranixSociety\HMFramework\Commands\InstallCommand',
        ]);
    }
}