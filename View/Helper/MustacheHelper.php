<?php
/** Mustache view helper for CakePHP
 *
 * 2011 BetterLesson Inc.
 * Andrew Drane
 * Jonathan Hendler
 *
 * Process and render Mustache templates into your CakePHP application.
 *
 * Requires that the Mustache.php library is copied to your Vendor directory
 * Available from:
 * https://github.com/bobthecow/mustache.php/
 *
 *
 * A few conventions:
 *
 * Replace / in an element path with __ - taht way Javascript can deal with it.
 *
 * Element: the name of the element - like you would use in CakePHP, but replace '/' with '__'
 *  For example users__name corresponds to app/views/elements/users/name.mustache
 *
 * Template: the text string from an element, which is read from the element file.
 *  This is what gets passed into Mustache for conversion to HTML
 *
 * Partials: elements called within elements. To include an element call within
 *  your element, simply use the element convention in the proper context!
 *  Mustache takes care of the Data context
 *
 *
 *
 */

class MustacheHelper extends AppHelper {
	protected $defaults	= array(
		'path'		=> false,
		'extension'	=> 'mustache',
		'viewVars'	=> true
	);
	public $options		= array();
	protected $path		= '';
	protected $mustacheEngine;

	public function __construct(View $View, $options = array()) {
		$this->options			= array_merge($this->defaults, $this->options, $options);
		$this->options['path']	= $this->getTemplatePath();

		$this->View = $View;

		$this->setEngineInstance();

		return parent::__construct($View, $options);
	}

	protected function setEngineInstance($path = false) {
		$path = $path ? $path : $this->options['path'];
		return $this->mustacheEngine = new Mustache_Engine(array(
			'loader'          => new Mustache_Loader_FilesystemLoader($path),
			'partials_loader' => new Mustache_Loader_FilesystemLoader($path)
		));
	}

	protected function getTemplatePath() {
		$path = Configure::read('Mustache.path');
		if (is_dir($path)) {
			return $path;
		}

		$path	= $this->options['path'];
		if (is_dir($path)) {
			return $path;
		}

		$path	= WWW_ROOT . 'mustache';
		if (is_dir($path)) {
			return $path;
		}

		$this->throwError(__('No valid path found for mustache templates'));
		return false;
	}

	protected function throwError($mustacheError = '') {
		if (Configure::write('debug') > 0) {
			return debug(compact('mustacheError'));
		}
		return CakeLog::write('error', compact('mustacheError'));
	}

	public function render($template, $context = array()) {
		$return		= false;
		$context	= array_merge_recursive($this->_View->viewVars, $context);
		try {
			$return = $this->mustacheEngine->render($template, $context);
		} catch ( Exception $mustacheError ) {
			$this->throwError($mustacheError);
		}
		return $return;
	}

	public function __call($name = false, $arguments = array()) {
		if (!$name) {
			return false;
		}
		$context = false;
		if (is_callable(array($this, $name))) {
			$context = $this;
		}
		if (is_callable(array($this->mustacheEngine, $name))) {
			$context = $this->mustacheEngine;
		}
		if (!$context) {
			return false;
		}
		return call_user_func_array(array(
			$context, $name
		), $arguments);
	}

	public static function __callStatic($name = false, $arguments = array()) {
		if (!$name) {
			return false;
		}
		return forward_static_call_array(array(
			Mustache_Engine, $name
		), $arguments);
	}
}