function validateForm() 
{
    var from = document.forms["frm"]["from"].value;
    var to = document.forms["frm"]["to"].value;
 
 if (!from || !to) 
	{
        alert("Please select both from and to dates of the special target ");
        return false;
    }
	
}