<?php
/**
* Native Driver exception for avoiding exit on redirect
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
		foreach ($this->xpath->query("//input[@type='radio' and @name='$name' and @checked]") as $radio) 
		{
			$radio->removeAttribute('checked');
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
		$fields = "//*[(self::input and (((@type = 'radio' or @type = 'checkbox') and @checked) or (@type = 'hidden') or (@type = 'text') or not(@type))) or self::select or self::textarea]";

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