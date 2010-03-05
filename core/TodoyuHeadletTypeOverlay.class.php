<?php

abstract class TodoyuHeadletTypeOverlay extends TodoyuHeadlet {

	protected function initType() {
		$this->setTemplate('core/view/headlet-type-overlay.tmpl');

		$this->addButtonAttribute('class', 'headletTypeOverlay');
	}

	protected function setOverlayContent($content) {
		$this->data['overlayContent'] = $content;
	}

}

?>