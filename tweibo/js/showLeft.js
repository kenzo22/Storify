function ShowHouse(TypeName,Dictionary)
{
    var url="./demo/" + encodeURI(TypeName) + "/" + encodeURI(Dictionary) + ".php";
    if(top.document.frames)
	    top.document.frames["mainFrame"].location.href=url;    
	else
	    top.document.getElementsByName("mainFrame")[0].contentWindow.location.href=url;	
}
function changeMenu(obj)
{
    if(obj==getCurMenu()) 
    {
       return;
    }
    if(getCurMenu()!="")
    {
        document.getElementById("img" + getCurMenu()).src="images/left_title_20070514_r.gif";
        document.getElementById("div" + getCurMenu()).style.display="none";
    }
    document.getElementById("div" + obj).style.display="block";
    document.getElementById("img" + obj).src="images/left_title_20070514.gif";
    
}
function getCurMenu()
{
    if(document.getElementById("divStyle").style.display!="none")
        return "Style";   
	if(document.getElementById("divStyle1").style.display!="none")
        return "Style1";
    if(document.getElementById("divStyle2").style.display!="none")
        return "Style2";   
	if(document.getElementById("divStyle3").style.display!="none")
        return "Style3";
	if(document.getElementById("divStyle4").style.display!="none")
        return "Style4";
	if(document.getElementById("divStyle5").style.display!="none")
        return "Style5";
	if(document.getElementById("divStyle6").style.display!="none")
        return "Style6";
	if(document.getElementById("divStyle7").style.display!="none")
        return "Style7";
	if(document.getElementById("divStyle8").style.display!="none")
        return "Style8";
	if(document.getElementById("divStyle9").style.display!="none")
        return "Style9";
	if(document.getElementById("divStyle10").style.display!="none")
        return "Style10";
    return "";
}