<?php

namespace Domora\TvGuide\Service;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class SecurityRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        return $request->getMethod() != 'GET';
    }
}