<?php

class Techinflo_Zipcodeavailability_Lib_Varien_Data_Form_Element_Zipcodeselect extends Varien_Data_Form_Element_Multiselect {

    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        $this->setType('select');
        $this->setExtType('multipleselecet');
        $this->setSize(8);
    }

    public function getName() {
        $name = parent::getName();
        if (strpos($name, '[]') === false) {
            $name.= '[]';
        }
        return $name;
    }

    public function getElementHtml() {
        $this->addClass('select js-multiselect multipleselecet');
        $html = '<div id="zipocdessellerzone" class="row"><div class="col-sm-5">';

        if ($this->getCanBeEmpty() && empty($this->_data['disabled'])) {
            $html .= '<input type="hidden" name="' . parent::getName() . '" value="" />';
        }
        $html .= '<select id="' . $this->getHtmlId() . '" name="' . $this->getName() . '" ' .
                $this->serialize($this->getHtmlAttributes()) . ' multiple>' . "\n";

        $value = $this->getValue();
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        if ($values = $this->getValues()) {
            foreach ($values as $option) {
                if (is_array($option['value'])) {
                    $html .= '<optgroup label="' . $option['label'] . '">' . "\n";
                    foreach ($option['value'] as $groupItem) {
                        $html .= $this->_optionToHtml($groupItem, $value);
                    }
                    $html .= '</optgroup>' . "\n";
                } else {
                    $html .= $this->_optionToHtml($option, $value);
                }
            }
        }

        $html .= '</select>' . "</div>";
        $html .= $this->getAfterElementHtml();
		$html .= '<div class="col-sm-2">
    		<button type="button" id="js_right_All_1" class="btn btn-block">&#10097;&#10097;</button>
    		<button type="button" id="js_right_Selected_1" class="btn btn-block">&#10095;</button>
    		<button type="button" id="js_left_Selected_1" class="btn btn-block">&#10094;</button>
    		<button type="button" id="js_left_All_1" class="btn btn-block">&#10096;&#10096;</button>
    	</div>
    	
    	<div class="col-sm-5">';
    	$html .= '<select name="to[]" id="js_multiselect_to_1" class="form-control" size="8" multiple="multiple">';
		$html .= $this->getMultiToSelected();
		'</select>
    	</div>
		</div>';
        return $html;
    }

    protected function _optionToHtml($option, $selected) {
        if (is_array($option['value'])) {
            $html = '<optgroup label="' . $option['label'] . '">' . "\n";
            foreach ($option['value'] as $groupItem) {
                $html .= $this->_optionToHtml($groupItem, $selected);
            }
            $html .='</optgroup>' . "\n";
        } else {
			 if (!in_array($option['value'], $selected)) {
				$html = '<option value="' . $this->_escape($option['value']) . '"';
				$html.= isset($option['title']) ? 'title="' . $this->_escape($option['title']) . '"' : '';
				$html.= isset($option['style']) ? 'style="' . $option['style'] . '"' : '';
				$html.= '>' . $this->_escape($option['label']) . '</option>' . "\n";
            }            
        }
        return $html;
    }

    public function getHtmlAttributes() {
        return array('title', 'class', 'style', 'onclick', 'onchange', 'disabled', 'size', 'tabindex');
    }

	public function getMultiToSelected(){
		$value = $this->getValue();		
		 if (!is_array($value)) {
            $values = explode(',', $value);
        }
		if($values){
			$tohtml = "";
			foreach($values as $value){
				$tohtml .= '<option value="' . $this->_escape($value) . '">';
				$tohtml .= $value;
				$tohtml .= '</option>';
			}
		}
		return $tohtml;
	}
	
    public function getLabelHtml($idSuffix = '') {
        if (!is_null($this->getLabel())) {
            $html = '<label for="' . $this->getHtmlId() . $idSuffix . '" style="' . $this->getLabelStyle() . '">' . $this->getLabel()
                    . ( $this->getRequired() ? ' <span class="required">*</span>' : '' ) . '</label>' . "\n";
        } else {
            $html = '';
        }
        return $html;
    }

}
