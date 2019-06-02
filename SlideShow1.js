

var myImage=document.getElementById("ip4");


var imageArray=["airprishtina.png","sharrtravel.png","ipko.png"];
var imageIndex=0;
function changeImage()
{
	
	ip4.setAttribute("src",imageArray[imageIndex]);
	imageIndex++;
	if(imageIndex>=imageArray.length)
	{
		imageIndex=0;
		
	}
}

var intervalImage=setInterval(changeImage,4000);

















