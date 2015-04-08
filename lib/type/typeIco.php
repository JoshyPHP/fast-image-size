<?php

/**
 * fast-image-size image type ico
 * @package fast-image-size
 * @copyright (c) Marc Alexander <admin@m-a-styles.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fastImageSize\type;

class typeIco extends typeBase
{
	/** @var string ICO reserved field */
	const ICO_RESERVED = 0;

	/** @var int ICO type field */
	const ICO_TYPE = 1;

	/**
	 * {@inheritdoc}
	 */
	public function getSize($filename)
	{
		// Retrieve image data for ICO header and header of first entry.
		// We assume the first entry to have the same size as the other ones.
		$data = $this->fastImageSize->get_image($filename, 0, 2 * self::LONG_SIZE);
		$header = unpack('vreserved/vtype/vimages', $data);

		// Check if header fits expected format
		if ($header['reserved'] !== self::ICO_RESERVED || $header['type'] !== self::ICO_TYPE || $header['images'] < 1 || $header['images'] > 255)
		{
			return;
		}

		$size = unpack('Cwidth/Cheight', substr($data, self::LONG_SIZE + self::SHORT_SIZE, self::SHORT_SIZE));

		$this->fastImageSize->set_size($size);
		$this->fastImageSize->set_image_type(IMAGETYPE_ICO);
	}
}
