<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Cache\CacheInterface;

class ThrottleFilter implements FilterInterface
{
    protected $cache;
    
    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }
    
    public function before(RequestInterface $request, $arguments = null)
    {
        // Default values
        $maxRequests = 60;  // requests
        $timeWindow = 60;   // seconds
        
        // Override with arguments if provided
        if ($arguments) {
            if (isset($arguments['max_requests'])) {
                $maxRequests = (int) $arguments['max_requests'];
            }
            if (isset($arguments['time_window'])) {
                $timeWindow = (int) $arguments['time_window'];
            }
        }
        
        // Get client IP
        $ip = $request->getIPAddress();
        
        // Generate cache key
        $cacheKey = 'throttle_' . md5($ip . '_' . $request->getUri()->getPath());
        
        // Get current request count
        $requests = $this->cache->get($cacheKey);
        
        if ($requests === null) {
            // First request in time window
            $requests = [
                'count' => 1,
                'first_request' => time()
            ];
            $this->cache->save($cacheKey, $requests, $timeWindow);
        } else {
            // Check if time window has expired
            if ((time() - $requests['first_request']) > $timeWindow) {
                // Reset counter
                $requests = [
                    'count' => 1,
                    'first_request' => time()
                ];
                $this->cache->save($cacheKey, $requests, $timeWindow);
            } else {
                // Increment counter
                $requests['count']++;
                $this->cache->save($cacheKey, $requests, $timeWindow - (time() - $requests['first_request']));
                
                // Check if limit exceeded
                if ($requests['count'] > $maxRequests) {
                    // Calculate retry after time
                    $retryAfter = $requests['first_request'] + $timeWindow - time();
                    
                    // Set rate limit headers
                    $response = service('response');
                    $response->setHeader('Retry-After', $retryAfter);
                    $response->setHeader('X-RateLimit-Limit', $maxRequests);
                    $response->setHeader('X-RateLimit-Remaining', max(0, $maxRequests - $requests['count']));
                    $response->setHeader('X-RateLimit-Reset', $requests['first_request'] + $timeWindow);
                    
                    // Return 429 Too Many Requests
                    $response->setStatusCode(429);
                    $response->setJSON([
                        'error' => true,
                        'message' => 'Terlalu banyak permintaan. Silakan coba lagi dalam ' . $retryAfter . ' detik.'
                    ]);
                    
                    return $response;
                }
            }
        }
        
        // Set rate limit headers for successful request
        $response = service('response');
        $response->setHeader('X-RateLimit-Limit', $maxRequests);
        $response->setHeader('X-RateLimit-Remaining', max(0, $maxRequests - $requests['count']));
        $response->setHeader('X-RateLimit-Reset', $requests['first_request'] + $timeWindow);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after request
    }
}