<?php
/**
 * Description of MPCEObject
 *
 */
class MPCEObject extends MPCEElement {
    public $closeType;
    public $resize;
    public $parameters = array();
    public $styles = array(
        'mp_style_classes' => array(
            'basic' => array(),
            'predefined' => array(),
            'default' => array(),
            'selector' => ''
        ),
		'mp_custom_style' => array(
			'limitation' => array(),
			'selector' => ''
		)
    );

    protected $errors = array(
        'id' => array(),
        'name' => array(),
        'icon' => array(),
        'parameters' => array(),
        'styles' => array(),
        'position' => array(),
        //'title' => array(),
        'closeType' => array(),
        'resize' => array(),
        'show' => array()
    );

    const SELF_CLOSED = 'self-closed';
    const ENCLOSED = 'enclosed';

    const ICON_DIR = 'object';

    const RESIZE_NONE = 'none';
    const RESIZE_HORIZONTAL = 'horizontal';
    const RESIZE_VERTICAL= 'vertical';
    const RESIZE_ALL= 'all';

    /**
     * @param string $id
     * @param string $name
     * @param string $icon [optional]
     * @param array $parameters [optional]
     * @param int $position [optional]
     * @param string $closeType [optional]
     * @param string $resize [optional]
     * @param boolean $show [optional]
     */
    public function __construct($id, $name, $icon = 'no-object.png', $parameters = array(), $position = 0, $closeType = self::SELF_CLOSED, $resize = self::RESIZE_HORIZONTAL, $show = true) {
        $this->setId($id);

        $this->setName($name);

        if (empty($icon)) {
            $icon = 'no-object.png';
        }
        $this->setIcon($icon);

        if (!empty($parameters)) {
            $this->addParameter($parameters);
        } else {
            $this->parameters = new stdClass();
        }

        if (empty($position)) {
            $position = 0;
        }
        $this->setPosition($position);

        if (empty($closeType)) {
            $closeType = self::SELF_CLOSED;
        }
        $this->setCloseType($closeType);

        if (empty($resize)) {
            $resize = self::RESIZE_HORIZONTAL;
        }
        $this->setResize($resize);

        $this->setShow($show);
    }

    public function setIcon($icon) {
        parent::icon($icon, self::ICON_DIR);
    }

    /**
     * @return string
     */
    public function getCloseType() {
        return $this->closeType;
    }

    /**
     * @global stdClass $motopressCELang
     * @param string $closeType
     */
    public function setCloseType($closeType) {
        global $motopressCELang;

        if (is_string($closeType)) {
            $closeType = trim($closeType);
            if (!empty($closeType)) {
                $closeType = filter_var($closeType, FILTER_SANITIZE_STRING);
                if ($closeType === self::SELF_CLOSED || $closeType === self::ENCLOSED) {
                    $this->closeType = $closeType;
                } else {
                    $this->addError('closeType', strtr($motopressCELang->CEValues, array('%values%' => implode(', ', array(self::SELF_CLOSED, self::ENCLOSED)))));
                }
            } else {
                $this->addError('closeType', $motopressCELang->CEEmpty);
            }
        } else {
            $this->addError('closeType', strtr($motopressCELang->CEInvalidArgumentType, array('%name%' => gettype($closeType))));
        }
    }

    /**
     * @return string
     */
    public function getResize() {
        return $this->resize;
    }

    /**
     * @global stdClass $motopressCELang
     * @param string $resize
     */
    public function setResize($resize) {
        global $motopressCELang;

        if (is_string($resize)) {
            $resize = trim($resize);
            if (!empty($resize)) {
                $resize = filter_var($resize, FILTER_SANITIZE_STRING);
                if (
                    $resize === self::RESIZE_NONE || $resize === self::RESIZE_HORIZONTAL ||
                    $resize === self::RESIZE_VERTICAL || $resize === self::RESIZE_ALL
                ) {
                    $this->resize = $resize;
                } else {
                    $this->addError('resize', strtr($motopressCELang->CEValues, array('%values%' => implode(', ', array(self::RESIZE_NONE, self::RESIZE_HORIZONTAL, self::RESIZE_VERTICAL, self::RESIZE_ALL)))));
                }
            } else {
                $this->addError('resize', $motopressCELang->CEEmpty);
            }
        } else {
            $this->addError('resize', strtr($motopressCELang->CEInvalidArgumentType, array('%name%' => gettype($resize))));
        }
    }

