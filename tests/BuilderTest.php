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
        $builder = (new Builder($server, $secret))->url($url);

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
                'url' => 'https://dummyimage.com/600x400.jpg',
                'options' => ['crop' => [100, 200, 300, 400]],
                'expected_url' => 'https://cdn.example.com/unsafe/100x200:300x400/https://dummyimage.com/600x400.jpg',
            ],
            'resize the image size in 200x100 pixels ' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://dummyimage.com/600x400.jpg',
                'options' => ['resize' => [100, 200]],
                'expected_url' => 'https://cdn.example.com/unsafe/100x200/https://dummyimage.com/600x400.jpg',
            ],
            'resize the image size with encrypted URL' => [
                'server' => 'https://cdn.example.com',
                'secret '=> 'abc',
                'url' => 'https://dummyimage.com/600x400.jpg',
                'options' => ['resize' => [100, 200]],
                'expected_url' => 'https://cdn.example.com/avUQIEshAEWnjrdSn25wgho4TdM=/100x200/https://dummyimage.com/600x400.jpg',
            ],
            'fit the image in an imaginary box by 200x100 pixels' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://dummyimage.com/600x400.jpg',
                'options' => ['fitIn' => [100, 200]],
                'expected_url' => 'https://cdn.example.com/unsafe/fit-in/100x200/https://dummyimage.com/600x400.jpg',
            ],
            'full-fit the image in an imaginary box by 200x100 pixels' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://dummyimage.com/600x400.jpg',
                'options' => ['fullFitIn' => [100, 200,]],
                'expected_url' => 'https://cdn.example.com/unsafe/full-fit-in/100x200/https://dummyimage.com/600x400.jpg',
            ],
            'trim the image without surrounding space' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://dummyimage.com/600x400.jpg',
                'options' => ['trim' => []],
                'expected_url' => 'https://cdn.example.com/unsafe/trim/https://dummyimage.com/600x400.jpg',
            ],
            'trim the image with the top-left orientation' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://dummyimage.com/600x400.jpg',
                'options' => ['trim' => ['top-left']],
                'expected_url' => 'https://cdn.example.com/unsafe/trim:top-left/https://dummyimage.com/600x400.jpg',
            ],
            'add a gifv filter to the image' => [
                'server' => 'https://cdn.example.com',
                'secret '=> '',
                'url' => 'https://www.sample-videos.com/gif/2.gif',
                'options' => ['filter' => ['gifv', 'mp4']],
                'expected_url' => 'https://cdn.example.com/unsafe/filters:gifv(mp4)/https://www.sample-videos.com/gif/2.gif',
            ],
        ];
    }
}
