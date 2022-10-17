<?php

namespace Thumbor;

class Builder
{
    /**
     * The unsafe path to make unencryted URL to be available.
     *
     * @var string
     */
    private const UNSAFE_PATH = 'unsafe';

    /**
     * The server URL for thumbor instances.
     *
     * @var string
     */
    private $server;

    /**
     * The security key to generate a hash-based message authentication code.
     *
     * @var string|null
     */
    private $secret;

    /**
     * The original image URL.
     *
     * @var string
     */
    private $url;

    /**
     * The crop option to resize the image.
     *
     * @var string|null
     */
    private $crop;

    /**
     * A list of filter options to process the image.
     *
     * @link https://thumbor.readthedocs.io/en/latest/filters.html?highlight=filters*
     *
     * @var array
     */
    private $filters = [];

    /**
     * The size option to resize the image.
     *
     * @var string|null
     */
    private $resize;

    /**
     * The trim option to remove surrounding space in image.
     *
     * @var string|null
     */
    private $trim;

    /**
     * Create a new builder instance.
     *
     * @param string      $server
     * @param string|null $secret
     *
     * @return void
     */
    public function __construct(string $server, string $secret = null)
    {
        $this->server = $server;
        $this->secret = $secret;
    }

    /**
     * Manual crop the given image URL.
     *
     * @param int $left
     * @param int $top
     * @param int $right
     * @param int $bottom
     *
     * @return Builder
     */
    public function crop(int $left, int $top, int $right, int $bottom): self
    {
        $this->crop = "{$left}x{$top}:{$right}x{$bottom}";

        return $this;
    }

    /**
     * Append a filter and arguments to the pipeline.
     *
     * @param string $filter
     * @param array  ...$args
     *
     * @return Builder
     */
    public function filter(string $filter, ...$args): self
    {
        $this->filters[] = sprintf('%s(%s)', $filter, implode(',', $args));

        return $this;
    }

    /**
     * Resize the image to fit in a box of the specified dimensions.
     *
     * @param int $width
     * @param int $height
     *
     * @return Builder
     */
    public function fitIn(int $width = 0, int $height = 0): self
    {
        $this->resize = "fit-in/{$width}x{$height}";

        return $this;
    }

    /**
     * Resize the image to fit smallest side in a box of the specified dimensions.
     *
     * @param int $width
     * @param int $height
     *
     * @return Builder
     */
    public function fullFitIn(int $width = 0, int $height = 0): self
    {
        $this->resize = "full-fit-in/{$width}x{$height}";

        return $this;
    }

    /**
     * Resize the given image URL, set default value 0 to retain original size of the image.
     *
     * @param int $width
     * @param int $height
     *
     * @return Builder
     */
    public function resize(int $width = 0, int $height = 0): self
    {
        $this->resize = "{$width}x{$height}";

        return $this;
    }

    /**
     * Trim surrounding space from the thumbnail. The top-left corner of the
     * image is assumed to contain the background colour. To specify otherwise,
     * pass either `top-left` or `bottom-right` as the orientation argument.
     * For tolerance the euclidian distance between the colors of the reference pixel
     * and the surrounding pixels is used. If the distance is within the
     * tolerance they'll get trimmed. For a RGB image the tolerance would
     * be within the range 0-442
     *
     * @param string|null $orientation
     * @param string|null $tolerance
     *
     * @return Builder
     */
    public function trim(string $orientation = null, string $tolerance = null): self
    {
        $this->trim = 'trim' . ($orientation ? ":{$orientation}" : '') . ($tolerance ? ":{$tolerance}" : '');

        return $this;
    }

    /**
     * Set the original image URL.
     *
     * @param string $url
     *
     * @return Builder
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Build the full image endpoint.
     *
     * @return string
     */
    public function build(): string
    {
        $path = implode('/', array_filter([
            $this->trim,
            $this->crop,
            $this->resize,
            $this->filters ? 'filters:' . implode(':', $this->filters) : '',
            $this->url,
        ]));

        $signature = $this->secret ? $this->sign($path, $this->secret) : self::UNSAFE_PATH;

        return implode('/', [$this->server, $signature, $path]);
    }

    /**
     * Generate a signature for image URL with the security key.
     *
     * @param string $data
     * @param string $secret
     *
     * @return string
     */
    private function sign(string $data, string $secret): string
    {
        return strtr(base64_encode(hash_hmac('sha1', $data, $secret, true)), '/+', '_-');
    }

    /**
     * Convert thumbor arguments as string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }
}
