<?php

namespace Unimatrix\Frontend\Http\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Http\Middleware\CsrfProtectionMiddleware as CakeCsrfProtectionMiddleware;

/**
 * CSRF Protection
 * Wrapper of the original cake CSRF Protection middleware with the addition
 * of being able to skip verification for configured `exceptions` (controller and actions)
 * You can skip a whole controller or just single controller action
 *
 * Example: (app.php)
 * ---------------------------------------------------------------------------------
 * 'Frontend' => [
 *     'security' => [
 *         'skip' => [
 *             ['controller' => 'API', 'action' => 'batch'], // skip the batch action from the API controller
 *             ['controller' => 'Amazon'], // skip the whole amazon controller
 *             ['plugin => 'Rest'] // skip the whole REST plugin
 *         ]
 * ---------------------------------------------------------------------------------
 *
 * @author Flavius
 * @version 2.0
 */
class CsrfProtectionMiddleware extends CakeCsrfProtectionMiddleware
{
    /**
     * Constructor
     *
     * @param array $config Config options. See $_defaultConfig for valid keys.
     */
    public function __construct(array $config = []) {
        parent::__construct($config + [
            'httpOnly' => true,
            'secure' => env('HTTPS'),
            'cookieName' => 'frontend_csrf_token'
        ]);
    }

    /**
     * {@inheritDoc}
     * @see \Cake\Http\Middleware\CsrfProtectionMiddleware::__invoke()
     */
    public function __invoke(ServerRequest $request, Response $response, $next) {
        // compute skips & params
        $skips = [];
        $params = $request->getAttribute('params');
        if(Configure::check('Frontend.security.skip'))
            $skips = Configure::read('Frontend.security.skip');

        // got a match? skip CSRF :)
        foreach($skips as $skip)
            if(array_intersect_assoc($params, $skip) === $skip)
                return $next($request, $response);

        // continue normally
        return parent::__invoke($request, $response, $next);
    }
}
