<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class GridView extends AbstractWidget {

	/**
	 * The configuration array.
	 *
	 * @var array
	 */
	protected $config = [
		'title'         => '',
		'listSim'       => [],
		'viewMoreUrl'   => '',
		'items' => 16,
		'amp'   => false
	];

	/**
	 * Treat this method as a controller action.
	 * Return view() or other content to display.
	 */
	public function run () {
		return view('widgets.grid_view', [
			'config' => $this->config,
		]);
	}
}
