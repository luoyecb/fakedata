<?php
namespace Luoyecb\Generator;

use Luoyecb\IGenerator;

class UUIDGenerator implements IGenerator {
	public function createData() {
		$tmpStr = md5(uniqid().microtime().mt_rand());
		$uuid = substr($tmpStr, 0, 8) . '-';
		$uuid .= substr($tmpStr, 8, 4) . '-';
		$uuid .= substr($tmpStr, 12, 4) . '-';
		$uuid .= substr($tmpStr, 16, 4) . '-';
		$uuid .= substr($tmpStr, 20, 12);
		return $uuid;
	}
}
