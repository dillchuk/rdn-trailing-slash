<?php

namespace RdnTrailingSlash;

use Zend\Mvc\MvcEvent;

/**
 * Redirect if the path has a trailing slash.
 */
class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        if (PHP_SAPI == 'cli')
        {
            return;
        }

        $events = $event->getApplication()->getEventManager();
        $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), 1000);
    }

    public function onRoute(MvcEvent $event)
    {
        $application = $event->getApplication();
        /** @var \Zend\Http\PhpEnvironment\Request $req */
        $req = $application->getRequest();

        $uri = $req->getUri();
        $path = $uri->getPath();
        $basePath = $req->getBasePath();
        $scriptName = $req->getServer('SCRIPT_NAME');

        // Try to figure out (base) path relative to front controller
        if (strpos($path, $scriptName) !== false)
        {
            // Handle the case where the script name is included in the path.
            // For example: http://example.org/index.php/some/route
            $path = substr($path, strlen($scriptName));
        }
        else
        {
            $path = substr($path, strlen($basePath));
        }

        $isRoot = $path == '/';
        $hasTrailingSlash = substr($path, -1) == '/';
        if (!$isRoot && $hasTrailingSlash)
        {
            $uri->setPath(substr($uri->getPath(), 0, -1));

            /** @var \Zend\Http\PhpEnvironment\Response $response */
            $response = $application->getResponse();
            $response->setStatusCode(301);
            $response->getHeaders()->addHeaderLine('Location', $uri);

            return $response;
        }
    }
}
