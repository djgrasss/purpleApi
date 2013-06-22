(function($) {

	var generateTable = function(params) 
	{
		var 
			list = params.data,
		 	columns = params.columns,
		 	tableCssClass = params.tableCssClass;
			
		var returnCode = "<table class='" + tableCssClass + "'>";

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

		//Table content
		returnCode += '<tbody>';
		for (var key in list) {
			var value = list[key];
			var keyType = isNaN(parseInt(key)) ? typeof key : null;

			returnCode += '<tr>';

			switch(typeof value)
			{
				case 'string':
				case 'number':
					// Show key
					if (keyType != null)
						returnCode += '<td>' + key + '</td>';
					returnCode += '<td>' + value + '</td>';
				break;
				case 'object':
					if (columns == null)
						returnCode += '<td>' + generateTable(value) + '</td>';
					else
					for (var i = 0; i < columns.length; i++)
						returnCode += '<td>' + value[columns[i]] + '</td>';
				break;
				default:
					returnCode += 'UNTREATABLE TYPE : ' + (typeof value).toString();
				break;
			}

			returnCode += '</tr>';
		};
		returnCode += '</tbody>';
		returnCode += '</table>';

		return returnCode;
	};


	//Plugin
	$.fn.purpleUiTable = function(params) {
		//For each target of the plugin...
		$(this).each(function() {
			//Start of code plugin
			$(this).html(generateTable(params));
			//End of code plugin
		});
	};
})(jQuery);