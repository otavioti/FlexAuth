var FLEXAUTH = function () {
	var undef;
	return {
		module: function (newmodule) {
			if (this[newmodule] === undef) {
				this[newmodule] = {};
				return this[newmodule];
			}
			return this[newmodule];
		}
	};
}() ;

FLEXAUTH.$ = jQuery.noConflict(true);


function resetSelect(objID){ 
	selObject=document.getElementById(objID) 
	//cycle through the options collection, setting the selected attribute of each to false 
	for (i=0;i<selObject.options.length;i++){ 
		selObject.options[i].selected=false; 
	}

} 

function resetRadioPermissions() {
	for(i in (inputs = document.getElementsByTagName('input'))) {
	    if(inputs[i].type === 'radio') {
	    	inputs[i].checked = false;
	    	if(inputs[i].value == "inheritance") {
	    		inputs[i].checked = true;
	    	}
	        
	    }
	  }
}


function mostraTest(name) {
    var g =name.split(".");
    var groupname ="";
    if(g.length>0) {
	  
	  groupname=g.shift();
	  var restante="";
	  
	  if(g.length>0) {
	    var first=0;
	    for(var gi=0;gi<g.length;gi++) {
		if(first == 0 ) {
		    restante = restante+g[gi];
		    first=1;
		  } else {
		    restante = restante+"."+g[gi];
		  }
	    }
	   mostraTest(restante);
	  }
    } 
    console.log("groupname: "+groupname);
    console.log("restante: "+restante);
 }



function addRowPermission(tableId,name,completename,code) {
	var $ = FLEXAUTH.$;
	var groups=name.split(".");
	if(groups.length>0) {
		var groupname = groups.shift();
	    
		if (groups.length>0) {
			var restante="";
			var first=0;
			for(var gi=0;gi<groups.length;gi++) {
				if(first == 0 ) {
				    restante = groups[gi];
				    first=1;
				} else {
				    restante = restante+"."+groups[gi];
				}
			 }
			
			var tId= groupname + "ID";
			
			if($('#'+tableId).length > 0) {
				addRowPermission(tId,restante,code);
				
			} else {
			
				var row = "<tr>" +
				"<td>" + name + "</td>" +
				"<td>&nbsp;</td>" +
				"</tr>";
				$('#'+tableId+" tbody").append(row);
				
				var row2 = "<tr><td colspan=2> " +
						"<table id cellpadding='0' cellspacing='0' id='"+ tId +"'  ><tbody></tbody></table>" +
						"</td> </tr>";
				$('#'+tableId+" tbody").append(row2);
				
			    console.log(row);
			    console.log(row2); 
			    addRowPermission(tId,restante,code);
			}
		} else {
			var row1 = "<tr>" +
			"<td>" + completename + "</td>" +
			"<td>" + code + "</td>" +
			"</tr>";
			//console.log(row1);
	        	
	
			$('#'+tableId+" tbody").append(row1);
		}
		
	} 
	
	
}

