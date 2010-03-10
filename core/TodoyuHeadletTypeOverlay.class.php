<?php

abstract class TodoyuHeadletTypeOverlay extends TodoyuHeadlet {

	protected $type = 'overlay';

	protected function initType() {
		//$this->setTemplate('core/view/headlet-type-overlay.tmpl');

		$this->addButtonAttribute('class', 'headletTypeOverlay');
	}

	abstract function renderOverlayContent();

	public function render() {
		$this->data['content'] = $this->renderOverlayContent();

		return parent::render();
	}

}

?>