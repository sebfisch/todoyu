<?php

abstract class TodoyuHeadletTypeMenu extends TodoyuHeadlet {

	protected $type = 'menu';

	protected function initType() {

	}

	abstract protected function getMenuItems();


	private function renderMenuItems() {
		$items	= $this->getMenuItems();

		$tmpl	= 'core/view/headlet-menu.tmpl';
		$data	= array(
			'id'	=> $this->getID(),// 'xxx',
			'items'	=> $items
		);

		return render($tmpl, $data);
	}


	public function render() {
		$this->data['content'] = $this->renderMenuItems();

		return parent::render();
	}
}

?>