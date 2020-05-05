<?php

use App\UrlHelper;
use PHPUnit\Framework\TestCase;

class UrlHelperTest extends TestCase {
    
    public function assertURLEquals($expected, $url) {
        $this->assertEquals($expected, urldecode($url));
    }
    public function testWithParam() {
        $url = UrlHelper::withParam([], 'k', 3);
        $this->assertEquals("k=3", $url);
    }

    public function testWithParamWithArray() {
        $url = UrlHelper::withParam([], 'k', [3,2,1]);
        $this->assertURLEquals("k=3,2,1", $url);
    }

    public function testWithParams() {
        $url = UrlHelper::withParams(['b' => 5], ['b' => 6, 'c' => 7]);
        $this->assertURLEquals('b=6&c=7', $url);
    }

    public function testWithParamsWithArray() {
        $url = UrlHelper::withParams(['a' => 5], ['a' => [5,6], 'c' => 7]);
        $this->assertURLEquals('a=5,6&c=7', $url);
    }


}