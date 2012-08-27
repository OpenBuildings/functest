<?php
/**
 * Func_Test Native Driver Helper - handles forms interacion
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Native_Forms
{
	protected $dom;
	protected $xpath;

	function __construct($dom, $xpath) 
	{
		$this->dom = $dom;
		$this->xpath = $xpath;
	}

	public function get_value($xpath)
	{
		$node = $this->xpath->find($xpath);

		switch ($node->tagName) 
		{
			case 'textarea':
				return $node->textContent;
			break;

			case 'select':
				$options = array();
				foreach ($this->xpath->query(".//option[@selected]", $node) as $option)
				{
					$options[] = $option->hasAttribute('value') ? $option->getAttribute('value') : $option->textContent;
				}
				return $node->hasAttribute('multiple') ? $options : Arr::get($options, 0);
			break;
			default:
				return $node->getAttribute('value');
		}
	}

	public function set_value($xpath, $value)
	{
		$node = $this->xpath->find($xpath);

		$setter = 'set_value_input';

		if ($node->tagName == 'input' AND $node->getAttribute('type') == 'checkbox')
		{
			$setter = 'set_value_checkbox';
		}

		elseif ($node->tagName == 'input' AND $node->getAttribute('type') == 'radio')
		{
			$setter = 'set_value_radio';
		}

		elseif ($node->tagName == 'textarea')
		{
			$setter = 'set_value_textarea';
		}

		elseif ($node->tagName == 'option')
		{
			$setter = 'set_value_option';
		}

		$this->{$setter}($node, $value);
	}

	public function set_value_checkbox(DOMNode $checkbox, $value)
	{
		if ($value)
		{
			$checkbox->setAttribute('checked', 'checked');
		}
		else
		{
			$checkbox->removeAttribute('checked');
		}
	}

	public function set_value_radio(DOMNode $radio, $value)
	{
		$name = $radio->getAttribute('name');
		foreach ($this->xpath->query("//input[@type='radio' and @name='$name' and @checked]") as $other_radio) 
		{
			$other_radio->removeAttribute('checked');
		}
		if ($value)
		{
			$radio->setAttribute('checked', 'checked');
		}
	}

	public function set_value_input(DOMNode $input, $value)
	{
		$input->setAttribute('value', $value);
	}

	public function set_value_textarea(DOMNode $textarea, $value)
	{
		$textarea->nodeValue = $value;
	}

	public function set_value_option(DOMNode $option, $value)
	{
		$select = $this->xpath->find("./ancestor::select", $option);

		if ( ! $select->hasAttribute('multiple'))
		{
			foreach ($this->xpath->query(".//option[@selected]", $select) as $old_option) 
			{
				$old_option->removeAttribute('selected');
			}
		}

		if ($value)
		{
			$option->setAttribute('selected', 'selected');
		}
		else
		{
			$option->removeAttribute('selected');	
		}
	}

	public function serialize_form($xpath)
	{
		$form = $this->xpath->find($xpath);

		$types['radio']    = "(self::input and @type = 'radio' and @checked)";
		$types['checkbox'] = "(self::input and @type = 'checkbox' and @checked)";
		$types['hidden']   = "(self::input and @type = 'hidden')";
		$types['password'] = "(self::input and @type = 'password')";
		$types['text']     = "(self::input and @type = 'text')";
		$types['notype']   = "(self::input and not(@type))";
		$types['select']   = "(self::select)";
		$types['textarea'] = "(self::textarea)";

		$fields = ".//*[not(@disabled) and (".join(' or ', $types).")]";
		$data = array();
		foreach ($this->xpath->query($fields, $form) as $field) 
		{
			$value = $this->get_value($field);
			if (is_array($value))
			{
				foreach ($value as $name => $value_item) 
				{
					$data[] = $field->getAttribute('name')."[$name]".'='.$value_item;
				}
			}
			else
			{
				$data[] = $field->getAttribute('name').'='.$value;
			}
		}

		return join('&', $data);
	}
}