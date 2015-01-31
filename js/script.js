

function manage_count()
{
 var feedback=document.getElementById("feedback");
 var count=document.getElementById('count');
 var len=feedback.value.length;
 //count.value=result;
 count.innerHTML=1000-len;
 //alert(count.value);
 //alert("hai");
}