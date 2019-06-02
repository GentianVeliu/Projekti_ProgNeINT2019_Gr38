

var myImage1=document.getElementById("ip3");


var imageArray1=["background1.jpg","background2.jpg","background3.jpg","background4.jpg","background5.jpg"];
var imageIndex1=0;
function changeImage1()
{
	ip3.setAttribute("src",imageArray1[imageIndex1]);
	imageIndex1++;
	if(imageIndex1>=imageArray1.length)
	{
		imageIndex1=0;
		
	}
}

var intervalImage1=setInterval(changeImage1,2000);
















