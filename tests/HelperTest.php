<?php

namespace Pop\Utils\Test;

use Pop\Utils\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{

    public function testLoadFunctions()
    {
        Helper::loadFunctions();
        $this->assertTrue(function_exists('app_date'));
        $this->assertTrue(function_exists('str_slug'));
        $this->assertTrue(function_exists('str_random'));
        $this->assertTrue(function_exists('str_random_alpha'));
        $this->assertTrue(function_exists('str_random_num'));
        $this->assertTrue(function_exists('str_random_alphanum'));
        $this->assertTrue(function_exists('str_from_camel'));
        $this->assertTrue(function_exists('str_to_camel'));
        $this->assertTrue(function_exists('str_title_case'));
        $this->assertTrue(function_exists('str_snake_case'));
        $this->assertTrue(function_exists('str_kebab_case'));
    }

    public function testAppDate()
    {
        $time = time();
        $this->assertTrue(function_exists('app_date'));
        $this->assertEquals(date('Y-m-d H:i:s', $time), app_date('Y-m-d H:i:s', $time));
    }

    public function testAppDateUTC()
    {
        $time = time();
        $this->assertEquals(gmdate('Y-m-d H:i:s', $time), app_date('Y-m-d H:i:s', $time, 'APP_TIMEZONE', 'UTC'));
    }

    public function testAppDateTimezone()
    {
        $time = time();
        $this->assertEquals(gmdate('Y-m-d H:i:s', $time), app_date('Y-m-d H:i:s', $time + 3600, 'APP_TIMEZONE', '-1'));
    }

    public function testStrSlug()
    {
        $this->assertEquals('seo-title', str_slug('SEO Title'));
    }

    public function testStrRandom()
    {
        $this->assertEquals(6, strlen(str_random(6)));
    }

    public function testStrRandomAlpha()
    {
        $this->assertEquals(0, preg_match('/[0123456789!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', str_random_alpha(6)));
    }

    public function testStrRandomNum()
    {
        $this->assertEquals(1, preg_match('/[0123456789]/', str_random_num(6)));
    }

    public function testStrRandomAlphaNum()
    {
        $this->assertEquals(0, preg_match('/[!?#$%&@\-\_\+\*=\,\.:;\(\)\[\]\{\}]/', str_random_alphanum(6)));
    }

    public function testStrFromCamel()
    {
        $this->assertEquals('some-string', str_from_camel('SomeString'));
    }

    public function testStrToCamel()
    {
        $this->assertEquals('someString', str_to_camel('some-string', '-'));
    }

    public function testStrTitleCase()
    {
        $this->assertEquals('SomeString', str_title_case('some-string', '-'));
    }

    public function testStrSnakeCase()
    {
        $this->assertEquals('some_string', str_snake_case('SomeString'));
    }

    public function testStrKebabCase()
    {
        $this->assertEquals('some-string', str_kebab_case('SomeString'));
    }

}