    /**
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * @param string $id
     * @return array
     */
    public function &getParameter($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->parameters)) {
                        return $this->parameters[$id];
                    }
                }
            }
        }
        $parameter = false;
        return $parameter;
    }

    /**
     * @param array $parameter
     */
    public function addParameter(array $parameter) {
        global $motopressCELang;

        if (!empty($parameter)) {
            foreach ($parameter as $key => $value) {
                if (!array_key_exists($key, $this->parameters) && !array_key_exists($key, MPCEShortcode::$styles)) {
                    $this->parameters[$key] = $value;
                }
            }
        } else {
            $this->addError('parameters', $motopressCELang->CEEmpty);
        }
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function removeParameter($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->parameters)) {
                        unset($this->parameters[$id]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getStyles() {
        return $this->styles;
    }

    /**
     * @param string $id
     * @return array
     */
    public function &getStyle($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->styles)) {
                        return $this->styles[$id];
                    }
                }
            }
        }
        $style = false;
        return $style;
    }

    /**
	 *
     * @param array $style 
	 * 
     */
	public function addStyle(array $style) {
	   global $motopressCELang;
	   $backgroundImageLimitations = array(
		   'background-image-type',
		   'background-image',
		   'background-gradient',
		   'background-position',
		   'background-position-x',
		   'background-position-y',
		   'background-repeat',
		   'background-attachment',
		   'background-size'
	   );
	   if (!empty($style)) {
		   foreach ($style as $key => $value) {
			   switch($key) {
				   case 'mp_style_classes':
					   if (empty($this->styles['mp_style_classes']['basic']) && empty($this->styles['mp_style_classes']['predefined']) &&
						   empty($this->styles['mp_style_classes']['default']) && empty($this->styles['mp_style_classes']['selector']))
					   {
						   $this->styles['mp_style_classes'] = $value;
					   }
					   break;
				   case 'mp_custom_style':
					   if (isset($value['selector'])) {
						   $this->styles['mp_custom_style']['selector'] = $value['selector'];
					   }
					   if (isset($value['limitation'])) {
						   foreach ((array) $value['limitation'] as $limitation) {
							   $this->addLimitation($limitation);
						   }						   
					   }
					   break;
				   default:
					   if (!array_key_exists($key, $this->styles)){
						   $this->styles[$key] = $value;
					   }
					   break;
			   }
		   }
	   } else {
		   $this->addError('styles', $motopressCELang->CEEmpty);
	   }
   }

   /**
    *
    * @param string $limitation Possible limitation values
	*						Margins: 'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'margin-vertical', 'margin-horizontal',
	*						Paddings: 'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left', 'padding-vertical', 'padding-horizontal',
	*						Borders: 'border', 'border-top-width', 'border-bottom-width', 'border-left-width', 'border-right-width', 'border-width', 'border-style', 'border-color',
	*								'border-radius', 'border-top-left-radius', 'border-top-right-radius', 'border-bottom-left-radius', 'border-bottom-right-radius'
	*						Background: 'background', 'background-color', 'background-image', 'background-position', 'background-repeat'
	*						Other: 'text-color'
    */
   private function addLimitation($limitation){	   
	   $this->styles['mp_custom_style']['limitation'] = array_unique( array_merge($this->styles['mp_custom_style']['limitation'], $this->filterLimitation($limitation)) );
	}

	private function filterLimitation($limitation){
		$limitations = array();
		switch ($limitation) {
			case 'background-image':
				$limitations[] = 'background-image-type';
				$limitations[] = 'background-image';
				$limitations[] = 'background-gradient';
				$limitations = array_merge($limitations, $this->filterLimitation('background-position'));
				$limitations[] = 'background-repeat';
				$limitations[] = 'background-size';
				$limitations[] = 'background-attachment';	
			case 'margin-horizontal':
				$limitations[] = 'margin-left';
				$limitations[] = 'margin-right';
				break;
			case 'margin-vertical':
				$limitations[] = 'margin-top';
				$limitations[] = 'margin-bottom';
				break;
			case 'padding-horizontal':
				$limitations[] = 'padding-left';
				$limitations[] = 'padding-right';
				break;
			case 'padding-vertical':
				$limitations[] = 'margin-top';
				$limitations[] = 'margin-bottom';
				break;
			case 'padding':
				$limitations = array_merge($limitations, $this->filterLimitation('padding-horizontal'));
				$limitations = array_merge($limitations, $this->filterLimitation('padding-vertical'));
				break;
			case 'margin':
				$limitations = array_merge($limitations, $this->filterLimitation('margin-horizontal'));
				$limitations = array_merge($limitations, $this->filterLimitation('margin-vertical'));
				break;
			case 'background-position':
				$limitations[] = 'background-position';
				$limitations[] = 'background-position-x';
				$limitations[] = 'background-position-y';
				break;
			case 'border-width':
				$limitations[] = 'border-top-width';
				$limitations[] = 'border-bottom-width';
				$limitations[] = 'border-left-width';
				$limitations[] = 'border-right-width';
				break;
			case 'border-radius':
				$limitations[] = 'border-top-left-radius';
				$limitations[] = 'border-top-right-radius';
				$limitations[] = 'border-bottom-left-radius';
				$limitations[] = 'border-bottom-right-radius';
				break;
			case 'border':
				$limitations = array_merge($limitations, $this->filterLimitation('border-width'));
				$limitations = array_merge($limitations, $this->filterLimitation('border-radius'));
				$limitations[] = 'border-style';
				$limitations[] = 'border-color';
				break;
			case 'background':
				$limitations = array_merge($limitations, $this->filterLimitation('background-image'));				
				$limitations[] = 'background-color';			
				break;
			default:
				$limitations[] = $limitation;
				break;
		}
		return $limitations;
	}
    /**
     * @param string $id
     * @return boolean
     */
    public function removeStyle($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->styles)) {
                        unset($this->styles[$id]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function isValid() {
        return (
            empty($this->errors['id']) &&
            empty($this->errors['name']) &&
            empty($this->errors['icon']) &&
            //empty($this->errors['title']) &&
            empty($this->errors['closeType']) &&
            empty($this->errors['position']) &&
            empty($this->errors['resize']) &&
            empty($this->errors['show']) &&
            empty($this->errors['parameters']) &&
            empty($this->errors['styles'])
        ) ? true : false;
    }

    /**
     * @return string
     */
    public function __toString() {
        $str = 'id: ' . $this->getId() . ', ';
        $str .= 'name: ' . $this->getName() . ', ';
        $str .= 'icon: ' . $this->getIcon() . ', ';
        //$str .= 'title: ' . $this->getTitle() . ', ';
        $str .= 'closeType: ' . $this->getCloseType() . ', ';
        $str .= 'resize: ' . $this->getResize() . ', ';
        $str .= 'position: ' . $this->getPosition() . ', ';
        $str .= 'show: ' . $this->getShow();
        return $str;
    }
}