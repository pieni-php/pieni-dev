<?php
class fallback {
	public static function get_fallback_path($seeds)
	{
		foreach (self::get_cartesian_product($seeds) as $segments) {
			$fallback_path = preg_replace('#/+#', '/', trim(implode('/', $segments), '/'));
			if (file_exists('./'.$fallback_path)) {
				return $fallback_path;
			}
		}
		return null;
	}

	public static function get_all_fallback_pathes($seeds)
	{
		$all_fallback_pathes = [];
		foreach (self::get_cartesian_product($seeds) as $segments) {
			$all_fallback_pathes[] = preg_replace('#/+#', '/', trim(implode('/', $segments), '/'));
		}
		return $all_fallback_pathes;
	}

	protected static function get_cartesian_product($seeds)
	{
		$cartesian_product = [];
		foreach ($seeds[0] as $head) {
			if (count($seeds) === 1) {
				$cartesian_product[] = [$head];
			} else {
				foreach (self::get_cartesian_product(array_slice($seeds, 1)) as $body) {
					$cartesian_product[] = array_merge([$head], $body);
				}
			}
		}
		return $cartesian_product;
	}
}
