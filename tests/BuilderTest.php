<?php

namespace Thumbor\Tests;

use PHPUnit\Framework\TestCase;
use Thumbor\Builder;

class BuilderTest extends TestCase
{
    /**
     * @dataProvider thumborDataProvider
     *
     * @param string $server
     * @param string $secret
     * @param string $url
     * @param array  $options
     * @param string $expected_url
     */
    public function testBuildImageUrl(string $server, string $secret, string $url, array $options, string $expected_url)
    {
        $builder = new Builder($server, $secret);
        $builder->url($url);

        foreach ($options as $option => $args) {
            $builder->{$option}(...$args);
        }

        $this->assertSame($expected_url, $builder->build());
        $this->assertSame($expected_url, (string) $builder);
    }

    public function thumborDataProvider()
    {
        return [
            'crop the image size to 100x100:100x100 pixels' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
                'options' => ['crop' => [100, 200, 300, 400]],
                'expected_url' => 'https://cdn.example.com/unsafe/100x200:300x400/https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
            ],
            'resize the image size in 200x100 pixels ' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
                'options' => ['resize' => [100, 200]],
                'expected_url' => 'https://cdn.example.com/unsafe/100x200/https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
            ],
            'resize the image size with encrypted URL' => [
                'server' => 'https://cdn.example.com',
                'secret '=> 'abc',
                'url' => 'https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
                'options' => ['resize' => [100, 200]],
                'expected_url' => 'https://cdn.example.com/1xEMQmnEPSnZKCfW-3JhlCpHkoM=/100x200/https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
            ],
            'fit the image in an imaginary box by 200x100 pixels' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
                'options' => ['fitIn' => [100, 200]],
                'expected_url' => 'https://cdn.example.com/unsafe/fit-in/100x200/https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
            ],
            'full-fit the image in an imaginary box by 200x100 pixels' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
                'options' => ['fullFitIn' => [100, 200,]],
                'expected_url' => 'https://cdn.example.com/unsafe/full-fit-in/100x200/https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
            ],
            'trim the image without surrounding space' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
                'options' => ['trim' => []],
                'expected_url' => 'https://cdn.example.com/unsafe/trim/https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
            ],
            'trim the image with the top-left orientation' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
                'options' => ['trim' => ['top-left']],
                'expected_url' => 'https://cdn.example.com/unsafe/trim:top-left/https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg',
            ],
            'add a gifv filter to the image' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://images.mamilove.com.tw/test/product/details/1f3f1844-0cc2-11ed-9b01-0242c0a85007.gif',
                'options' => ['filter' => ['gifv', 'mp4']],
                'expected_url' => 'https://cdn.example.com/unsafe/filters:gifv(mp4)/https://images.mamilove.com.tw/test/product/details/1f3f1844-0cc2-11ed-9b01-0242c0a85007.gif',
            ],
        ];
    }
}
