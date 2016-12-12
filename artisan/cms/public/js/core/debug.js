

function safeLog(text) {
	
	//TODO: test if console.log exists
	console.log(text);
	
} //end safeLog()



function safeErr(text) {
	
	//TODO: test if console.log exists
	console.log(text);
	
} //end safeErr()




// return name of JavaScript object prototype of which object is a copy
function getClassName(object) {
	
	var _name = 'null';
	
	if (object) {
		
		//get object type
		_name = typeof(object);
		
		if (object.constructor) {
		
			// get classname abstracted from
			// constructor property
			_name = object.constructor.toString();
			
			if (_name) {
				var start = _name.indexOf('function ') + 9;
				var stop = _name.indexOf('(');
				_name = _name.substring(start, stop);
			}
			
		} //end if (object has constructor)
		
	} //end if (object exists)
	
	return _name;
	
} //end getClassName()






//return an array of all properties of an object
function printProperties(object, useAlert){

	//handle optional parameters
	if (useAlert===undefined || useAlert==null)
		useAlert = false;
		

	//get output string
	var _output = propertiesString(object);

	//print object properties
	if (useAlert) {
		alert(_output);
	}
	else {
		console.log(_output);
	}


}; //end printProperties()






function propertiesString(object, depth, ignoreFunctions, indentString){

	//handle optional parameters
	if (depth===undefined || depth==null)
		depth = -1;
	if (ignoreFunctions===undefined || ignoreFunctions==null) //TODO: implement
		ignoreFunctions = true;
	if (indentString===undefined || indentString==null)
		indentString = "";


	//adjust depth
	var _newDepth = depth>0 ? depth-1 : depth;

	//determine new indent string
	var _newIndentString = indentString + "\t";


	//output string
	var _output = indentString + "Object[" + getClassName(object) + "]: " + object + "\n";

	//process object
	if (object) {
	
		//get object keys
		for(var key in object){
			
			try {

				//property is type object
				if (typeof(object[key])=="object" && depth!=0) {
					
					//recurrsively process string
					_output += indentString + " - " + key + ": " + propertiesString(object[key], _newDepth, ignoreFunctions, _newIndentString);
					
				}
				//primitive type
				else {
					_output += indentString + " - " + key + ": " + object[key] + "\n";
				}
				
			}
			catch (e) {
				console.log("[debug.js] Error printing object properties for key[" + key + "]: " + e);
				continue;
			}
			
		} //end for()
   
	} //end if (object exists)
	
	return _output;
	
}; //end propertiesString()