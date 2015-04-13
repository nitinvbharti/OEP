var validNavigation = false;
 
function wireUpEvents() {
  
  //var dont_confirm_leave = 0; //set dont_confirm_leave to 1 when you want the user to be able to leave withou confirmation
  var leave_message = 'Please use logout button to exit';
  

  function goodbye() {
    if (!validNavigation) {
       {
        
        if(!e) e = window.event;
        //alert(e.type);
        e.cancelBubble is supported by IE - this will kill the bubbling process.
        e.cancelBubble = false;
        e.returnValue = leave_message;
        e.stopPropagation works in Firefox.
        


        if (e.stopPropagation) {
          e.stopPropagation();
          e.preventDefault();
        }
    /*
    //chrome.windows.onRemoved.addListener
      //return works for Chrome and Safari
         var ajaxRequest;
       ajaxRequest.onreadystatechange = function(){
   
      if(ajaxRequest.readyState == 4){
         var ajaxDisplay = document.getElementById('ajaxDiv');
         ajaxDisplay.innerHTML = ajaxRequest.responseText;
      }
   }


         ajaxRequest.open("GET", "ajax.php", true);
         ajaxRequest.send(null); 
*/
      alert("Please use log out button to exit");
        return leave_message;
      }
    }
  }
  

  // Attach the event click for all links in the page
  $("a").bind("click", function() {
    validNavigation = true;
  });
  $("a").bind("submit", function() {
    validNavigation = true;
  });
 
  // Attach the event submit for all forms in the page
  $("form").bind("submit", function() {
    validNavigation = true;
  });
 
  // Attach the event click for all inputs in the page
  $("input[type=submit]").bind("submit", function() {
    validNavigation = true;
  });

$("button").bind("click", function() {
    validNavigation = true;
  });

if(validNavigation==false)
  window.onbeforeunload=goodbye;
else
  window.onbeforeunload='';
//chrome.windows.onRemoved.addListener(function goodbye);
}
 
// Wire up the events as soon as the DOM tree is ready
$(document).ready(function() {
  wireUpEvents();
});