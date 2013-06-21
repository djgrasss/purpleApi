(function($) {
	
	//Props

	//Functions

	var generateTable = function(list, columns) 
	{
		var returnCode = '<table border>';

		// Columns header
		if ((typeof list[0]) == 'object')
		if (columns != null)
		{
			returnCode += "<thead>";
			returnCode += "<tr>";
			for (var i = 0; i < columns.length; i++)
				returnCode += "<th>" + columns[i] + "</th>";
			returnCode += "</tr>";
			returnCode += "</thead>";
		}

		returnCode += '<tbody>';
		for (var key in list) {
			var value = list[key];
			var dataType = typeof value;
			var keyType = isNaN(parseInt(key)) ? typeof key : null;

			// Show value
			if (['string','number'].indexOf(dataType) != -1)
			{
				returnCode += '<tr>';
				// Show key
				if (keyType != null)
					returnCode += '<td>' + key + '</td>';
				returnCode += '<td>' + value + '</td>';
				returnCode += '</tr>';
			}
			else if (dataType == 'object')
			{	
				if (columns == null)
				{
					returnCode += '<tr>';
					returnCode += '<td>' + generateTable(value) + '</td>';
					returnCode += '</tr>';
				}
				else
				{
					returnCode += '<tr>';
					for (var i = 0; i < columns.length; i++)
						returnCode += '<td>' + value[columns[i]] + '</td>';
					returnCode += '</tr>';
				}
			}
			else
			{
				returnCode += 'UNTREATABLE TYPE : ' + (typeof value).toString();
			}
		};
		returnCode += '</tbody>';
		returnCode += '</table>';

		return returnCode;
	};


	//Plugin
	$.fn.purpleUiTable = function(list, columns) {
		//For each target of the plugin...
		$(this).each(function() {
			//Start of code plugin
			$(this).html(generateTable(list, columns));
			//End of code plugin
		});
	};
})(jQuery);