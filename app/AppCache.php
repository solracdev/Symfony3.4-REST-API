<?php

use FOS\HttpCache\SymfonyCache\CacheInvalidation;
use FOS\HttpCache\SymfonyCache\CustomTtlListener;
use FOS\HttpCache\SymfonyCache\DebugListener;
use FOS\HttpCache\SymfonyCache\EventDispatchingHttpCache;
use FOS\HttpCache\SymfonyCache\PurgeListener;
use FOS\HttpCache\SymfonyCache\RefreshListener;
use FOS\HttpCache\SymfonyCache\UserContextListener;
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class AppCache extends HttpCache implements CacheInvalidation {

    use EventDispatchingHttpCache;

    public function __construct(KernelInterface $kernel, $cacheDir = null) {
        parent::__construct($kernel, $cacheDir);
        
        $this->addSubscriber(new CustomTtlListener());
        $this->addSubscriber(new PurgeListener());
        $this->addSubscriber(new RefreshListener());
        $this->addSubscriber(new UserContextListener());
        
        if (isset($option["debug"]) && $option["debug"]) {
            
            $this->addSubscriber(new DebugListener());
        }
    }
    
    protected function invalidate(Request $request, $catch = false) {
        
        if ("PURGE" != $request->getMethod()) {

            return parent::invalidate($request, $catch);
        }
        
        $response = new Response();
        
        if ($this->getStore()->purge($request->getUri())) {
            
            $response->setStatusCode(200, "Purged");
            
        }else {
            
            $response->setStatusCode(400 , "Not Found");
        }
       
        return $response;
    }

    public function fetch(Request $request, $catch = false) {

        return parent::fetch($request, $catch);
    }

}
