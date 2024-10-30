<?php

/**
 * @package   Building
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @todo      This view still needs to be implemented. Leaving the
 *            implementation incomplete until we actually need to use it.
 */
class BuildingBlockAudioView extends BuildingBlockView
{
	// {{{ protected function displayContent()

	protected function displayContent(BuildingBlock $block)
	{
		if (!$block->media instanceof SiteAudioMedia) {
			throw new InvalidArgumentException(
				sprintf(
					'The view %s can only display blocks with audio.',
					get_class($this)
				)
			);
		}
	}

	// }}}
}

?>
