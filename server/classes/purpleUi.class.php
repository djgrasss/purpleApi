<?php
class purpleUi
{

	static function renderTable($data)
	{
		$returnString = '';

		$returnString .= '<table border>';

		foreach($data as $key => $value)
		{
			$curValueType = gettype($value);

			$returnString .= "<tr>";
				$returnString .= "<td>$key</td>";
				
				$returnString .= "<td>";

				switch($curValueType)
				{
					case 'NULL';
						$returnString .= '&nbsp;';
						break;
					case 'string':
					case 'double':
					case 'integer':
						$returnString .= $value;
						break;
					case 'array':
					case 'object':
						$returnString .= purpleUi::renderTable($value);
						break;
					default:
						$returnString .= 'UNMANAGED:' . $curValueType;
					break;
				}
				$returnString .= "</td>";
			$returnString .= "</tr>";
		}
		$returnString .= '</table>';

		return $returnString;
	}
	

}

?>